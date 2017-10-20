<?php

App::uses('MinikuraController', 'Controller');

class CreditCardController extends MinikuraController
{
    const MODEL_NAME_SECURITY = 'PaymentGMOSecurityCard';
    const MODEL_NAME_CREDIT_CARD = 'PaymentGMOCreditCard';

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
        } elseif ($this->Customer->hasCreditCard() && $this->action === 'customer_add') {
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
            // create
            $credit_data[self::MODEL_NAME_CREDIT_CARD]['gmo_token'] = filter_input(INPUT_POST, 'gmo_token');
            $this->PaymentGMOCreditCard->set($credit_data);
            $res = $this->PaymentGMOCreditCard->apiPost($this->PaymentGMOCreditCard->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'add']);
            }

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
        $step = Hash::get($this->request->params, 'step');
        $returnTo = Hash::get($this->request->query, 'return');

        if ($this->request->is('get')) {
            return $this->render('customer_edit');
        } elseif ($this->request->is('post')) {
            $credit_data[self::MODEL_NAME_CREDIT_CARD]['gmo_token'] = filter_input(INPUT_POST, 'gmo_token');
            $this->PaymentGMOCreditCard->set($credit_data);

            // update
            $res = $this->PaymentGMOCreditCard->apiPut($this->PaymentGMOCreditCard->toArray());

            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'edit']);
            }

            if ($returnTo === 'purchase') {
                $sales_id = Hash::get(CakeSession::read('PaymentGMOPurchase'), 'sales_id');
                return $this->redirect([
                    'controller' => 'purchase', 'action' => 'input', 'customer' => false
                    , 'id' => $sales_id, '?' => ['back' => 'true']
                ]);
            }

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

            $credit_data[self::MODEL_NAME_CREDIT_CARD]['gmo_token'] = filter_input(INPUT_POST, 'gmo_token');
            $this->PaymentGMOCreditCard->set($credit_data);

            // update
            $res = $this->PaymentGMOCreditCard->apiPut($this->PaymentGMOCreditCard->toArray());

            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->render('customer_edit');
            }

            return $this->redirect(['controller' => 'login', 'action' => 'logout', 'paymentng' => false]);
        }
    }

    /**
     * 債務ユーザー(アマゾンペイメント)
     */
    public function paymentng_edit_amazon_pay()
    {
        return $this->render('customer_edit_amazon_pay');
    }
}
