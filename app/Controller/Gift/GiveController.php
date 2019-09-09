<?php

App::uses('MinikuraController', 'Controller');

App::uses('GiftKitPrice', 'Model');
App::uses('PaymentGMOPurchaseGift', 'Model');
App::uses('AmazonPayModel', 'Model');
App::uses('PaymentAmazonGiftAmazonPay', 'Model');

class GiveController extends MinikuraController
{
    const MODEL_NAME_GIFT_BY_CREDIT_CARD = 'PaymentGMOPurchaseGift';
    const MODEL_NAME_GIFT_BY_AMAZON      = 'PaymentAmazonGiftAmazonPay';
    const MODEL_NAME_CREDIT_CARD         = 'PaymentGMOCreditCard';
    const MODEL_NAME_CREDIT_CARD_CHECK   = 'PaymentGMOCreditCardCheck';

    /** layout */
    public $layout = 'order';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        // TODO ギフトリリースまで遷移させない
        new AppTerminalError(AppE::NOT_FOUND, 404);

        parent::beforeFilter();

        // 法人口座未登録用遷移
        $actionCannot = 'cannot';
        if ($this->action !== $actionCannot && !$this->Customer->isEntry() && !$this->Customer->canOrderKit()) {
            return $this->redirect(['action' => $actionCannot]);
        }

