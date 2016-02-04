<?php

App::uses('AppController', 'Controller');

class CreditCardController extends AppController
{
    const MODEL_NAME_SECURITY = 'PaymentGMOSecurityCard';
    const MODEL_NAME_CARD = 'PaymentGMOCard';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel($this::MODEL_NAME_SECURITY);
        $this->loadModel($this::MODEL_NAME_CARD);
    }

    /**
     * 入力
     */
    public function customer_edit()
    {
        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = CakeSession::read($this::MODEL_NAME_SECURITY);
        } else {
            $default_payment = $this->PaymentGMOCard->apiGetDefaultCard();
            $this->request->data[$this::MODEL_NAME_SECURITY] = $default_payment;
        }
        CakeSession::delete($this::MODEL_NAME_SECURITY);
    }

    /**
     * 確認
     */
    public function customer_confirm()
    {
        $this->PaymentGMOSecurityCard->set($this->request->data);

        // Expire
        $this->PaymentGMOSecurityCard->setExpire($this->request->data);
        // ハイフン削除
        $this->PaymentGMOSecurityCard->trimHyphenCardNo($this->request->data);

        if ($this->PaymentGMOSecurityCard->validates()) {
            // Expire year 表示用
            $this->PaymentGMOSecurityCard->setDisplayExpire($this->request->data);

            $this->set('security_card', $this->PaymentGMOSecurityCard->data[$this::MODEL_NAME_SECURITY]);
            CakeSession::write($this::MODEL_NAME_SECURITY, $this->PaymentGMOSecurityCard->data);
        } else {
            return $this->render('customer_edit');
        }
    }

    /**
     * 完了
     */
    public function customer_complete()
    {
        $session_data = CakeSession::read($this::MODEL_NAME_SECURITY);
        CakeSession::delete($this::MODEL_NAME_SECURITY);

        if (empty($session_data)) {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'edit']);
        }

        $this->PaymentGMOSecurityCard->set($session_data);
        if ($this->PaymentGMOSecurityCard->validates()) {
            // api
            $res = $this->PaymentGMOSecurityCard->apiPut($this->PaymentGMOSecurityCard->toArray());
            if (!empty($res->error_message)) {
                // TODO: 例外処理
                $this->Session->setFlash($res->error_message);
                return $this->redirect(['action' => 'edit']);
            }
        } else {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'edit']);
        }
    }
}
