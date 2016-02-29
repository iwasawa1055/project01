<?php

App::uses('MinikuraController', 'Controller');

class PasswordResetController extends MinikuraController
{
    const MODEL_NAME = 'CustomerPasswordReset';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        // ログイン不要なページ
        $this->checkLogined = false;
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
    }

    /**
     *
     */
    public function customer_add()
    {
        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = CakeSession::read(self::MODEL_NAME);
            $this->request->data[self::MODEL_NAME]['new_password'] = '';
            $this->request->data[self::MODEL_NAME]['new_password_confirm'] = '';
        }
        CakeSession::delete(self::MODEL_NAME);
    }

    /**
     *
     */
    public function customer_confirm()
    {
        $this->CustomerPasswordReset->set($this->request->data);
        if ($this->CustomerPasswordReset->validates()) {
            CakeSession::write(self::MODEL_NAME, $this->CustomerPasswordReset->data);
        } else {
            return $this->render('customer_add');
        }
    }

    /**
     *
     */
    public function customer_complete()
    {
        $data = CakeSession::read(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME);
        if (empty($data)) {
            // TODO:
            $this->Flash->set('try again');
            return $this->redirect(['action' => 'add']);
        }

        $this->CustomerPasswordReset->set($data);
        if ($this->CustomerPasswordReset->validates()) {
            // api
            $this->CustomerPasswordReset->apiPut($this->CustomerPasswordReset->toArray());
            $this->set('email', $this->CustomerPasswordReset->toArray()['email']);
        } else {
            // TODO:
            $this->Flash->set('try again');
            return $this->redirect(['action' => 'add']);
        }
    }
}
