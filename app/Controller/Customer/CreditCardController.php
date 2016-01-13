<?php

App::uses('AppController', 'Controller');

class CreditCardController extends AppController
{
    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
    }

    /**
     * 入力
     */
    public function edit()
    {
        if (! empty(CakeSession::read('PaymentGMOSecurityCard'))) {
            CakeSession::delete('PaymentGMOSecurityCard');
        }
    }

    /**
     * 確認
     */
    public function confirm()
    {
        $this->loadModel('PaymentGMOSecurityCard');
        $this->PaymentGMOSecurityCard->set($this->request->data);

        // Expire
        $this->PaymentGMOSecurityCard->setExpire($this->request->data);
        // ハイフン削除
        $this->PaymentGMOSecurityCard->trimHyphenCardNo($this->request->data);

        if ($this->PaymentGMOSecurityCard->validates()) {
            $this->PaymentGMOSecurityCard->data['PaymentGMOSecurityCard']['expire_year_disp'] = $this->request->data['expire_year'] + 2000;

            $this->set('security_card', $this->PaymentGMOSecurityCard->data['PaymentGMOSecurityCard']);
            CakeSession::write('PaymentGMOSecurityCard', $this->PaymentGMOSecurityCard->data);
        } else {
            return $this->render('edit');
        }
    }

    /**
     * 完了
     */
    public function complete()
    {
        $session_data = CakeSession::read('PaymentGMOSecurityCard');
        if (empty($session_data)) {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect('/customer/credit_card/edit/');
        }

        $this->loadModel('PaymentGMOSecurityCard');
        $this->PaymentGMOSecurityCard->set($session_data);

        if ($this->PaymentGMOSecurityCard->validates()) {
            // api
            $res = $this->PaymentGMOSecurityCard->apiPut($this->PaymentGMOSecurityCard->data);

            CakeSession::delete('PaymentGMOSecurityCard');

            if ($res->status !== '1') {
                // TODO:
                $this->Session->setFlash('try again');
                return $this->redirect('/customer/credit_card/edit/');
            }
        } else {
            // TODO:
            $this->Session->setFlash('try again');
            CakeSession::delete('PaymentGMOSecurityCard');
            return $this->redirect('/customer/credit_card/edit/');
        }
    }
}
