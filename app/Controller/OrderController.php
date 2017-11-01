<?php

App::uses('MinikuraController', 'Controller');
App::uses('CustomerKitPrice', 'Model');
App::uses('PaymentGMOKitByCreditCard', 'Model');
App::uses('AmazonPayModel', 'Model');
App::uses('PaymentAmazonKitAmazonPay', 'Model');


App::uses('AppValid', 'Lib');
App::uses('KitDeliveryDatetime', 'Model');
App::uses('EmailModel', 'Model');
App::uses('FirstKitPrice', 'Model');
App::uses('AppCode', 'Lib');

class OrderController extends MinikuraController
{
    const MODEL_NAME = 'OrderKit';
    const MODEL_NAME_DATETIME = 'DatetimeDeliveryKit';
    const MODEL_NAME_CREDIT_CARD = 'PaymentGMOCreditCard';
    const MODEL_NAME_CREDIT_CARD_CHECK = 'PaymentGMOCreditCardCheck';

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

        // アマゾンペイメント対応
        if ($this->Customer->isAmazonPay()) {
            $this->redirect('/order/input_amazon_pay');
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

        // アマゾンペイメント対応
        if ($this->Customer->isAmazonPay()) {
            $this->redirect('/order/input_amazon_pay');
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

    public function input_amazon_pay()
    {
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
                'security_cd' => filter_input(INPUT_POST, 'security_cd'),
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

            // 逐次セッション保存
            CakeSession::write('OrderKit.select_card', $select_card);

            // ハイフン削除はバリデーション前に実施
            $vali_card_params['security_cd'] = mb_convert_kana($vali_card_params['security_cd'], 'nhk', "utf-8");;

            // 逐次バリデーション
            $validation = AppValid::validate($vali_card_params);
            //* 共通バリデーションでエラーあったらメッセージセット
            if (!empty($validation)) {
                foreach ($validation as $key => $message) {
                    $this->Flash->validation($message, ['key' => $key]);
                }
                $is_validation_error = true;
            }

            // 登録したカードを変更するにチェックをつけて、POSTした場合、登録を促す
            if ($select_card === 'register') {
                $this->Flash->validation('カードを変更・登録する場合はこの画面でカードを登録を完了させて下さい。。', ['key' => 'card_no']);
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
        // direct_inboundは項目上不要
        unset($Order['direct_inbound']);
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
    public function confirm_amazon_pay()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['Order/input_amazon_pay', 'Order/confirm_amazon_pay', 'Order/complete_amazon_pay'],
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

        $params = [
            'datetime_cd'       => filter_input(INPUT_POST, 'datetime_cd'),
            'select_delivery'   => filter_input(INPUT_POST, 'select_delivery'),
            'insert_address_list'   => filter_input(INPUT_POST, 'insert-adress-list'),
        ];

        // お届け日をセッション保存用に変更
        $params['select_delivery_list'] = json_decode($params['select_delivery']);

        // お届け日のラベルを作成
        if(is_array($params['select_delivery_list'])) {
            foreach ($params['select_delivery_list'] as $key => $value) {
                if ($value->datetime_cd === $params['datetime_cd']) {
                    $params['select_delivery_text'] = $value->text;
                }
            }

            // お届け日のラベル
            // 逐次セッションに保存
            if (array_key_exists('select_delivery_text', $params)) {
                CakeSession::write('OrderKit.select_delivery_text', $params['select_delivery_text']);
            }
        }

        // amazon pay 情報取得
        // アマゾンウィジェットID取得
        $amazon_order_reference_id = filter_input(INPUT_POST, 'amazon_order_reference_id');
        if($amazon_order_reference_id === null) {
            // 初回かリターン確認
            if(CakeSession::read('Order.amazon_pay.amazon_order_reference_id') != null) {
                $amazon_order_reference_id = CakeSession::write('Order.amazon_pay.amazon_order_reference_id');
            }
        }

        // 住所情報等を取得
        $this->loadModel('AmazonPayModel');
        $set_param = array();
        $set_param['amazon_order_reference_id'] = $amazon_order_reference_id;
        $set_param['address_consent_token'] = $this->Customer->getAmazonPayAccessKey();
        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->getOrderReferenceDetails($set_param);
        // GetOrderReferenceDetails
        if($res['ResponseStatus'] != '200') {
            // ↓AmazonPayのエラーがどのような頻度で起きるか様子見するためのログ。消さないでー！
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));
            $this->Flash->validation('Amazon Pay からの情報取得に失敗しました。再度お試し下さい。', ['key' => 'customer_amazon_pay_info']);
            $this->redirect('/order/input_amazon_pay');
        }

        // 有効なアマゾンウィジェットIDを設定
        CakeSession::write('Order.amazon_pay.amazon_order_reference_id', $amazon_order_reference_id);
        // 住所に関する箇所を取得
        $physicaldestination = $res['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination']['PhysicalDestination'];
        $physicaldestination = $this->AmazonPayModel->wrapPhysicalDestination($physicaldestination);

        //Address情報を格納する配列
        $get_address = array();
        $get_address_amazon_pay = array();
        $get_address_tmp = array();

        $get_address_tmp = CakeSession::read('Address');

        // 住所情報セット
        $get_address_amazon_pay['name']      = $physicaldestination['Name'];

        $PostalCode = $this->_editPostalFormat($physicaldestination['PostalCode']);
        $get_address_amazon_pay['postal']      = $PostalCode;
        $get_address_amazon_pay['pref']        = $physicaldestination['StateOrRegion'];

        $get_address_amazon_pay['address1'] = $physicaldestination['AddressLine1'];
        $get_address_amazon_pay['address2'] = $physicaldestination['AddressLine2'];
        $get_address_amazon_pay['address3'] = $physicaldestination['AddressLine3'];
        $get_address_amazon_pay['tel1']        = $physicaldestination['Phone'];
        $get_address['datetime_cd'] = $params['datetime_cd'];
        $get_address['select_delivery_text'] = $this->_convDatetimeCode($params['datetime_cd']);

        $get_address = array_merge($get_address, $get_address_amazon_pay);

        // 住所情報更新
        CakeSession::write('Address',   $get_address);
        CakeSession::write('DispAddress', $get_address);

        //*  validation 基本は共通クラスのAppValidで行う
        //   お届け日時バリデーション
        $validation = AppValid::validate($params);
        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->Flash->validation(INPUT_ERROR, ['key' => 'customer_address_info']);
            $is_validation_error = true;
        }

        //*  validation 基本は共通クラスのAppValidで行う
        //   Amazon Pay 取得情報 バリデーション
        $validation = AppValid::validate($get_address_amazon_pay);
        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->Flash->validation(AMAZON_PAY_ERROR_URGING_INPUT, ['key' => 'customer_amazon_pay_info']);
            $is_validation_error = true;
        }