        $this->Order = $this->Components->load('Order');
        $this->Order->init($this->Customer->getToken()['division'], $this->Customer->hasCreditCard());
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
        if (!$this->Customer->canOrderKit() && ($this->action === 'complete_card' || $this->action === 'complete_bank')) {
            return true;
        }
        return false;
    }

    /**
     * 入力フォーム選択
     */
    public function gift_add()
    {
        // session delete
        $allow_action_list = [
            'Give/gift_add',
            'Give/gift_input_card',
            'Give/gift_confirm_card',
            'Give/gift_input_amazon_pay',
            'Give/gift_confirm_amazon_pay',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->_cleanKitOrderSession();
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // entry user
        if ($this->Customer->isEntry()) {
            return $this->redirect(['controller' => 'customer/register', 'action' => 'add_personal']);
        }

        // Amazon Payment
        if ($this->Customer->isAmazonPay()) {
            CakeSession::write('order_type', 'amazon');
            $this->redirect('/gift/give/input_amazon_pay');
        }

        // corporate user
        if (!$this->Customer->isPrivateCustomer()) {
            return $this->redirect('/');
        }

        // get card data
        $card_data = $this->Customer->getDefaultCard();
        CakeSession::write('card_data', $card_data);
        CakeSession::write('order_type', 'card');
        $this->redirect('/gift/give/input_card');
    }

    /**
     * クレジットカード購入入力フォーム
     */
    public function gift_input_card()
    {
        // check access source actions
        $allow_action_list = [
            'Give/gift_add',
            'Give/gift_input_card',
            'Give/gift_confirm_card',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'give', 'action' => 'gift_add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_GIFT_BY_CREDIT_CARD);

        if ($this->request->is('get')) {

            $this->request->data[self::MODEL_NAME_GIFT_BY_CREDIT_CARD] = CakeSession::read(self::MODEL_NAME_GIFT_BY_CREDIT_CARD);

            $this->set('card_data', CakeSession::read('card_data'));

            $this->PaymentGMOPurchaseGift->set($this->request->data);

        } elseif ($this->request->is('post')) {

            $data = $this->request->data[self::MODEL_NAME_GIFT_BY_CREDIT_CARD];

            // 注文情報
            $order_list = $this->_setOrderList($data);

            /** セッションデータ */
            CakeSession::write(self::MODEL_NAME_GIFT_BY_CREDIT_CARD, $data);

            $this->PaymentGMOPurchaseGift->set($data);

            $error_flag = false;

            /** 購入情報バリデーション */
            $validation_item = [
                'security_cd',
                'cleaning_num',
                'receiver_email',
                'sender_name',
                'email_message',
                'gift_cleaning_num',
            ];
            if (!$this->PaymentGMOPurchaseGift->validates(['fieldList' => $validation_item])) {
                $error_flag = true;
            }

            // 登録したカードを変更するにチェックをつけて、POSTした場合、登録を促す
            if ($data['select-card'] !== 'as-card' || empty(CakeSession::read('card_data'))) {
                $this->PaymentGMOPurchaseGift->validationErrors['card_no'] = 'カードを変更・登録する場合はこの画面でカードを登録を完了させて下さい';
                $error_flag = true;
            }

            if ($error_flag) {
                $this->set('card_data', CakeSession::read('card_data'));
                return $this->render('gift_input_card');
            }

            CakeSession::write('order_list', $order_list);

            return $this->redirect(['controller' => 'give', 'action' => 'confirm_card']);

        }
    }

    /**
     * クレジットカード購入確認フォーム
     */
    public function gift_confirm_card()
    {
        // check access source actions
        $allow_action_list = [
            'Give/gift_input_card',
            'Give/gift_confirm_card',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'give', 'action' => 'gift_add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->set('card_data', CakeSession::read('card_data'));
        $this->set('order_list', CakeSession::read('order_list'));
        $this->set('data', CakeSession::read(self::MODEL_NAME_GIFT_BY_CREDIT_CARD));
    }

    /**
     * クレジットカード完了フォーム
     */
    public function gift_complete_card()
    {
        // check access source actions
        $allow_action_list = [
            'Give/gift_confirm_card',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'give', 'action' => 'gift_add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        /** セッションデータ */
        $data = CakeSession::read(self::MODEL_NAME_GIFT_BY_CREDIT_CARD);

        /** 決済 */
        $this->_postPaymentCreditCard($data);

        $this->set('card_data', CakeSession::read('card_data'));
        $this->set('order_list', CakeSession::read('order_list'));
        $this->set('data', $data);

        $this->_cleanKitOrderSession();
    }

    /*
     * AmazonPayment入力フォーム
     */
    public function gift_input_amazon_pay()
    {
        // check access source actions
        $allow_action_list = [
            'Give/gift_add',
            'Give/gift_input_amazon_pay',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'give', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_GIFT_BY_AMAZON);

        if ($this->request->is('get')) {

            $this->request->data[self::MODEL_NAME_GIFT_BY_AMAZON] = CakeSession::read(self::MODEL_NAME_GIFT_BY_AMAZON);

            $this->PaymentAmazonGiftAmazonPay->set($this->request->data);

        } elseif ($this->request->is('post')) {

            $data = $this->request->data[self::MODEL_NAME_GIFT_BY_AMAZON];

            /** データ整形 */
            // Amazonより取得した個人情報よりデータ整形
            $this->_setAmazonCustomerData($data);

            // 注文情報
            $kit_list = array();
            $order_list = $this->_setOrderList($data);

            /** セッションデータ */
            CakeSession::write(self::MODEL_NAME_GIFT_BY_AMAZON, $data);

            $this->PaymentAmazonGiftAmazonPay->set($data);

            $error_flag = false;

            /** 購入者情報バリデーション */
            $validation_item = [
                'access_token',
                'amazon_order_reference_id',
                'receiver_email',
                'sender_name',
                'email_message',
                'gift_cleaning_num',
            ];
            if (!$this->PaymentAmazonGiftAmazonPay->validates(['fieldList' => $validation_item])) {
                $error_flag = true;
            }

            if ($error_flag) {
                $this->set('address_list', CakeSession::read('address_list'));
                $this->set('card_data', CakeSession::read('card_data'));
                return $this->render('gift_input_amazon_pay');
            }

            CakeSession::write('order_list', $order_list);

            return $this->redirect(['controller' => 'give', 'action' => 'confirm_amazon_pay']);

        }
    }

    /**
     * AmazonPayment確認フォーム
     */
    public function gift_confirm_amazon_pay()
    {
        // check access source actions
        $allow_action_list = [
            'Give/gift_input_amazon_pay',
            'Give/gift_confirm_amazon_pay',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'give', 'action' => 'gift_add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->set('order_list', CakeSession::read('order_list'));
        $this->set(self::MODEL_NAME_GIFT_BY_AMAZON, CakeSession::read(self::MODEL_NAME_GIFT_BY_AMAZON));
    }

    /**
     * AmazonPayment完了フォーム
     */
    public function gift_complete_amazon_pay()
    {
        // check access source actions
        $allow_action_list = [
            'Give/gift_confirm_amazon_pay',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'give', 'action' => 'gift_add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        /** セッションデータ */
        $data = CakeSession::read(self::MODEL_NAME_GIFT_BY_AMAZON);

        /** 決済 */
        $this->_postPaymentAmazon($data);

        $this->set('order_list', CakeSession::read('order_list'));
        $this->set(self::MODEL_NAME_GIFT_BY_AMAZON, CakeSession::read(self::MODEL_NAME_GIFT_BY_AMAZON));

        $this->_cleanKitOrderSession();
    }

    public function as_register_credit_card()
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

        // set session (card data)
        CakeSession::write('card_data', $this->Customer->getDefaultCard());

        return json_encode($result);
    }

    public function as_update_credit_card()
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

        // set session (card data)
        CakeSession::write('card_data', $this->Customer->getDefaultCard());

        return json_encode($result);
    }

    public function as_check_credit_card()
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
        return json_encode($result);
    }

    /**
     * AmazonPayment情報取得設定
     */
    private function _setAmazonCustomerData(&$_data)
    {
        $this->loadModel('AmazonPayModel');

        $tmp_data = $_data;

        $tmp_data['address_consent_token'] = $this->Customer->getAmazonPayAccessKey();
        $tmp_data['mws_auth_token']        = Configure::read('app.amazon_pay.client_id');

        $result = $this->AmazonPayModel->getOrderReferenceDetails($tmp_data);

        if($result['ResponseStatus'] != '200') {
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($result, true));
            $this->Flash->validation('Amazon Pay からの情報取得に失敗しました。再度お試し下さい。', ['key' => 'customer_amazon_pay_info']);
            $this->redirect(['controller' => 'give', 'action' => 'gift_add']);
        }

        // Amazonより個人情報を取得
        $physicaldestination = $result['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination']['PhysicalDestination'];
        $physicaldestination = $this->AmazonPayModel->wrapPhysicalDestination($physicaldestination);

        $amazon_physical_name_list = AMAZON_CHANGE_PHYSICALDESTINATION_NAME_ARRAY;
        foreach ($amazon_physical_name_list as $amazon_name => $data_name) {
            switch (true) {
                case $amazon_name === 'PostalCode':
                    $_data[$data_name] = $this->_editPostalFormat($physicaldestination[$amazon_name]);
                    break;
                default:
                    $_data[$data_name] = $physicaldestination[$amazon_name];
                    break;
            }
        }
    }

    /**
     * カード決済
     */
    private function _postPaymentCreditCard($_data)
    {
        $this->loadModel(self::MODEL_NAME_GIFT_BY_CREDIT_CARD);

        // データ整形
        $_data['security_cd'] = self::_wrapConvertKana($_data['security_cd']);

        $result_kit_card = $this->PaymentGMOPurchaseGift->apiPost($_data);
        if ($result_kit_card->status !== '1') {
            $this->Flash->validation($result_kit_card->error_message, ['key' => 'customer_kit_card_info']);
            return $this->redirect(['controller' => 'give', 'action' => 'gift_add']);
        }
    }

    /**
     * AmazonPayment決済
     */
    private function _postPaymentAmazon($_data)
    {
        $this->loadModel('PaymentAmazonGiftAmazonPay');

        // データ整形
        $_data['tel1'] = self::_wrapConvertKana($_data['tel1']);

        $this->PaymentAmazonGiftAmazonPay->set($_data);
        $result_kit_amazon_pay = $this->PaymentAmazonGiftAmazonPay->apiPost($this->PaymentAmazonGiftAmazonPay->toArray());
        if ($result_kit_amazon_pay->status !== '1') {
            if ($result_kit_amazon_pay->http_code === 400) {
                $this->Flash->validation(AMAZON_PAY_ERROR_PAYMENT_FAILURE_RETRY, ['key' => 'customer_kit_card_info']);
            } else {
                $this->Flash->validation($result_kit_amazon_pay->message, ['key' => 'customer_kit_card_info']);
            }
            $this->redirect(['controller' => 'give', 'action' => 'gift_add']);
        }
    }

    /**
     * 注文内容の作成
     */
    private function _setOrderList(&$_data)
    {
        // kitコード 表示kit名取得
        $kit_code = KIT_CODE_DISP_NAME_ARRAY;
        // 金額取得API
        $kit_price = new GiftKitPrice();
        // 決済時に使用するkitパラメータ
        $kit_param_list = array();
        // 金額集計
        $order_list = array();
        // kit情報
        $_kit_list = array();

        foreach ($_data as $key => $value) {
            if (array_key_exists ($key, $kit_code)) {
                if ($value != 0 ) {
                    $code = $kit_code[$key]['code'];
                    // gvido用のコードを変換
                    $customer_info = $this->Customer->getInfo();
                    if (isset($customer_info['alliance_cd'])) {
                        if ($customer_info["alliance_cd"] == 'gvido' && $kit_code[$key]['code'] == KIT_CD_LIBRARY_DEFAULT) {
                            $code = KIT_CD_LIBRARY_GVIDO;
                        }
                    }
                    // 注文タイプ判別
                    $_kit_list[$code] = $value;
                    $order_list[$code]['number']   = $value;
                    $order_list[$code]['kit_name'] = $kit_code[$key]['name'];
                    $order_list[$code]['price']    = 0;
                    $kit_param_list[] = $code . ':' .$value;
                }
            }
        }

        $_data['kit'] = implode(',', $kit_param_list);

        $r = $kit_price->apiGet(['kit' => implode(',', $kit_param_list)]);
        if ($r->isSuccess()) {
            foreach ($r->results as $price_data) {
                $order_list[$price_data['kit_cd']]['price'] += $price_data['price'];
            }
        }

        return $order_list;
    }

    /**
     * orderで使用しているセッションを削除
     */
    private function _cleanKitOrderSession()
    {
        CakeSession::delete(self::MODEL_NAME_GIFT_BY_CREDIT_CARD);
        CakeSession::delete(self::MODEL_NAME_GIFT_BY_AMAZON);
        CakeSession::delete('card_data');
        CakeSession::delete('order_list');
    }

}
