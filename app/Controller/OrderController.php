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
            CakeSession::write('order_sneaker', true);
            return $this->setAction('input_sneaker');
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
        } else {
            // スニーカーではない
            CakeSession::write('order_sneaker', false);

            // 通所購入以外のオーダ情報を削除
            $order_hako = CakeSession::read('Order.hako');
            $order_mono = CakeSession::read('Order.mono');
            $order_cleaning = CakeSession::read('Order.cleaning');
            CakeSession::delete('Order');
            CakeSession::write('Order.hako', $order_hako);
            CakeSession::write('Order.mono', $order_mono);
            CakeSession::write('Order.cleaning', $order_cleaning);
        }

        // セッションリセット
        //CakeSession::delete('OrderKit');
        if (empty(CakeSession::read('OrderKit.address_list'))) {

            $OrderKit = array(
                'address_list' => array(),
                'address_id' => "",
                'is_input_address' => false,
                'insert_address_list' =>  true,
                'card_data' => array(),
                'select_card' => "default",
                'is_credit' => false,
                'kit_params' => array(),
            );

            CakeSession::write('OrderKit', $OrderKit);
        }

        // 初期化チェック
        if(CakeSession::read('Address.lastname_kana') !== '　') {
            // アドレスカナを全角スペースで初期化
            CakeSession::write('Address.lastname_kana', '　');
            CakeSession::write('Address.firstname_kana', '　');
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
        $set_address_list[AddressComponent::CREATE_NEW_ADDRESS_ID] = 'お届先を入力する';

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
    public function confirm()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['Order/input', 'Order/confirm', 'Order/complete'],
                true) === false
        ) {
            //* NG redirect
            $this->redirect(['controller' => 'order', 'action' => 'input']);
        }

        // order情報取得
        $Order = CakeSession::read('Order');
        $OrderTotal = CakeSession::read('OrderTotal');

        // 箱選択されているか
        $vali_oreder_params = array();

        switch (true) {
            case CakeSession::read('order_sneaker') === true:
                $Order = $this->_setSneakerOrder($Order);
                $vali_oreder_params = array(
                    'select_oreder_sneaker' => $Order['sneaker']['sneaker']
                );

                break;
            default:
                // 箱情報の集計
                $Order = $this->_setMonoOrder($Order);
                $OrderTotal['mono_num'] = array_sum($Order['mono']);
                $Order = $this->_setHakoOrder($Order);
                $OrderTotal['hako_num'] = array_sum($Order['hako']);
                $Order = $this->_setCleaningOrder($Order);

                if (array_sum(array($OrderTotal['mono_num'], $OrderTotal['hako_num'], $Order['cleaning']['cleaning'])) === 0) {
                    $vali_oreder_params = array(
                        'select_oreder_mono' => $OrderTotal['mono_num'],
                        'select_oreder_hako' => $OrderTotal['hako_num'],
                        'select_oreder_cleaning' => $Order['cleaning']['cleaning']
                    );
                }
                break;
        }


        //* Session write
        CakeSession::write('Order', $Order);
        CakeSession::write('OrderTotal', $OrderTotal);

        // 逐次バリデーションをかける 最後にまとめてバリデーションエラーでリターン
        // バリデーションをかけない値もセッションには保存する。
        $is_validation_error = false;
        
        // 逐次バリデーション
        $validation = AppValid::validate($vali_oreder_params);

        //* 共通バリデーションでエラーあったらメッセージセット
        if (!empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $is_validation_error = true;
        }

        /* カード処理 */
        // カード利用の場合
        // カード利用しない場合はセッション保存も不要
        if (CakeSession::read('OrderKit.is_credit')) {
            // カードの入力情報
            $input_card_params = array(
                'card_no' => filter_input(INPUT_POST, 'card_no'),
                'security_cd' => filter_input(INPUT_POST, 'security_cd'),
                'new_security_cd' => filter_input(INPUT_POST, 'new_security_cd'),
                'expire_month' => filter_input(INPUT_POST, 'expire_month'),
                'expire_year' => filter_input(INPUT_POST, 'expire_year'),
                'expire' => filter_input(INPUT_POST, 'expire_month') . filter_input(INPUT_POST, 'expire_year'),
                'holder_name' => strtoupper(filter_input(INPUT_POST, 'holder_name')),
            );

            // カード情報をセッションに保存
            CakeSession::write('Credit', $input_card_params);

            // 登録カードの変更の有無
            $select_card = filter_input(INPUT_POST, 'select-card');

            $vali_card_params= array();

            // 登録カードの変更の有無
            if ($select_card === 'default') {
                // 登録カード変更なし
                $vali_card_params['security_cd'] = $input_card_params['security_cd'];
            }

            // 登録カードの変更の有無
            $is_card_insert = false;

            // カード変更
            if($select_card === 'register') {
                $is_card_insert = true;
            }

            // カード追加
            if (is_null(CakeSession::read('OrderKit.card_data'))) {
                $is_card_insert = true;
            }

            if($is_card_insert) {
                // new_security_cdをsecurity_cdにいれバリデーションをかける
                // new_security_cdとsecurity_cdが２つバリデーションにかけることはない
                $input_card_params['security_cd'] = $input_card_params['new_security_cd'];
                $vali_card_params = $input_card_params;

                // カードno バリデーション前処理
                $vali_card_params['card_no'] = self::_wrapConvertKana($vali_card_params['card_no']);
            }

            // 逐次セッション保存
            CakeSession::write('OrderKit.select_card', $select_card);

            // ハイフン削除はバリデーション前に実施
            $vali_card_params['security_cd'] = mb_convert_kana($vali_card_params['security_cd'], 'nhk', "utf-8");;

            // 逐次バリデーション
            $validation = AppValid::validate($vali_card_params);
            //* 共通バリデーションでエラーあったらメッセージセット
            if (!empty($validation)) {
                foreach ($validation as $key => $message) {
                    // カード変更の場合 バリデーションエラーキーを再設定
                    if ($select_card === 'register') {
                        switch (true) {
                            case $key === 'card_no':
                                $this->Flash->validation($message, ['key' => 'new_card_no']);
                                break;
                            case $key === 'security_cd':
                                $this->Flash->validation($message, ['key' => 'new_security_cd']);
                                break;
                            default:
                                $this->Flash->validation($message, ['key' => $key]);
                                break;
                        }
                    } else {
                        $this->Flash->validation($message, ['key' => $key]);
                    }
                }
                $is_validation_error = true;
            }

            // バリデーションエラーない場合
            if (!$is_validation_error) {
                // 登録カードの変更の有無
                if ($select_card === 'register') {
                    // 利用可能カードか確認
                    //* クレジットカードのチェック 未ログイン時にチェックできる v4/gmo_payment/card_check apiを使用する
                    $this->loadModel('CardCheck');
                    $res = $this->CardCheck->getCardCheck($vali_card_params);

                    if (!empty($res->error_message)) {
                        $this->Flash->validation($res->error_message, ['key' => 'card_no']);
                        $is_validation_error = true;
                    }
                }
            }

        }

        /* 住所処理 */
        // 住所の入力情報
        // お届け先追加か判定
        $input_address_params = [
            'firstname'         => filter_input(INPUT_POST, 'firstname'),
            'firstname_kana'    => filter_input(INPUT_POST, 'firstname_kana'),
            'lastname'          => filter_input(INPUT_POST, 'lastname'),
            'lastname_kana'     => filter_input(INPUT_POST, 'lastname_kana'),
            'tel1'              => filter_input(INPUT_POST, 'tel1'),
            'postal'            => filter_input(INPUT_POST, 'postal'),
            'pref'              => filter_input(INPUT_POST, 'pref'),
            'address1'          => filter_input(INPUT_POST, 'address1'),
            'address2'          => filter_input(INPUT_POST, 'address2'),
            'address3'          => filter_input(INPUT_POST, 'address3'),
            'datetime_cd'       => filter_input(INPUT_POST, 'datetime_cd'),
            'select_delivery'   => filter_input(INPUT_POST, 'select_delivery'),
            'insert_address_list'   => filter_input(INPUT_POST, 'insert-adress-list'),
        ];

        // お届け日をセッション保存用に変更
        $input_address_params['select_delivery_list'] = json_decode($input_address_params['select_delivery']);

        // お届け日のラベルを作成
        if(is_array($input_address_params['select_delivery_list'])) {
            foreach ($input_address_params['select_delivery_list'] as $key => $value) {
                if ($value->datetime_cd === $input_address_params['datetime_cd']) {
                    $input_address_params['select_delivery_text'] = $value->text;
                }
            }

            // お届け日のラベル
            // 逐次セッションに保存
            if (array_key_exists('select_delivery_text', $input_address_params)) {
                CakeSession::write('OrderKit.select_delivery_text', $input_address_params['select_delivery_text']);
            }
        }


        // 住所情報をセッションに保存
        CakeSession::write('Address', $input_address_params);
        CakeSession::write('DispAddress', $input_address_params);

        // 既存のアドレス選択処理は OrderKitに含める
        $address_id = filter_input(INPUT_POST, 'address_id');

        // 逐次セッションに保存
        CakeSession::write('OrderKit.address_id', $address_id);

        // 入力アドレス
        $is_input_address = false;
        if ($address_id === AddressComponent::CREATE_NEW_ADDRESS_ID ) {
            $is_input_address = true;
        }

        // 逐次セッションに保存
        CakeSession::write('OrderKit.is_input_address', $is_input_address);

        // アドレスリスト追加
        // 逐次セッションに保存
        $insert_address_list = true;
        if (empty($input_address_params['insert_address_list'])) {
            $insert_address_list = false;
        }
        CakeSession::write('OrderKit.insert_address_list', $insert_address_list);

        // お届けコードをキットセッションにも保存
        CakeSession::write('OrderKit.datetime_cd', $input_address_params['datetime_cd']);

        // アドレス入力
        $set_address = array();
        if ($is_input_address) {
            // アドレス追加の場合
            $set_address = $input_address_params;

            $set_address['tel1'] = self::_wrapConvertKana($set_address['tel1']);
            // 登録バリデーション
            $vail_address_params = $set_address;
        } else {
            // アドレスリスト使用の場合
            // 住所指定されている場合

            // アドレスIDチェック
            $vail_address_params['address_id'] = $address_id;
            $vail_address_params['datetime_cd'] = $input_address_params['datetime_cd'];

            if (!empty($address_id)) {
                // 購入時用住所パラメータチェック POST値ではないため、選択した住所情報をチェックし共通のエラーを返す
                // カード決済、口座振替でパラメータが異なる
                // 住所idから住所取得
                $set_address = $this->Address->find($address_id);

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

                    // 日付選択
                    $vail_address_params['datetime_cd'] = $input_address_params['datetime_cd'];
                }
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
                if ($key !== 'datetime_cd') {
                    $address_list_error = true;
                }
            }
            // アドレスリストの内容がエラーだった場合
            if (!$is_input_address) {
                if($address_list_error) {
                    $this->Flash->validation('お届け先の形式が正しくありません。会員情報またはお届け先変更にてご確認ください。'
                        , ['key' => 'format_address']);
                }
            }
            $is_validation_error = true;
        }

        // kitコード 表示kit名取得
        $kit_code = KIT_CODE_DISP_NAME_ARRAY;

        // 金額取得API
        $kitPrice = new CustomerKitPrice();

        // 金額集計
        $OrderList = array();
        $OrderTotalList = array();
        $OrderTotalList['number'] = 0;
        $OrderTotalList['price'] = 0;

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
                        $OrderTotalList['number'] += $value;
                        $product = $code . ':' .$value;
                        $kit_params[] = $product;

                        $r = $kitPrice->apiGet([
                            'kit' => $product
                        ]);
                        if ($r->isSuccess()) {
                            $price = $r->results[0]['total_price'] * 1;
                            $OrderList[$code]['price'] = number_format($price);
                            $OrderTotalList['price'] += $price;
                        }
                    }
                }
            }
        }

        CakeSession::write('OrderList', $OrderList);
        CakeSession::write('OrderTotalList', $OrderTotalList);
        CakeSession::write('OrderKit.kit_params', $kit_params);

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

        // カード決済の場合
        if (CakeSession::read('OrderKit.is_credit')) {

            // カード情報編集
            $is_card_insert = false;

            // カード変更
            if (CakeSession::read('OrderKit.select_card') === 'register') {
                $is_card_insert = true;
            }

            // カード追加
            if (is_null(CakeSession::read('OrderKit.card_data'))) {
                $is_card_insert = true;
            }

            if ($is_card_insert) {
                // カード変更追加モデル
                $this->loadModel('PaymentGMOSecurityCard');

                $Credit = CakeSession::read('Credit');

                $Credit['card_no'] = self::_wrapConvertKana($Credit['card_no']);
                $Credit['security_cd'] = $Credit['new_security_cd'];
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

                if (!$this->PaymentGMOSecurityCard->validates()) {
                    $this->Flash->validation($this->PaymentGMOSecurityCard->validationErrors,
                        ['key' => 'customer_card_info']);
                    return $this->_flowSwitch('input');
                }

                if (is_null(CakeSession::read('OrderKit.card_data'))) {
                    // カード更新
                    $result_security_card = $this->PaymentGMOSecurityCard->apiPost($this->PaymentGMOSecurityCard->toArray());
                } else {
                    // カード更新
                    $result_security_card = $this->PaymentGMOSecurityCard->apiPut($this->PaymentGMOSecurityCard->toArray());
                }
                if (!empty($result_security_card->error_message)) {
                    $this->Flash->validation($result_security_card->error_message, ['key' => 'customer_kit_card_info']);
                    return $this->_flowSwitch('input');
                }
            }
        }

        // 住所のセット
        $set_address = array();
        if(CakeSession::read('OrderKit.is_input_address')) {
            // 入力住所
            $set_address = CakeSession::read('Address');

            // 住所リスト追加
            if(CakeSession::read('OrderKit.insert_address_list')) {

                $set_address = CakeSession::read('Address');
                unset($set_address['select_delivery']);
                unset($set_address['select_delivery_list']);

                $set_address['tel1'] = self::_wrapConvertKana($set_address['tel1']);

                $this->loadModel('CustomerAddress');

                $this->CustomerAddress->set($set_address);
                if (!$this->CustomerAddress->validates()) {
                    foreach ($this->CustomerAddress->validationErrors as $key => $message) {
                        $this->Flash->validation($message[0], ['key' => $key]);
                    }

                    return $this->_flowSwitch('input');
                }

                CakeSession::write('CustomerAddress', $this->CustomerAddress->toArray());

                $ret = $this->CustomerAddress->apiPost($this->CustomerAddress->toArray());

                // 追加アドレスIDを取得
                $address_id = Hash::get($this->Address->last(), 'address_id', '');

                // 部分更新
                CakeSession::write('OrderKit.address_id', $address_id);
            } else {

                // リストに追加しない
                $set_address = CakeSession::read('Address');
                $set_address['tel1'] = self::_wrapConvertKana($set_address['tel1']);

            }

        } else {
            // リスト住所
            $address_id = CakeSession::read('OrderKit.address_id');

            $set_address = $this->Address->find($address_id);
        }

        // 決済方法によって実行するapiが異なる
        if (CakeSession::read('OrderKit.is_credit')) {
            // カード購入
            $this->loadModel('PaymentGMOKitCard');
            $gmo_kit_card = array();
            $gmo_kit_card['card_seq']      = 0;
            $gmo_kit_card['security_cd']   = self::_wrapConvertKana(CakeSession::read('Credit.security_cd'));
            $gmo_kit_card['address_id']    = CakeSession::read('OrderKit.address_id');
            $gmo_kit_card['datetime_cd']   = CakeSession::read('OrderKit.datetime_cd');
            $gmo_kit_card['name']          = $set_address['lastname'] . '　' . $set_address['firstname'];
            $gmo_kit_card['tel1']          = self::_wrapConvertKana($set_address['tel1']);
            $gmo_kit_card['postal']        = $set_address['postal'];
            $gmo_kit_card['address']       = $set_address['pref'] . $set_address['address1'] . $set_address['address2'] . '　' .  $set_address['address3'];

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
            $kit_payment_transfer['lastname'] = $set_address['lastname'];
            $kit_payment_transfer['firstname'] = $set_address['firstname'];
            $kit_payment_transfer['tel1'] = self::_wrapConvertKana($set_address['tel1']);
            $kit_payment_transfer['postal'] = $set_address['postal'];
            $kit_payment_transfer['pref'] = $set_address['pref'];
            $kit_payment_transfer['address1'] = $set_address['address1'];
            $kit_payment_transfer['address2'] = $set_address['address2'];
            $kit_payment_transfer['address3'] = $set_address['address3'];
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
     * ajax 指定IDの配送日時情報取得
     */
    public function as_get_address_datetime_by_postal()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        // 画面描画しない
        $this->autoRender = false;

        $postal = filter_input(INPUT_POST, 'postal');

        $result = $this->_getAddressDatetimeByPostal($postal);

        $status = !empty($result);

        // コードを表示用文字列に変換
        App::uses('AppHelper', 'View/Helper');
        $appHelper = new AppHelper(new View());

        $results = [];
        $i = 0;
        foreach ($result->results as $datetime) {
            $datetime_cd = $datetime['datetime_cd'];
            $results[$i]["datetime_cd"] = $datetime_cd;
            $results[$i]["text"] = $appHelper->convDatetimeCode($datetime_cd);
            $i++;
        }

        return json_encode(compact('status', 'results'));
    }

    /**
     * 指定郵便番号の配送日時情報取得
     */
    private function _getAddressDatetimeByPostal($postal)
    {
        // ハイフンチェック
        if (mb_strlen($postal) > 7) {
            // ハイフン部分を削除 macの場合全角ハイフンの文字コードが異なるため単純な全角半角変換ができない
            $postal = mb_substr($postal,0, 3) . mb_substr($postal, 4, 4);
        }
        $postal = mb_convert_kana($postal, 'nhk', "utf-8");

        // 配送日時情報取得
        $this->loadModel('KitDeliveryDatetime');

        $result = $this->KitDeliveryDatetime->getKitDeliveryDatetime(array('postal' => $postal));

        return $result;
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
        CakeSession::delete('OrderTotalList');
        CakeSession::delete('Address');
        CakeSession::delete('Credit');
        CakeSession::delete('order_sneaker');
    }

}