        if ($is_validation_error === true) {
            $this->redirect('/order/input_amazon_pay');
            return;
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

        // direct_inboundは項目上不要
        unset($Order['direct_inbound']);
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

        // アドレスの処理(API側でパースした際に12文字目がスペースのみで終わらないように変換をかける)
        $address = $set_address['pref'] . $set_address['address1'] . $set_address['address2'] . $set_address['address3'];
        if(mb_strlen($address) === 12  && mb_substr($address, 11, 1) === '　'){ //合計12文字で最後が全角スペースで終わる場合
            $address = mb_substr($address, 0, 11); //12文字目の全角スペースを除いた先頭から11文字を返す
        }


        // 決済方法によって実行するapiが異なる
        if (CakeSession::read('OrderKit.is_credit')) {
            // カード購入
            $this->loadModel('PaymentGMOKitByCreditCard');
            $gmo_kit_card = array();
            $gmo_kit_card['card_seq']      = 0;
            $gmo_kit_card['security_cd']   = self::_wrapConvertKana(CakeSession::read('Credit.security_cd'));
            $gmo_kit_card['address_id']    = CakeSession::read('OrderKit.address_id');
            $gmo_kit_card['datetime_cd']   = CakeSession::read('OrderKit.datetime_cd');
            $gmo_kit_card['name']          = $set_address['lastname'] . '　' . $set_address['firstname'];
            $gmo_kit_card['tel1']          = self::_wrapConvertKana($set_address['tel1']);
            $gmo_kit_card['postal']        = $set_address['postal'];
            $gmo_kit_card['address']       = $address;

            $kit_params = CakeSession::read('OrderKit.kit_params');
            $gmo_kit_card['kit'] = implode(',', $kit_params);

            $this->PaymentGMOKitByCreditCard->set($gmo_kit_card);
            $result_kit_card = $this->PaymentGMOKitByCreditCard->apiPost($this->PaymentGMOKitByCreditCard->toArray());

            if ($result_kit_card->status !== '1') {
                if ($result_kit_card->http_code === 400) {
                    // セキュリティコード入力エラーの場合は、出力位置を変更
                    if ($result_kit_card->message === '400 Bad Request - 42G440000') {
                        $this->Flash->validation($result_kit_card->error_message, ['key' => 'buy_kit_security_cd_error']);
                    } else {
                        $this->Flash->validation($result_kit_card->error_message, ['key' => 'customer_kit_card_info']);
                    }
                } else {
                    $this->Flash->validation($result_kit_card->error_message, ['key' => 'customer_kit_card_info']);
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


    /**
     *
     */
    public function complete_amazon_pay()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['Order/confirm_amazon_pay', 'Order/complete_amazon_pay'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'order', 'action' => 'input_amazon_pay']);
        }

        // 住所のセット
        $set_address = array();

        // 入力住所
        $set_address = CakeSession::read('Address');

        // 住所リスト追加
        // カード購入
        $this->loadModel('PaymentAmazonKitAmazonPay');
        $amazon_kit_pay = array();

        $amazon_pay_user_info = CakeSession::read('login.amazon_pay.user_info');

        // アドレスの処理(API側でパースした際に12文字目がスペースのみで終わらないように変換をかける)
        $address = $set_address['pref'] . $set_address['address1'] . $set_address['address2'] . $set_address['address3'];
        if(mb_strlen($address) === 12  && mb_substr($address, 11, 1) === '　'){ //合計12文字で最後が全角スペースで終わる場合
            $address = mb_substr($address, 0, 11); //12文字目の全角スペースを除いた先頭から11文字を返す
        }

        // CakeSession::read('Address.pref') . CakeSession::read('Address.address1') . CakeSession::read('Address.address2') . '　' .  CakeSession::read('Address.address3')
        $amazon_kit_pay['access_token']     = $this->Customer->getAmazonPayAccessKey();
        $amazon_kit_pay['amazon_user_id']   = $amazon_pay_user_info['user_id'];
        $amazon_kit_pay['amazon_order_reference_id'] = CakeSession::read('Order.amazon_pay.amazon_order_reference_id');
        $amazon_kit_pay['name']             = $set_address['name'];
        $amazon_kit_pay['tel1']             = self::_wrapConvertKana($set_address['tel1']);
        $amazon_kit_pay['postal']           = $set_address['postal'];
        $amazon_kit_pay['address']          = $address;
        $amazon_kit_pay['datetime_cd']      = CakeSession::read('OrderKit.datetime_cd');

        $kit_params = CakeSession::read('OrderKit.kit_params');
        $amazon_kit_pay['kit'] = implode(',', $kit_params);

        $this->PaymentAmazonKitAmazonPay->set($amazon_kit_pay);
        $result_kit_amazon_pay = $this->PaymentAmazonKitAmazonPay->apiPost($this->PaymentAmazonKitAmazonPay->toArray());
        if ($result_kit_amazon_pay->status !== '1') {
            if ($result_kit_amazon_pay->http_code === 400) {
                $this->Flash->validation(AMAZON_PAY_ERROR_PAYMENT_FAILURE_RETRY, ['key' => 'customer_kit_card_info']);
            } else {
                $this->Flash->validation($result_kit_amazon_pay->message, ['key' => 'customer_kit_card_info']);
            }
            $this->redirect('/order/input_amazon_pay');
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

    public function register_credit_card()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        $this->autoRender = false;

        $this->loadModel(self::MODEL_NAME_CREDIT_CARD);

        $gmo_token = $this->request->data['gmo_token'];
        if(!empty($gmo_token)){
            $gmo_token = implode(',',$gmo_token);
        }
        $credit_data[self::MODEL_NAME_CREDIT_CARD]['gmo_token'] = $gmo_token;
        $this->PaymentGMOCreditCard->set($credit_data);
        $result = $this->PaymentGMOCreditCard->apiPost($this->PaymentGMOCreditCard->toArray());

        return json_encode($result);
    }

    public function update_credit_card()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        $this->autoRender = false;

        $this->loadModel(self::MODEL_NAME_CREDIT_CARD);

        $gmo_token = $this->request->data['gmo_token'];
        if(!empty($gmo_token)){
            $gmo_token = implode(',',$gmo_token);
        }
        $credit_data[self::MODEL_NAME_CREDIT_CARD]['gmo_token'] = $gmo_token;
        $this->PaymentGMOCreditCard->set($credit_data);
        $result = $this->PaymentGMOCreditCard->apiPut($this->PaymentGMOCreditCard->toArray());

        return json_encode($result);
    }

    public function check_credit_card()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        $this->autoRender = false;

        $this->loadModel(self::MODEL_NAME_CREDIT_CARD_CHECK);

        $gmo_token = $this->request->data['gmo_token'];
        if(!empty($gmo_token)){
            $gmo_token = implode(',',$gmo_token);
        }
        $credit_data['gmo_token'] = $gmo_token;
        $result = $this->PaymentGMOCreditCardCheck->getCreditCardCheck($credit_data);
CakeLog::write(DEBUG_LOG, 'FILE_NAME:'.__FILE__.' LINE:'.__LINE__.' '.print_r(json_encode($result), true));
        return json_encode($result);
    }

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
     * ajax 指定IDの配送日時情報取得 amazon pay
     */
    public function as_get_address_datetime_by_amazon()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        // 画面描画しない
        $this->autoRender = false;

