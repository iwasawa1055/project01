<?php

App::uses('MinikuraController', 'Controller');
App::uses('CustomerKitPrice', 'Model');
App::uses('InboundDirect', 'Model');
App::uses('InboundDirectArrival', 'Model');
App::uses('DatePickup', 'Model');
App::uses('TimePickup', 'Model');

class DirectInboundController extends MinikuraController
{
    const MODEL_NAME = 'OrderKit';
    const MODEL_NAME_CARD = 'PaymentGMOCard';
    const MODEL_NAME_DATETIME = 'DatetimeDeliveryKit';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        // 法人口座未登録用遷移
        $actionCannot = 'cannot';
        if ($this->action !== $actionCannot && !$this->Customer->isEntry() && !$this->Customer->canOrderKit()) {
            return $this->redirect(['action' => $actionCannot]);
        }

        // 以下、スニーカーがあるため変更できない
        $this->Order = $this->Components->load('Order');
        $this->Order->init($this->Customer->getToken()['division'], $this->Customer->hasCreditCard());
        $this->loadModel(self::MODEL_NAME_CARD);
        $this->loadModel(self::MODEL_NAME_DATETIME);
        $this->set('validErrors', []);

        // 配送先
        $this->set('address', $this->Address->get());
        $this->set('default_payment', $this->Customer->getDefaultCard());
    }

    /**
     * アクセス拒否
     */
    protected function isAccessDeny()
    {
        if (!$this->Customer->canOrderKit() && $this->action === 'complete') {
            return true;
        }
        return false;
    }

    /**
     *
     */
    public function add()
    {
        // スニーカー判定
        if ($this->Customer->getInfo()['oem_cd'] === OEM_CD_LIST['sneakers']) {
            CakeSession::write('order_sneaker', true);
            return $this->redirect('order/input_sneaker');
        }

        CakeSession::write('order_sneaker', false);
        return $this->setAction('input');
    }

    /**
     *
     */
    public function input()
    {

        // エントリーユーザの場合初回購入動線へ移動
        if ($this->Customer->isEntry()) {
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // スニーカーセッションを制御
        if ($this->Customer->getInfo()['oem_cd'] === OEM_CD_LIST['sneakers']) {
            // スニーカー
            CakeSession::write('order_sneaker', true);

            // スニーカー以外のオーダ情報を削除
            $order_sneaker = CakeSession::read('Order.sneaker');
            CakeSession::delete('Order');
            CakeSession::write('Order.sneaker', $order_sneaker);

            return $this->redirect('order/input_sneaker');

        } else {
            // スニーカーではない
            CakeSession::write('order_sneaker', false);

            // 通所購入以外のオーダ情報を削除
            $order_direct_inbound = CakeSession::read('Order.direct_inbound');
            CakeSession::delete('Order');
            CakeSession::write('Order.direct_inbound', $order_direct_inbound);
        }

        // セッションリセット
        //CakeSession::delete('OrderKit');
        if (empty(CakeSession::read('OrderKit.cargo'))) {

            $OrderKit = array(
                'address_list' => array(),
                'address_id' => "",
                'is_input_address' => false,
                'is_credit' => false,
                'card_data' => array(),
                'cargo'       => "ヤマト運輸",
            );

            CakeSession::write('OrderKit', $OrderKit);
        }

        if(is_null((CakeSession::read('SelectTime')))){

            $SelectTime = array(
                    'day_cd'         => "",
                    'time_cd'        => "",
                    'select_delivery_day'   => "",
                    'select_delivery_time'  => "",
                    'select_delivery_text'  => "",
                    'select_delivery_day_list'      => array(),
                    'select_delivery_time_list'     => array(),
            );

            CakeSession::write('SelectTime', $SelectTime);

        }

        // 初期化チェック
        if(CakeSession::read('Address.lastname_kana') !== '　') {
            CakeSession::write('Address.select_delivery_list', array());
        }

        // セッション情報取得
        $OrderKit = CakeSession::read('OrderKit');

        // 住所一覧を取得
        $address_list = $this->Address->get();

        // ヘルパー読み込めないためヘルパーでの処理を転記
        $set_address_list = array();
        if (is_array($address_list)) {
            foreach ($address_list as $address) {
                $set_address_list[$address['address_id']] = h("〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}");
            }
        }

        $OrderKit['address_list'] = $set_address_list;

        // カード判定
        $OrderKit['is_credit'] = false;

        // クレジットカードかどうか
        // 法人口座未登録用遷移はbeforeFilterで判定済み
        if ($this->Customer->isPrivateCustomer()) {
            // 個人
            $OrderKit['is_credit'] = true;

            // カード情報取得
            $OrderKit['card_data'] = $this->Customer->getDefaultCard();
        } else {
            // 法人 法人カードの場合 account_situationは空白
            if (empty($this->Customer->getInfo()['account_situation'])) {
                $OrderKit['is_credit'] = true;
                // カード情報取得
                $OrderKit['card_data'] = $this->Customer->getDefaultCard();
            }
        }

        // セッション情報格納
        CakeSession::write('OrderKit', $OrderKit);

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

    }

    /**
     *
     */
    public function confirm_input()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['DirectInbound/input', 'DirectInbound/confirm', 'DirectInbound/complete'],
                true) === false
        ) {
            //* NG redirect
            $this->redirect(['controller' => 'direct_inbound', 'action' => 'input']);
        }

        $set_order_params = array();
        $set_order_params = $this->_setDirectInbound($set_order_params);
        $order_params = $set_order_params['direct_inbound'];

        // FirstOrderと階層を合わせる
        CakeSession::write('Order', $set_order_params);


        // 逐次バリデーションをかける 最後にまとめてバリデーションエラーでリターン
        // バリデーションをかけない値もセッションには保存する。
        $is_validation_error = false;

        // 逐次バリデーション
        $validation = AppValid::validate($order_params);

        //* 共通バリデーションでエラーあったらメッセージセット
        if (!empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $is_validation_error = true;
        }

        // 預け入れ方法保存
        // 既存のアドレス選択処理は OrderKitに含める
        $cargo = filter_input(INPUT_POST, 'cargo');

        // 逐次セッションに保存
        CakeSession::write('OrderKit.cargo', $cargo);


        if (CakeSession::read('OrderKit.cargo') !== "着払い") {
            /* 住所処理 */
            // 住所の入力情報
            // お届け先追加か判定
            $input_address_params = [
                'date_cd' => filter_input(INPUT_POST, 'date_cd'),
                'time_cd' => filter_input(INPUT_POST, 'time_cd'),
                'select_delivery_day' => filter_input(INPUT_POST, 'select_delivery_day'),
                'select_delivery_time' => filter_input(INPUT_POST, 'select_delivery_time'),
            ];

            //* Session write select_delivery_text
            $input_address_params['select_delivery_day_list'] = json_decode($input_address_params['select_delivery_day']);

            $input_address_params['select_delivery_text'] = "";
            if(!empty($input_address_params['select_delivery_day_list'])) {
                foreach ($input_address_params['select_delivery_day_list'] as $key => $value) {
                    if ($value->date_cd === $input_address_params['date_cd']) {
                        $input_address_params['select_delivery_text'] = $value->text;
                    }
                }
            }

            $input_address_params['select_delivery_time_list'] = json_decode($input_address_params['select_delivery_time']);

            if(!empty($input_address_params['select_delivery_time_list'])) {
                foreach ($input_address_params['select_delivery_time_list'] as $key => $value) {
                    if ($value->time_cd === $input_address_params['time_cd']) {
                        $input_address_params['select_delivery_text'] .= ' ' . $value->text;
                    }
                }
            }

            CakeSession::write('SelectTime', $input_address_params);

            // お届け日のラベル
            // 逐次セッションに保存
            if (array_key_exists('select_delivery_text', $input_address_params)) {
                CakeSession::write('OrderKit.select_delivery_text', $input_address_params['select_delivery_text']);
            }

            // 逐次バリデーション
            $validation = AppValid::validate($input_address_params);

            //* 共通バリデーションでエラーあったらメッセージセット
            if (!empty($validation)) {
                foreach ($validation as $key => $message) {
                    $this->Flash->validation($message, ['key' => $key]);
                }
                $is_validation_error = true;
            }
        }

        // 既存のアドレス選択処理は OrderKitに含める
        $address_id = filter_input(INPUT_POST, 'address_id');

        // 逐次セッションに保存
        CakeSession::write('OrderKit.address_id', $address_id);

        // アドレスリスト使用の場合
        // 住所指定されている場合

        // アドレスIDチェック
        $vail_address_params['address_id'] = $address_id;

        CakeSession::delete('DispAddress');
        if (CakeSession::read('OrderKit.cargo') !== "着払い") {

            if (!empty($address_id)) {
                // 購入時用住所パラメータチェック POST値ではないため、選択した住所情報をチェックし共通のエラーを返す
                // カード決済、口座振替でパラメータが異なる
                // 住所idから住所取得
                $set_address = $this->Address->find($address_id);

                // 住所保管
                CakeSession::write('Address', $set_address);

                // 表示用住所データ
                CakeSession::write('DispAddress', $set_address);

                // アドレスリストバリデーション
                // 決済によって必要情報が異なる
                if (CakeSession::read('OrderKit.is_credit')) {
                    // カード決済
                    $vail_address_params['name'] = $set_address['lastname'] . $set_address['firstname'];
                    $vail_address_params['postal'] = $set_address['postal'];
                    $vail_address_params['address'] = $set_address['pref'] . $set_address['address1'] . $set_address['address2'] . $set_address['address3'];
                    $vail_address_params['tel1'] = $set_address['tel1'];
                } else {
                    // 口座決済
                    $vail_address_params = $set_address;
                }
            }

            //*  validation 基本は共通クラスのAppValidで行う
            $validation = AppValid::validate($vail_address_params);
            //* 共通バリデーションでエラーあったらメッセージセット
            if ( !empty($validation)) {
                // アドレスリストの内容がエラーかチェックする。
                $address_list_error = false;
                foreach ($validation as $key => $message) {
                    $this->Flash->validation($message, ['key' => $key]);
                    // お届け先日時選択エラーの場合はお届け先形式エラーにしない。
                    if ($key !== 'date_cd') {
                        $address_list_error = true;
                    }
                    if ($key !== 'time_cd') {
                        $address_list_error = true;
                    }
                }
                // アドレスリストの内容がエラーだった場合
                if($address_list_error) {
                    $this->Flash->validation('お届け先の形式が正しくありません。会員情報またはお届け先変更にてご確認ください。'
                        , ['key' => 'format_address']);
                }
                $is_validation_error = true;
            }
        }

        if ($is_validation_error === true) {
            $this->redirect('input');
            return;
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        if (is_null(CakeSession::read('OrderKit.card_data'))){
            // カード入力画面
            $this->redirect('input_credit');
        }

        $this->redirect('confirm');

    }

    public function input_credit()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['DirectInbound/confirm_input', 'DirectInbound/input_credit', 'DirectInbound/confirm_credit'],
                true) === false
        ) {
            //* NG redirect
            $this->redirect(['controller' => 'direct_inbound', 'action' => 'input']);
        }

        if (is_null(CakeSession::read('Credit'))) {
            $Credit = array(
                'card_no'       => "",
                'security_cd'   => "",
                'expire'        => "",
                'holder_name'   => "",
            );
            CakeSession::write('Credit', $Credit);
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

    }

    public function confirm_credit()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['DirectInbound/input_credit', 'DirectInbound/confirm_credit', 'DirectInbound/complete_credit'],
                true) === false
        ) {
            //* NG redirect
            $this->redirect(['controller' => 'direct_inbound', 'action' => 'input']);
        }

        $input_card_params = array(
            'card_no'           => filter_input(INPUT_POST, 'card_no'),
            'security_cd'       => filter_input(INPUT_POST, 'security_cd'),
            'expire_month'      => filter_input(INPUT_POST, 'expire_month'),
            'expire_year'       => filter_input(INPUT_POST, 'expire_year'),
            'expire'            => filter_input(INPUT_POST, 'expire_month') . filter_input(INPUT_POST, 'expire_year'),
            'holder_name'       => strtoupper(filter_input(INPUT_POST, 'holder_name')),
        );

        // カード情報をセッションに保存
        CakeSession::write('Credit', $input_card_params);

        $input_card_params['card_no'] = self::_wrapConvertKana($input_card_params['card_no']);
        $input_card_params['security_cd'] = mb_convert_kana($input_card_params['security_cd'], 'nhk', "utf-8");;

        // 逐次バリデーション
        $validation = AppValid::validate($input_card_params);
        //* 共通バリデーションでエラーあったらメッセージセット
        if (!empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }

            // カードチェックはバリデーションOKの場合
            $this->redirect('input_credit');
        }

        $this->loadModel('CardCheck');
        $res = $this->CardCheck->getCardCheck($input_card_params);

        if (!empty($res->error_message)) {
            $this->Flash->validation($res->error_message, ['key' => 'card_no']);
            // 共通バリデーションがないのでここでリターン
            $this->redirect('input_credit');
        }

        // 伏せ文字カード番号保時
        CakeSession::write('Credit.disp_card_no', $res->results['card_no']);

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

    }
    /**
     *
     */
    public function complete_credit()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['DirectInbound/confirm_credit', 'DirectInbound/complete_credit', 'DirectInbound/confirm'],
                true) === false
        ) {
            //* NG redirect
            $this->redirect(['controller' => 'direct_inbound', 'action' => 'input']);
        }


        // カード未登録の場合
        if (is_null(CakeSession::read('OrderKit.card_data'))) {

            // カード変更追加モデル
            $this->loadModel('PaymentGMOSecurityCard');

            $Credit = CakeSession::read('Credit');

            $Credit['card_no'] = self::_wrapConvertKana($Credit['card_no']);
            $Credit['security_cd'] = mb_convert_kana($Credit['security_cd'], 'nhk', "utf-8");;
            $Credit['card_seq'] = '0';

            // カード構造を更新する
            CakeSession::write('Credit', $Credit);

            $credit_data['PaymentGMOSecurityCard'] = $Credit;

            $this->PaymentGMOSecurityCard->set($credit_data);

            // Expire
            $this->PaymentGMOSecurityCard->setExpire($credit_data);

            // ハイフン削除
            $this->PaymentGMOSecurityCard->trimHyphenCardNo($credit_data);

            // validates
            // card_seq 除外
            $this->PaymentGMOSecurityCard->validator()->remove('card_seq');
            $this->PaymentGMOSecurityCard->validator()->remove('disp_card_no');

            if (!$this->PaymentGMOSecurityCard->validates()) {
                foreach ($this->PaymentGMOSecurityCard->validationErrors as $key => $message) {
                    $this->Flash->validation($message[0], ['key' => 'customer_card_info']);
                }
                CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' PaymentGMOSecurityCard->validationErrors ' . print_r($this->PaymentGMOSecurityCard->validationErrors, true));

                return $this->redirect('input_credit');
            }

            // カード登録処理
            $result_security_card = $this->PaymentGMOSecurityCard->apiPost($this->PaymentGMOSecurityCard->toArray());
            if (!empty($result_security_card->error_message)) {
                $this->Flash->validation($result_security_card->error_message, ['key' => 'customer_kit_card_info']);
                CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' $result_security_card->error_message ' . print_r($result_security_card->error_message, true));

                return $this->redirect('input_credit');
            }
        }


        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->redirect('confirm');
    }

    public function confirm()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['DirectInbound/input', 'DirectInbound/confirm_input', 'DirectInbound/complete_credit', 'DirectInbound/confirm', 'DirectInbound/complete'],
                true) === false
        ) {
            //* NG redirect
            $this->redirect(['controller' => 'direct_inbound', 'action' => 'input']);
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

    }
    /**
     *
     */
    public function complete()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['DirectInbound/confirm', 'DirectInbound/complete'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'direct_inbound', 'action' => 'input']);
        }

        // チェックボックス
        $params = [
            'check_weight'              => filter_input(INPUT_POST, 'check_weight'),
            'check_hazardous_material'  => filter_input(INPUT_POST, 'check_hazardous_material'),
            'remember'                  => filter_input(INPUT_POST, 'remember'),
        ];

        $is_validation_error = false;

        //* 共通バリデーションでエラーあったらメッセージセット
        $validation = AppValid::validate($params);
        if (!empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $is_validation_error = true;
        }

        $validation = AppValid::validateTermsAgree($params['remember']);
        //* 共通バリデーションでエラーあったらメッセージセット
        if (!empty($validation) ) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $is_validation_error = true;
        }

        if ($is_validation_error === true) {
            $this->redirect('confirm');
            return;
        }

        // セッションが古い場合があるので再チェック
        // 発送日一覧のエラーチェック
        // 着払いでない場合
        if (CakeSession::read('OrderKit.cargo') !== "着払い") {

            $check_address_datetime_cd = false;
            $date_cd = CakeSession::read('SelectTime.date_cd');

            // 日付リストの確認
            $date_list = $this->_getInboundDate();
            CakeLog::write(DEBUG_LOG,
                $this->name . '::' . $this->action . ' date_list ' . print_r($date_list, true));

            foreach ($date_list as $key => $value) {
                if ($value['date_cd'] === $date_cd) {
                    $check_address_datetime_cd = true;
                }
            }

            if (!$check_address_datetime_cd) {
                $this->Flash->validation('集荷希望日をご確認ください。',
                    ['key' => 'date_cd']);
                CakeSession::write('SelectTime.date_cd', '');

                CakeLog::write(DEBUG_LOG,
                    $this->name . '::' . $this->action . ' check_address_datetime_cd error');
                return $this->redirect('input');
            }

            $time_cd = CakeSession::read('SelectTime.time_cd');

            // 時間リストの確認
            $time_list = $this->_getInboundTime();
            foreach ($time_list as $key => $value) {
                if ($value['time_cd'] === $time_cd) {
                    $check_address_datetime_cd = true;
                }
            }

            if (!$check_address_datetime_cd) {
                $this->Flash->validation('集荷希望時間をご確認ください。',
                    ['key' => 'time_cd']);
                CakeSession::write('SelectTime.time_cd', '');

                CakeLog::write(DEBUG_LOG,
                    $this->name . '::' . $this->action . ' check_address_datetime_cd error');
                return $this->redirect('input');
            }
        }

        // ボックス情報の生成
        $box = "";
        for($i = 0;$i < CakeSession::read('Order.direct_inbound.direct_inbound'); $i++) {
            $number = $i + 1;
            if(empty($box)) {
                $box .= PRODUCT_CD_DIRECT_INBOUND.':'.'minikuraダイレクト' . ':';
            } else {
                $box .= ',' . PRODUCT_CD_DIRECT_INBOUND.':'.'minikuraダイレクト' . ':';
            }
        }

        // 入庫

        $this->InboundDirect = new InboundDirect();

        $inbound_direct = array();
        $inbound_direct['box']          = $box;

        if (CakeSession::read('OrderKit.cargo') !== "着払い") {
            // 集荷
            $inbound_direct['direct_type'] = "0";
            $inbound_direct['lastname'] = CakeSession::read('Address.lastname');
            $inbound_direct['firstname'] = CakeSession::read('Address.firstname');
            $inbound_direct['tel1'] = self::_wrapConvertKana(CakeSession::read('Address.tel1'));
            $inbound_direct['postal'] = CakeSession::read('Address.postal');
            $inbound_direct['pref'] = CakeSession::read('Address.pref');
            $inbound_direct['address1'] = CakeSession::read('Address.address1');
            $inbound_direct['address2'] = CakeSession::read('Address.address2');
            $inbound_direct['address3'] = CakeSession::read('Address.address3');
            $inbound_direct['day_cd'] = CakeSession::read('SelectTime.date_cd');
            $inbound_direct['time_cd'] = CakeSession::read('SelectTime.time_cd');
        } else {
            // 着払い
            $inbound_direct['direct_type']          = "1";
        }

        $res = $this->InboundDirect->postInboundDirect($inbound_direct);
        if (!empty($res->message)) {
            $this->Flash->validation('直接入庫処理エラー', ['key' => 'inbound_direct']);
            return $this->redirect('confirm');
        }

        // 完了したページ情報を保存
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->_cleanKitOrderSession();
    }

    /**
     * 注文不可ユーザ用表示メソッド
     */
    public function cannot()
    {
    }

    /**
     * ヤマト運輸の配送日にち情報取得
     */
    private function _getInboundDate()
    {
        $result = array();

        $DatePickupModel = new DatePickup();

        $result = $DatePickupModel->apiGetResults();

        CakeLog::write(DEBUG_LOG,
            $this->name . '::' . $this->action . ' result ' . print_r($result, true));


        return $result;
    }

    /**
     * ヤマト運輸の配送時間情報取得
     */
    private function _getInboundTime()
    {
        $result = array();

        $TimePickupModel = new TimePickup();

        $result = $TimePickupModel->apiGetResults();

        CakeLog::write(DEBUG_LOG,
            $this->name . '::' . $this->action . ' result ' . print_r($result, true));

        return $result;
    }

    /**
     * Direct Inbound set
     */
    private function _setDirectInbound($Order)
    {
        $Order['direct_inbound']['direct_inbound'] = (int)filter_input(INPUT_POST, 'direct_inbound');
        return $Order;
    }

    /**
     * first orderで使用しているセッション類を削除
     */
    private function _cleanKitOrderSession()
    {
        CakeSession::delete('Order');
        CakeSession::delete('OrderTotal');
        CakeSession::delete('OrderKit');
        CakeSession::delete('OrderList');
        CakeSession::delete('OrderTotalList');
        CakeSession::delete('SelectTime');
        CakeSession::delete('Address');
        CakeSession::delete('Credit');
        CakeSession::delete('order_sneaker');
    }

}
