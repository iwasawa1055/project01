<?php

App::uses('AppController', 'Controller');

class EmailController extends AppController
{
    const MODEL_NAME = 'CustomerEmail';

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
    public function edit()
    {
        if (! empty(CakeSession::read($this::MODEL_NAME))) {
            CakeSession::delete($this::MODEL_NAME);
        }
    }

    /**
     * 確認
     */
    public function confirm()
    {
        $this->CustomerEmail->set($this->request->data);
        if ($this->CustomerEmail->validates()) {
            $this->set('customer_email', $this->CustomerEmail->data[$this::MODEL_NAME]);
            CakeSession::write($this::MODEL_NAME, $this->CustomerEmail->data);
        } else {
            return $this->render('edit');
        }
    }

    /**
     * 完了
     */
    public function complete()
    {
        $data = CakeSession::read($this::MODEL_NAME);
        CakeSession::delete($this::MODEL_NAME);
        if (empty($data)) {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'edit']);
        }

        $this->CustomerEmail->set($data);
        if ($this->CustomerEmail->validates()) {
            // api
            $res = $this->CustomerEmail->apiPatch($this->CustomerEmail->data);
            if (!$res->isSuccess()) {
                // TODO:
                $this->Session->setFlash('try again');
                return $this->redirect(['action' => 'edit']);
            }
            // complete.ctp echo $email
            $this->set('email', $this->CustomerEmail->toArray()['email']);
        } else {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'edit']);
        }
    }
}