        $postal = "";

        $amazon_order_reference_id  = filter_input(INPUT_POST, 'amazon_order_reference_id');
        if($amazon_order_reference_id === null) {
            return json_encode(['status' => false]);
        }

        // モデルロード
        $this->loadModel('AmazonPayModel');

        $set_param = array();
        $set_param['amazon_order_reference_id'] = $amazon_order_reference_id;

        $set_param['address_consent_token'] = $this->Customer->getAmazonPayAccessKey();

        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->getOrderReferenceDetails($set_param);

        // GetOrderReferenceDetails
        if($res['ResponseStatus'] != '200') {
            return json_encode(['status' => false]);
        }

        if(!isset($res['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination']['PhysicalDestination']['PostalCode'])) {
            return json_encode(['status' => false]);
        }

        $postal = $res['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination']['PhysicalDestination']['PostalCode'];

        $result = $this->_getAddressDatetime($postal);

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
     * 指定IDの配送日時情報取得
     */
    private function _getAddressDatetime($postal)
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

    // 日付CD変換
    private function _convDatetimeCode ( $data_code ){

        // 時間CODE変換表
        $timeList = array( 2 => '午前中',
            //3 => '12～14時',
            4 => '14～16時',
            5 => '16～18時',
            6 => '18～20時',
            7 => '19～21時' );


        // 日付
        $date = substr( $data_code, 0, 10 );

        // 時間
        $time = substr( $data_code, 11, 1 );

        // 戻り値
        $datetime = date( "Y年m月d日", strtotime( $date ) );

        if( isset( $timeList[$time] )  ) $datetime .= ' '.$timeList[$time];
        return $datetime;
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
