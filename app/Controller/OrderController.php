<?php

App::uses('MinikuraController', 'Controller');
App::uses('CustomerKitPrice', 'Model');

class OrderController extends MinikuraController
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
            return $this->setAction('input_sneaker');
        }

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

        // 住所追加後リターン処理
        if (!empty(CakeSession::read('OrderKit.address_id'))) {

            $address_id = CakeSession::read('OrderKit.address_id');
            // 前回追加選択は最後のお届け先を選択
            if ($address_id === AddressComponent::CREATE_NEW_ADDRESS_ID) {

                $last_address = $this->Address->last();
                // CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'. ' last_address ' . print_r($last_address, true));

                $OrderKit = CakeSession::read('OrderKit');

                $OrderKit['address_id'] = $last_address['address_id'];
                $OrderKit['datetime_cd'] = '';

                $datetime_delivery_kit = $this->getDatetimeDeliveryKit($last_address['address_id']);
                $select_delivery = json_encode($datetime_delivery_kit);
                $select_delivery_list = json_decode($select_delivery);

                $OrderKit['select_delivery'] = $select_delivery;
                $OrderKit['select_delivery_list'] = $select_delivery_list;

                CakeSession::write('OrderKit.', $OrderKit);

            }
        }

        // CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'. ' OrderKit ' . print_r(CakeSession::read('OrderKit'), true));

        // セッションリセット
        if (empty(CakeSession::read('OrderKit.address_id'))) {

            $OrderKit = array(
                'address_list' => array(),
                'address_id' => "",
                'address' => array(),
                'is_add_address' => false,
                'select_delivery' => "",
                'select_delivery_text' => "",
                'select_delivery_list' => array(),
                'card_data' => array(),
                'card_no' => "",
                'security_cd' => "",
                'is_credit' => "",
                'kit_params' => array(),
            );

            CakeSession::write('OrderKit', $OrderKit);
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

            CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'. ' getInfo ' . print_r($this->Customer->getInfo(), true));
        } else {
            // 法人 法人カードの場合 account_situationは空白
            if (empty($this->Customer->getInfo()['account_situation'])) {
                $OrderKit['is_credit'] = true;
                // カード情報取得
                $OrderKit['card_data'] = $this->Customer->getDefaultCard();
            }
        }

        CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'. ' getInfo ' . print_r($this->Customer->getInfo(), true));

        // セッション情報格納
        CakeSession::write('OrderKit', $OrderKit);

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

    }

    /**
     *
     */
    public function confirm()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['Order/input', 'Order/confirm', 'Order/complete'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'order', 'action' => 'input']);
        }

        // お届け先追加か判定
        $address_id = filter_input(INPUT_POST, 'address_id');
        if ($address_id === AddressComponent::CREATE_NEW_ADDRESS_ID) {
            CakeSession::write('OrderKit.address_id', $address_id);

            return $this->redirect([
                'controller' => 'address', 'action' => 'add', 'customer' => true,
                '?' => ['return' => 'order']
            ]);
        }

        // order情報取得
        $Order = CakeSession::read('Order');
        $OrderTotal = CakeSession::read('OrderTotal');

        // 箱情報の集計
        $Order = $this->_setMonoOrder($Order);
        $OrderTotal['mono_num'] = array_sum($Order['mono']);
        $Order = $this->_setHakoOrder($Order);
        $OrderTotal['hako_num'] = array_sum($Order['hako']);
        $Order = $this->_setCleaningOrder($Order);

        // 箱選択されているか
        if (array_sum(array($OrderTotal['mono_num'], $OrderTotal['hako_num'], $Order['cleaning']['cleaning'])) === 0) {
            $params = array(
                'select_oreder_mono' => $OrderTotal['mono_num'],
                'select_oreder_hako' => $OrderTotal['hako_num'],
                'select_oreder_cleaning' => $Order['cleaning']['cleaning']
            );
        }

        //* Session write
        CakeSession::write('Order', $Order);
        CakeSession::write('OrderTotal', $OrderTotal);

        // CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'. 'select_delivery_list' . print_r($Order, true));

        // セッション内情報を取得
        $OrderKit = CakeSession::read('OrderKit');

        // カード利用の場合
        $is_register_credit = false;
        if (CakeSession::read('OrderKit.is_credit')) {
            // CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'. 'is_credit');
            // カード情報
            $OrderKit['security_cd'] = filter_input(INPUT_POST, 'security_cd');
            $params['security_cd'] = mb_convert_kana($OrderKit['security_cd'], 'nhk', "utf-8");
        }

        // お届け先情報等
        $params['address_id'] = $address_id;
        $OrderKit['address_id'] = $address_id;

        $OrderKit['datetime_cd'] = filter_input(INPUT_POST, 'datetime_cd');
        $params['datetime_cd'] = $OrderKit['datetime_cd'];

        CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'. 'params' . print_r($params, true));

        // パラメータチェック不要な要素はparamsにいれない
        // 表示用時間一覧
        $OrderKit['select_delivery'] = filter_input(INPUT_POST, 'select_delivery');
        // CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'. 'select_delivery' . print_r($select_delivery, true));

        $select_delivery_list = json_decode($OrderKit['select_delivery']);
        $OrderKit['select_delivery_list'] = $select_delivery_list;

        // CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'. 'select_delivery_list' . print_r($OrderKit['select_delivery_list'], true));

        if (is_array($select_delivery_list)) {
            foreach ($select_delivery_list as $key => $value) {
                if ($value->datetime_cd === $params['datetime_cd']) {
                    $OrderKit['select_delivery_text'] = $value->text;
                }
            }
        }

        CakeSession::write('OrderKit', $OrderKit);

        //*  validation 基本は共通クラスのAppValidで行う
        $is_validation_error = false;
        $validation = AppValid::validate($params);
        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
                CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'.$message . 'key '. $key);
            }
            $is_validation_error = true;
        }

        // kitコード 表示kit名取得
        $kit_code = KIT_CODE_DISP_NAME_ARRAY;

        // 金額取得API
        $kitPrice = new CustomerKitPrice();

        // 金額集計
        $OrderList = array();

        // 購入時用コード格納
        $kit_params = array();
        // Order['MONO',[レギュラー=>0,アパレル=>0,ブック=>0]]
        foreach ($Order as $orders => $kit_order) {
            foreach ($kit_order as $key => $value) {

                // BOX選択されている場合
                if ($value != 0 ) {
                    if (array_key_exists ($key, $kit_code)) {
                        // $OrderList[$key]['price']     = number_format($kit_code[$key]['price'] * $value * 1);
                        $code = $kit_code[$key]['code'];
                        $OrderList[$code]['number']    = $value;
                        $OrderList[$code]['kit_name']  = $kit_code[$key]['name'];
                        $OrderList[$code]['price'] = 0;
                        $product = $code . ':' .$value;
                        $kit_params[] = $product;

                        $r = $kitPrice->apiGet([
                            'kit' => $product
                        ]);
                        if ($r->isSuccess()) {
                            $price = $r->results[0]['total_price'] * 1;
                            $OrderList[$code]['price'] = number_format($price);
                        }
                    }
                }
            }
        }

        CakeSession::write('OrderList', $OrderList);
        CakeSession::write('OrderKit.kit_params', $kit_params);

        // 住所指定されている場合
        if (!empty($params['address_id'])) {
            // 購入時用住所パラメータチェック POST値ではないため、選択した住所情報をチェックし共通のエラーを返す
            // カード決済、口座振替でパラメータが異なる
            // 住所idから住所取得
            $address = $this->Address->find($params['address_id']);

            // CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'. 'select_delivery_list' . print_r($OrderKit['select_delivery_list'], true));

            // 表示用に住所をセッションに保存
            CakeSession::write('Address', $address);

            // 決済によって必要情報が異なる
            $address_params = array();
            if (CakeSession::read('OrderKit.is_credit')) {
                // カード決済
                $address_params['name'] = $address['lastname'] . $address['firstname'];
                $address_params['postal'] = $address['postal'];
                $address_params['address'] = $address['pref'] . $address['address1'] . $address['address2'] . $address['address3'];
                $address_params['tel1'] = $address['tel1'];
            } else {
                // 口座決済
                $address_params = $address;
            }

            // 住所パラメータチェック 住所エラーの場合表示場所は１箇所
            $validation = AppValid::validate($address_params);
            if (!empty($validation)) {
                foreach ($validation as $key => $message) {
                    $this->Flash->validation($message, ['key' => $key]);
                    CakeLog::write(DEBUG_LOG, __METHOD__ . '(' . __LINE__ . ')' . $message . 'key ' . $key);
                }

                $this->Flash->validation('お届け先の形式が正しくありません。会員情報またはお届け先変更にてご確認ください。'
                    , ['key' => 'format_address']);
                $is_validation_error = true;
            }
        }

        // CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'. ' Session OrderKit ' . print_r(CakeSession::read('OrderKit'), true));

        if ($is_validation_error === true) {
            $this->_flowSwitch('input');
            return;
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
        if (in_array(CakeSession::read('app.data.session_referer'), ['Order/confirm', 'Order/complete'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'order', 'action' => 'input']);
        }

        // 決済方法によって実行するapiが異なる
        if (CakeSession::read('OrderKit.is_credit')) {
            // カード購入
            $this->loadModel('PaymentGMOKitCard');
            $gmo_kit_card = array();
            $gmo_kit_card['card_seq']      = 0;
            $gmo_kit_card['security_cd']   = self::_wrapConvertKana(CakeSession::read('OrderKit.security_cd'));
            $gmo_kit_card['address_id']    = CakeSession::read('OrderKit.address_id');
            $gmo_kit_card['datetime_cd']   = CakeSession::read('OrderKit.datetime_cd');
            $gmo_kit_card['name']          = CakeSession::read('Address.lastname') . '　' . CakeSession::read('Address.firstname');
            $gmo_kit_card['tel1']          = self::_wrapConvertKana(CakeSession::read('Address.tel1'));
            $gmo_kit_card['postal']        = CakeSession::read('Address.postal');
            $gmo_kit_card['address']       = CakeSession::read('Address.pref') . CakeSession::read('Address.address1') . CakeSession::read('Address.address2') . '　' .  CakeSession::read('Address.address3');

            $kit_params = CakeSession::read('OrderKit.kit_params');
            $gmo_kit_card['kit'] = implode(',', $kit_params);

            $this->PaymentGMOKitCard->set($gmo_kit_card);
            $result_kit_card = $this->PaymentGMOKitCard->apiPost($this->PaymentGMOKitCard->toArray());
            if ($result_kit_card->status !== '1') {
                if ($result_kit_card->http_code === 400) {
                    $this->Flash->validation('キット購入エラー', ['key' => 'customer_kit_card_info']);
                } else {
                    $this->Flash->validation($result_kit_card->message, ['key' => 'customer_kit_card_info']);
                }
                // 暫定
                return $this->_flowSwitch('input');
            }

        } else {
            // 口座振込
            $this->loadModel('PaymentAccountTransferKit');

            //
            $kit_payment_transfer = array();
            $kit_params = CakeSession::read('OrderKit.kit_params');
            $kit_payment_transfer['kit'] = implode(',', $kit_params);
            $kit_payment_transfer['lastname'] = CakeSession::read('Address.lastname');
            $kit_payment_transfer['firstname'] = CakeSession::read('Address.firstname');
            $kit_payment_transfer['tel1'] = CakeSession::read('Address.tel1');
            $kit_payment_transfer['postal'] = CakeSession::read('Address.postal');
            $kit_payment_transfer['pref'] = CakeSession::read('Address.pref');
            $kit_payment_transfer['address1'] = CakeSession::read('Address.address1');
            $kit_payment_transfer['address2'] = CakeSession::read('Address.address2');
            $kit_payment_transfer['address3'] = CakeSession::read('Address.address3');
            $kit_payment_transfer['datetime_cd'] = CakeSession::read('OrderKit.datetime_cd');

            $this->PaymentAccountTransferKit->set($kit_payment_transfer);
            $result_kit_payment_transfer = $this->PaymentAccountTransferKit->apiPost($this->PaymentAccountTransferKit->toArray());
            if ($result_kit_payment_transfer->status !== '1') {
                if ($result_kit_payment_transfer->http_code === 400) {
                    $this->Flash->validation('キット購入エラー', ['key' => 'customer_kit_card_info']);
                } else {
                    $this->Flash->validation($result_kit_payment_transfer->message, ['key' => 'customer_kit_card_info']);
                }
                // 暫定
                return $this->_flowSwitch('input');
            }
        }

        // 完了したページ情報を保存
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->_cleanKitOrderSession();
    }

    public function input_sneaker()
    {
        // refererは基本の初回購入フローを使用する
        $this->setAction('input');

        return $this->render('input_sneaker');
    }

    public function confirm_sneaker()
    {

        // refererは基本の初回購入フローを使用する
        $this->setAction('confirm');

        return $this->render('confirm_sneaker');
    }

    /**
     *
     */
    public function complete_sneaker()
    {
        // refererは基本の初回購入フローを使用する
        $this->setAction('complete');

        // スニーカーセッション情報を削除
        CakeSession::delete('order_sneaker');
        return $this->render('complete_sneaker');
    }

    /**
     * 注文不可ユーザ用表示メソッド
     */
    public function cannot()
    {
    }

    /**
     *
     */
    public function getAddressDatetime()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        $this->autoRender = false;

        $address_id = $this->request->data['address_id'];
        $result = $this->getDatetimeDeliveryKit($address_id);
        $status = !empty($result);

        return json_encode(compact('status', 'result'));
    }

    private function getDatetimeDeliveryKit($address_id)
    {
        $address = $this->Address->find($address_id);
        if (!empty($address) && !empty($address['postal'])) {
            $res = $this->DatetimeDeliveryKit->apiGet([
                'postal' => $address['postal'],
            ]);
            if ($res->isSuccess()) {
                return $res->results;
            }
        }
        return [];
    }

    private function getDatetime($address_id, $datetime_cd)
    {
        $data = [];
        $result = $this->getDatetimeDeliveryKit($address_id);
        foreach ($result as $datetime) {
            if ($datetime['datetime_cd'] === $datetime_cd) {
                $data = $datetime;
            }
        }
        return $data;
    }

    /**
     * kit box mono 箱数をset
     */
    private function _setMonoOrder($Order)
    {
        $params = array(
            'mono'          => (int)filter_input(INPUT_POST, 'mono'),
            'mono_apparel'  => (int)filter_input(INPUT_POST, 'mono_apparel'),
            'mono_book'     => (int)filter_input(INPUT_POST, 'mono_book'),
        );
        $Order['mono'] = $params;
        return $Order;
    }

    /**
     * kit box hako 箱数をset
     */
    private function _setHakoOrder($Order)
    {
        $params = array(
            'hako'          => (int)filter_input(INPUT_POST, 'hako'),
            'hako_apparel'  => (int)filter_input(INPUT_POST, 'hako_apparel'),
            'hako_book'     => (int)filter_input(INPUT_POST, 'hako_book'),
        );
        $Order['hako'] = $params;
        return $Order;
    }

    /**
     * kit box cleaning 箱数をset
     */
    private function _setCleaningOrder($Order)
    {
        $Order['cleaning']['cleaning'] = (int)filter_input(INPUT_POST, 'cleaning');
        return $Order;
    }

    /**
     * kit box sneaker set
     */
    private function _setSneakerOrder($Order)
    {
        $Order['sneaker']['sneaker'] = (int)filter_input(INPUT_POST, 'sneaker');
        return $Order;
    }

    /**
     * フローを変更スイッチ
     * 遷移先メッソドを指定し、スニーカの場合_sneakerメソッドへ遷移させる
     */
    private function _flowSwitch($base_method)
    {
        $set_method = $base_method;

        // スニーカー判定
        if ($this->Customer->getInfo()['oem_cd'] === OEM_CD_LIST['sneakers']) {
            $set_method = $base_method . '_sneaker';
        }

        $this->redirect(['controller' => 'order', 'action' => $set_method]);

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
        CakeSession::delete('Address');
    }

}
