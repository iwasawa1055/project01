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
            return $this->setAction('add_sneakers');
        }

        return $this->setAction('input');
    }

    /**
     *
     */
    public function input()
    {
        // 直にアクセスされた用 スニーカー判定
        if ($this->Customer->getInfo()['oem_cd'] === OEM_CD_LIST['sneakers']) {
            return $this->setAction('add_sneakers');
        }

        // エントリーユーザの場合初回購入動線へ移動
        if ($this->Customer->isEntry()) {
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        if (!empty(CakeSession::read('OrderKit.address_id'))) {

            $address_id = CakeSession::read('OrderKit.address_id');
            // 前回追加選択は最後のお届け先を選択
            if ($address_id === AddressComponent::CREATE_NEW_ADDRESS_ID) {

                $last_address = $this->Address->last();

                CakeSession::write('OrderKit.address_id', $last_address['address_id']);
                CakeSession::write('OrderKit.datetime_cd', '');

                $select_delivery = $this->getDatetimeDeliveryKit($last_address['address_id']);
                $select_delivery_list = json_decode(json_encode($select_delivery));

                CakeSession::write('OrderKit.select_delivery', $select_delivery);
                CakeSession::write('OrderKit.select_delivery_list', $select_delivery_list);
            }
        }

        // セッションリセット
        if (empty(CakeSession::read('OrderKit.address_id'))) {

            $OrderKit = array(
                'address_list' => array(),
                'address_id' => "",
                'address' => array(),
                'select_delivery' => "",
                'select_delivery_text' => "",
                'select_delivery_list' => array(),
                'card_data' => array(),
                'kit_params' => array(),
            );

            CakeSession::write('OrderKit', $OrderKit);
        }

        // セッション情報格納
        // 住所一覧を取得
        $OrderKit = CakeSession::read(self::MODEL_NAME);
        $address_list = $this->Address->get();

        // ヘルパー読み込めないため
        $set_address_list = array();
        if (is_array($address_list)) {
            foreach ($address_list as $address) {
                $set_address_list[$address['address_id']] = h("〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}");
            }
        }
        $set_address_list[AddressComponent::CREATE_NEW_ADDRESS_ID] = 'お届先を追加する';

        $OrderKit['address_list'] = $set_address_list;

        // カード利用かどうか
        // 法人口座未登録用遷移はbeforeFilterで判定済み
        CakeSession::write('isCredit', false);
        if (!$this->Customer->isEntry() && !$this->Customer->isCustomerCreditCardUnregist() && !$this->Customer->isCorprateCreditCardUnregist()) {
            // カード情報取得
            if ($this->Customer->isPrivateCustomer() || !$this->Customer->getCorporatePayment()) {
                // カード情報取得
                $OrderKit['card_data'] = $this->Customer->getDefaultCard();
                CakeSession::write('isCredit', true);
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
        if($this->Customer->getInfo()['oem_cd'] === OEM_CD_LIST['sneakers']) {
            return $this->setAction('confirm_sneakers');
        }

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
        if (CakeSession::read('isCredit')) {
            CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'. 'isCredit');

            // カード情報
            $security_cd = filter_input(INPUT_POST, 'security_cd');
            $params['security_cd'] = mb_convert_kana($security_cd, 'nhk', "utf-8");
            $OrderKit['security_cd'] = $security_cd;
        }

        // お届け先情報等
        $params['address_id'] = $address_id;
        $OrderKit['address_id'] = $address_id;

        $params['datetime_cd'] = filter_input(INPUT_POST, 'datetime_cd');
        $OrderKit['datetime_cd'] = $params['datetime_cd'];

        CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'. 'params' . print_r($params, true));

        // パラメータチェック不要な要素
        // 表示用時間一覧
        $select_delivery = filter_input(INPUT_POST, 'select_delivery');
        $OrderKit['select_delivery'] = $select_delivery;
        // CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'. 'select_delivery' . print_r($select_delivery, true));

        $select_delivery_list = json_decode($select_delivery);
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

        // 添字に対応するコードを設定
        $kit_code = KIT_CODE_DISP_NAME_ARRAY;

        // 金額集計
        $kitPrice = new CustomerKitPrice();
        $OrderList = array();

        // 購入時用コード格納
        $kit_params = array();
        foreach ($Order as $orders => $kit_order) {
            foreach ($kit_order as $param => $value) {

                // 選択されている場合
                if ($value != 0 ) {
                    // スタータキット以外まとめて処理
                    if (array_key_exists ($param, $kit_code)) {
                        // $OrderList[$param]['price']     = number_format($kit_code[$param]['price'] * $value * 1);
                        $code = $kit_code[$param]['code'];
                        $OrderList[$code]['number']    = $value;
                        $OrderList[$code]['kit_name']  = $kit_code[$param]['name'];
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

        // 住所パラメータチェック 入力されているわけでないので、選択した住所情報をチェックし共通のエラーを返す
        // カード決済、口座振替でパラメータが異なる
        // 住所idから住所取得
        $address = $this->Address->find($params['address_id']);

        // CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'. 'select_delivery_list' . print_r($OrderKit['select_delivery_list'], true));

        // 表示用に住所をセッションに保存
        CakeSession::write('Address', $address);

        // 決済によって必要情報が異なる
        $address_params = array();
        if (CakeSession::read('isCredit')) {
            // 口座決済
            $address_params['name'] = $address['lastname'] . $address['firstname'];
            $address_params['postal'] = $address['postal'];
            $address_params['address'] = $address['pref'] . $address['address1'] . $address['address2'] . $address['address3'];
            $address_params['tel1'] = $address['tel1'];
        } else {
            // カード決済
            $address_params = $address;
        }

        // 住所パラメータチェック 住所エラーの場合表示場所が
        $validation = AppValid::validate($address_params);
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
                CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'.$message . 'key '. $key);
            }

            $this->Flash->validation('お届け先の形式が正しくありません。会員情報またはお届け先変更にてご確認ください。'
                                        ,['key' => 'format_address']);
            $is_validation_error = true;
        }

        CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'. 'orderKit '. print_r(CakeSession::read('OrderKit'),true));
        if ($is_validation_error === true) {
            $this->redirect('input');
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
        if($this->Customer->getInfo()['oem_cd'] === OEM_CD_LIST['sneakers']) {
            return $this->setAction('complete_sneakers');
        }

        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['Order/confirm', 'Order/complete'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'order', 'action' => 'input']);
        }

        // 決済方法によって実行するapiがことなる。
        if (CakeSession::read('isCredit')) {
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
                return $this->redirect('input');
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
                return $this->redirect('input');
            }
        }

        // 完了したページ情報を保存
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->_cleanKitOrderSession();
    }

    public function add_sneakers()
    {
        $isBack = Hash::get($this->request->query, 'back');
        $res_datetime = [];
        $data = CakeSession::read(self::MODEL_NAME);
        CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'.print_r($data, true));
        if ($isBack && !empty($data)) {
            if (!array_key_exists('address_id', $data)) {
                $data['address_id'] = '';
            }
            // 前回追加選択は最後のお届け先を選択
            if ($data['address_id'] === AddressComponent::CREATE_NEW_ADDRESS_ID) {
                $data['address_id'] = Hash::get($this->Address->last(), 'address_id', '');
                $data['datetime_cd'] = '';
            }
            $this->request->data[self::MODEL_NAME] = $data;
            if (!empty($data['address_id'])) {
                $res_datetime = $this->getDatetimeDeliveryKit($data['address_id']);
            }
        }

        $this->set('datetime', $res_datetime);

        $this->render('add_sneakers');

        CakeSession::delete(self::MODEL_NAME);
    }

    public function confirm_sneakers()
    {

        $data = Hash::get($this->request->data, self::MODEL_NAME);
        if (empty($data)) {
            return $this->render('add');
        }
        $model = $this->Order->model($data);
        $paymentModelName = $model->getModelName();

        // 届け先追加を選択の場合は追加画面へ遷移
        if (Hash::get($model->toArray(), 'address_id') === AddressComponent::CREATE_NEW_ADDRESS_ID) {
            CakeSession::write(self::MODEL_NAME, $model->toArray());
            return $this->redirect([
                'controller' => 'address', 'action' => 'add', 'customer' => true,
                '?' => ['return' => 'order']
            ]);
        }

        // キットPOSTデータキー
        $dataKeyNum = [
            KIT_CD_SNEAKERS => 'sneakers_num',
        ];

        // 料金（サービス（商品）ごと）集計
        $kitPrice = new CustomerKitPrice();
        $total = ['num' => 0, 'price' => 0];
        $productKitList = [
            PRODUCT_CD_SNEAKERS => [
                'kitList' => [KIT_CD_SNEAKERS => 0],
                'subtotal' => ['num' => 0, 'price' => 0]
            ],
        ];
        foreach ($productKitList as $productCd => &$product) {
            $product['pramaKit'] = [];

            // 個数集計
            foreach ($product['kitList'] as $kitCd => $d) {
                $num = Hash::get($this->request->data, self::MODEL_NAME . '.' . $dataKeyNum[$kitCd]);
                if (!empty($num)) {
                    $product['kitList'][$kitCd] = $num;
                    $product['subtotal']['num'] += $num;
                    $total['num'] += $num;
                    $product['pramaKit'][] = $kitCd . ':' . $num;
                }
            }
            // 金額取得
            if (!empty($product['pramaKit'])) {

                $r = $kitPrice->apiGet([
                    'kit' => implode(',', $product['pramaKit'])
                ]);
                if ($r->isSuccess()) {
                    $price = $r->results[0]['total_price'] * 1;
                    $product['subtotal']['price'] = $price;
                    $total['price'] += $price;
                }
            }
        }

        $this->set('productKitList', $productKitList);
        $this->set('total', $total);

        // 仮登録ユーザーの場合 or カード登録なし本登録(個人)
        if ($this->Customer->isEntry() || $this->Customer->isCustomerCreditCardUnregist()) {
            if ($model->validates(['fieldList' => ['mono_num', 'hako_num', 'cleaning_num']])) {
                CakeSession::write(self::MODEL_NAME, $model->data[$paymentModelName]);
                return $this->render('confirm');
            } else {
                $this->set('validErrors', $model->validationErrors);
                return $this->render('add');
            }
        }
        // 本登録ユーザーの場合
        $address_id = $this->request->data[self::MODEL_NAME]['address_id'];
        $address = $this->Address->find($address_id);

        // お届け先情報
        $model = $this->Order->setAddress($model->data[$paymentModelName], $address);
        $model->data[$paymentModelName]['kit'] = implode(Hash::extract($productKitList, '{n}.pramaKit.{n}'), ',');

        if ($model->validates()) {
            if ($this->Customer->isPrivateCustomer() || empty($this->Customer->getCorporatePayment())) {
                // カード
                $default_payment = $this->Customer->getDefaultCard();
                $this->set('default_payment_text', "{$default_payment['card_no']}　{$default_payment['holder_name']}");
            }
            // お届け先
            $this->set('address_text', "〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}");
            // お届け希望日時
            $datetime = $this->getDatetime($address_id, $this->request->data[self::MODEL_NAME]['datetime_cd']);
            $this->set('datetime', $datetime['text']);

            $model->data[$paymentModelName]['view_data_productKitList'] = serialize($productKitList);
            $model->data[$paymentModelName]['view_data_total'] = serialize($total);
            CakeSession::write(self::MODEL_NAME, $model->data[$paymentModelName]);
        } else {
            $this->set('validErrors', $model->validationErrors);

            // 配送日時
            $res_datetime = [];
            if (!empty($address_id)) {
                $res_datetime = $this->getDatetimeDeliveryKit($address_id);
            }
            $this->set('datetime', $res_datetime);

            return $this->render('add_sneakers');
        }

        return $this->render('confirm_sneakers');
    }

    /**
     *
     */
    public function complete_sneakers()
    {
        $data = CakeSession::read(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME);
        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }
        $model = $this->Order->model($data);

        if ($model->validates()) {
            // api
            $res = $model->apiPost($model->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'add']);
            }

            $address_id = $data['address_id'];
            $address = $this->Address->find($address_id);
            $this->set('data', $data);

            if ($this->Customer->isPrivateCustomer() || empty($this->Customer->getCorporatePayment())) {
                // カード
                $default_payment = $this->Customer->getDefaultCard();
                $this->set('default_payment_text', "{$default_payment['card_no']}　{$default_payment['holder_name']}");
            }
            // お届け先
            $this->set('address_text', "〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}");
            // お届け希望日時
            $datetime = $this->getDatetime($address_id, $data['datetime_cd']);
            $this->set('datetime', $datetime['text']);

            // 料金
            $this->set('productKitList', unserialize($data['view_data_productKitList']));
            $this->set('total', unserialize($data['view_data_total']));
        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }
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
     * first orderで使用しているセッション類を削除
     */
    private function _cleanKitOrderSession()
    {
        CakeSession::delete('Order');
        CakeSession::delete('OrderTotal');
        CakeSession::delete('OrderKit');
        CakeSession::delete('OrderList');
        CakeSession::delete('Address');
        CakeSession::delete('isCredit');
    }

}
