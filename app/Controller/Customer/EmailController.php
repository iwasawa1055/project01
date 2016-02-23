<?php

App::uses('AppController', 'Controller');

class EmailController extends AppController
{
    const MODEL_NAME = 'CustomerEmail';
    const MODEL_NAME_INFO = 'CustomerInfo';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
        $this->loadModel(self::MODEL_NAME_INFO);

        $this->set('current_email', $this->customer->info['email']);
    }

    /**
     * 入力
     */
    public function customer_edit()
    {
        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = CakeSession::read(self::MODEL_NAME);
        }
        CakeSession::delete(self::MODEL_NAME);
    }

    /**
     * 確認
     */
    public function customer_confirm()
    {
        $this->CustomerEmail->set($this->request->data);
        if ($this->CustomerEmail->validates()) {
            CakeSession::write(self::MODEL_NAME, $this->CustomerEmail->data);
        } else {
            return $this->render('customer_edit');
        }
    }

    /**
     * 完了
     */
    public function customer_complete()
    {
        $data = CakeSession::read(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME);
        if (empty($data)) {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'edit']);
        }

        $this->CustomerEmail->set($data);
        if ($this->CustomerEmail->validates()) {
            // api
            $res = $this->CustomerEmail->apiPatch($this->CustomerEmail->toArray());
            if (!empty($res->error_message)) {
                // TODO:
                $this->Session->setFlash('try again');
                return $this->redirect(['action' => 'edit']);
            }

            $res = $this->CustomerInfo->apiGet();
            $this->customer->setInfoAndSave($res->results[0]);
            $this->set('email', $this->CustomerEmail->toArray()['email']);
        } else {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'edit']);
        }
    }
}
