<?php

App::uses('AppController', 'Controller');

class CreditCardController extends AppController
{
    const MODEL_NAME = 'PaymentGMOSecurityCard';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel($this::MODEL_NAME);
    }

    /**
     * 入力
     */
    public function customer_edit()
    {
        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = CakeSession::read($this::MODEL_NAME);
        }
        CakeSession::delete($this::MODEL_NAME);
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

            $this->set('security_card', $this->PaymentGMOSecurityCard->data[$this::MODEL_NAME]);
            CakeSession::write($this::MODEL_NAME, $this->PaymentGMOSecurityCard->data);
        } else {
            return $this->render('customer_edit');
        }
    }

    /**
     * 完了
     */
    public function customer_complete()
    {
        $session_data = CakeSession::read($this::MODEL_NAME);
        CakeSession::delete($this::MODEL_NAME);

        if (empty($session_data)) {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'edit']);
        }

        $this->PaymentGMOSecurityCard->set($session_data);
        if ($this->PaymentGMOSecurityCard->validates()) {
            // api
            $this->PaymentGMOSecurityCard->apiPut($this->PaymentGMOSecurityCard->toArray());
        } else {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'edit']);
        }
    }
}
