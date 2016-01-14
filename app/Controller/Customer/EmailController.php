<?php

App::uses('AppController', 'Controller');

class EmailController extends AppController
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
        if (! empty(CakeSession::read('CustomerEmail'))) {
            CakeSession::delete('CustomerEmail');
        }
    }

    /**
     * 確認
     */
    public function confirm()
    {
        $this->loadModel('CustomerEmail');
        $this->CustomerEmail->set($this->request->data);

        if ($this->CustomerEmail->validates()) {
            $this->set('customer_email', $this->CustomerEmail->data['CustomerEmail']);
            CakeSession::write('CustomerEmail', $this->CustomerEmail->data);
        } else {
            return $this->render('edit');
        }
    }

    /**
     * 完了
     */
    public function complete()
    {
        $session_data = CakeSession::read('CustomerEmail');
        if (empty($session_data)) {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect('/customer/email/edit/');
        }

        $this->loadModel('CustomerEmail');
        $this->CustomerEmail->set($session_data);

        if ($this->CustomerEmail->validates()) {
            // api
            $res = $this->CustomerEmail->apiPatch($this->CustomerEmail->data);

            CakeSession::delete('CustomerEmail');
            $this->set('customer_email', $this->CustomerEmail->data['CustomerEmail']);

            if ($res->status !== '1') {
                // TODO:
                $this->Session->setFlash('try again');
                return $this->redirect('/customer/email/edit/');
            }
        } else {
            // TODO:
            $this->Session->setFlash('try again');
            CakeSession::delete('CustomerEmail');
            return $this->redirect('/customer/email/edit/');
        }
    }
}
