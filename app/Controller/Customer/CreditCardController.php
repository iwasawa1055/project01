<?php 
App::uses('MinikuraController', 'Controller');

class CreditCardController extends MinikuraController
{
    const MODEL_NAME_CREDIT_CARD = 'PaymentGMOCreditCard';
    const MODEL_NAME_CREDIT_CARD_CHECK = 'PaymentGMOCreditCardCheck';
    const MODEL_NAME_AMAZON_PAY_INFO = 'AmazonPayInfo';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME_CREDIT_CARD);
        $this->set('action', $this->action);
    }

    protected function isAccessDeny()
    {
        if (!$this->Customer->hasCreditCard() && $this->action === 'customer_edit') {
            // カード登録なし：変更不可
            return true;
        } elseif ($this->Customer->hasCreditCard() && ($this->action === 'customer_add' && Hash::get($this->request->params, 'step') !== 'complete')) {
            // カード登録あり：登録不可
            return true;
        } elseif (!$this->Customer->isPaymentNG() && $this->action === 'paymentng_edit') {
            // 債務ランク以外
            return true;
        }
        return false;
    }

    private function setRequestDataFromSession()
    {
        $step = Hash::get($this->request->params, 'step');
        $back = Hash::get($this->request->query, 'back');

        if ($back || $step === 'complete') {
            $data = CakeSession::read(self::MODEL_NAME_CREDIT_CARD);
            $this->request->data = $data;
            CakeSession::delete(self::MODEL_NAME_CREDIT_CARD);
        } elseif (($this->action === 'customer_edit' || $this->action === 'paymentng_edit') && empty($step)) {
            // edit 初期表示データ取得
            $default_payment = $this->PaymentGMOCreditCard->apiGetDefaultCard();
            $this->request->data[self::MODEL_NAME_CREDIT_CARD] = $default_payment;
        } elseif ($this->action === 'customer_add' && empty($step)) {
            // create カード登録確認
            $default_payment = $this->PaymentGMOCreditCard->apiGetDefaultCard();
            if (!empty($default_payment)) {
                return $this->redirect(['controller' => 'order', 'action' => 'add', 'customer' => false]);
            }
        }
    }

    /**
     * 登録
     */
    public function customer_add()
    {
        $this->setRequestDataFromSession();
        $step = Hash::get($this->request->params, 'step');

        if ($this->request->is('get')) {

            return $this->render('customer_edit');
        } elseif ($this->request->is('post')) {

            if ($this->Customer->isEntry()) {
                // 契約情報登録
                return $this->redirect(['controller' => 'info', 'action' => 'add']);
            }
            return $this->render('customer_complete');
        }
    }

    /**
     * 修正
     */
    public function customer_edit()
    {
        $this->setRequestDataFromSession();

        if ($this->request->is('get')) {
            return $this->render('customer_edit');
        } elseif ($this->request->is('post')) {
            return $this->render('customer_complete');
        }
    }

    /**
     * 債務ユーザー
     */
    public function paymentng_edit()
    {
        $this->setRequestDataFromSession();
        $step = Hash::get($this->request->params, 'step');

        if ($this->request->is('get')) {
            $this->Flash->paymentng_card_edit('');
            return $this->render('customer_edit');
        } elseif ($this->request->is('post')) {

            return $this->render('customer_paymentng_complete');
        }
    }

    /**
     * 修正(アマゾンペイメント)
     */
    public function customer_edit_amazon_pay()
    {
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        // BAIDを取得
        $baid = CakeSession::read('login.amazon_pay.baid');

        // access_tokenを取得
        $access_token = CakeSession::read('login.amazon_pay.access_token');

        $this->loadModel('AmazonPayModel');
        $set_param = array();
        $set_param['amazon_billing_agreement_id'] = $baid;
        $set_param['address_consent_token'] = $access_token;
        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->getBillingAgreementDetails($set_param);

        if($res['ResponseStatus'] != '200') {
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));
        }

        $state = '';
        if (isset($res['GetBillingAgreementDetailsResult']['BillingAgreementDetails']['BillingAgreementStatus']['State'])) {
            $state = $res['GetBillingAgreementDetailsResult']['BillingAgreementDetails']['BillingAgreementStatus']['State'];
        }

        if ($state == 'Open' || $state == 'Suspended') {
            $baid = CakeSession::read('login.amazon_pay.baid');
            $regist_user_flg = 0;
        } else {
            $baid = '';
            $regist_user_flg = 1;
        }

        $this->set('baid', $baid);
        $this->set('regist_user_flg', $regist_user_flg);
        $this->set('debt', false);
        $this->set('action', '/customer/credit_card/complete_amazon_pay');

        return $this->render('customer_edit_amazon_pay');
    }

    /**
     * 完了(アマゾンペイメント)
     */
    public function customer_complete_amazon_pay()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['CreditCard/customer_edit_amazon_pay'], true) === false) {
            return $this->redirect('/customer/credit_card/edit_amazon_pay');
        }

        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $baid = $this->request->data['amazon_billing_agreement_id'];
        $regist_user_flg = $this->request->data['regist_user_flg'];

        // AmazonPay 定期購入確定処理 会員登録で確定時にBAIDを確定させる
        $this->loadModel('AmazonPayModel');
        $set_param = array();
        $set_param['merchant_id'] = Configure::read('app.amazon_pay.merchant_id');
        $set_param['amazon_billing_agreement_id'] = $baid;
        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->setConfirmBillingAgreement($set_param);

        if ($res['ResponseStatus'] != '200') {
            $this->Flash->validation('AmazonPay情報の更新処理に失敗しました。', ['key' => 'amazon_pay_info']);
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));
            return $this->redirect('/customer/credit_card/edit_amazon_pay');
        }

        //baid
        $this->loadModel(self::MODEL_NAME_AMAZON_PAY_INFO);

        $data = array();
        $data[self::MODEL_NAME_AMAZON_PAY_INFO]['amazon_user_id'] = CakeSession::read('login.amazon_pay.amazon_user_id');
        $data[self::MODEL_NAME_AMAZON_PAY_INFO]['amazon_billing_agreement_id'] = $baid;
        $this->AmazonPayInfo->set($data);
        $res = $this->AmazonPayInfo->apiPatch($this->AmazonPayInfo->toArray());

        if ($res->status != 1) {
            $this->Flash->validation('AmazonPay情報の登録に失敗しました。', ['key' => 'amazon_pay_info']);
            return $this->redirect('/customer/credit_card/edit_amazon_pay');
        }

        CakeSession::write('login.amazon_pay.baid', $baid);

        $this->set('debt', false);
        return $this->render('customer_complete_amazon_pay');
    }

    /**
     * 債務ユーザー(アマゾンペイメント)
     */
    public function paymentng_edit_amazon_pay()
    {
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        // BAIDを取得
        $baid = CakeSession::read('login.amazon_pay.baid');

        // access_tokenを取得
        $access_token = CakeSession::read('login.amazon_pay.access_token');

        $this->loadModel('AmazonPayModel');
        $set_param = array();
        $set_param['amazon_billing_agreement_id'] = $baid;
        $set_param['address_consent_token'] = $access_token;
        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->getBillingAgreementDetails($set_param);

        if($res['ResponseStatus'] != '200') {
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));
        }

        $state = '';
        if (isset($res['GetBillingAgreementDetailsResult']['BillingAgreementDetails']['BillingAgreementStatus']['State'])) {
            $state = $res['GetBillingAgreementDetailsResult']['BillingAgreementDetails']['BillingAgreementStatus']['State'];
        }

        if ($state == 'Open' || $state == 'Suspended') {
            $baid = CakeSession::read('login.amazon_pay.baid');
            $regist_user_flg = 0;
        } else {
            $baid = '';
            $regist_user_flg = 1;
        }

        $this->set('baid', $baid);
        $this->set('regist_user_flg', $regist_user_flg);
        $this->set('debt', true);
        $this->set('action', '/paymentng/credit_card/complete_amazon_pay');

        return $this->render('customer_edit_amazon_pay');
    }

    /**
     * 債務ユーザー入力完了(アマゾンペイメント)
     */
    public function paymentng_complete_amazon_pay()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['CreditCard/paymentng_edit_amazon_pay'], true) === false) {
            return $this->redirect(['controller' => 'credit_card', 'action' => 'edit_amazon_pay', 'paymentng' => true]);
        }

        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $baid = $this->request->data['amazon_billing_agreement_id'];
        $regist_user_flg = $this->request->data['regist_user_flg'];

        // AmazonPay 定期購入確定処理 会員登録で確定時にBAIDを確定させる
        $this->loadModel('AmazonPayModel');
        $set_param = array();
        $set_param['merchant_id'] = Configure::read('app.amazon_pay.merchant_id');
        $set_param['amazon_billing_agreement_id'] = $baid;
        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->setConfirmBillingAgreement($set_param);

        if($res['ResponseStatus'] != '200') {
            $this->Flash->validation('AmazonPay情報の更新に失敗しました。', ['key' => 'amazon_pay_info']);
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));
            return $this->redirect(['controller' => 'credit_card', 'action' => 'edit_amazon_pay', 'paymentng' => true]);
        }

        //baid
        $this->loadModel(self::MODEL_NAME_AMAZON_PAY_INFO);

        $data = array();
        $data[self::MODEL_NAME_AMAZON_PAY_INFO]['amazon_user_id'] = CakeSession::read('login.amazon_pay.amazon_user_id');
        $data[self::MODEL_NAME_AMAZON_PAY_INFO]['amazon_billing_agreement_id'] = $baid;
        $this->AmazonPayInfo->set($data);
        $res = $this->AmazonPayInfo->apiPatch($this->AmazonPayInfo->toArray());

        if ($res->status != 1) {
            $this->Flash->validation('AmazonPay情報の登録に失敗しました。', ['key' => 'amazon_pay_info']);
            return $this->redirect(['controller' => 'credit_card', 'action' => 'edit_amazon_pay', 'paymentng' => true]);
        }

        CakeSession::write('login.amazon_pay.baid', $baid);

        $this->set('debt', true);
        return $this->render('customer_complete_amazon_pay');
    }

    public function paymentng_as_register_credit_card()
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

    public function paymentng_as_update_credit_card()
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

    public function paymentng_as_check_credit_card()
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
}
