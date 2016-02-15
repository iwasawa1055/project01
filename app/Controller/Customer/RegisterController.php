<?php

App::uses('AppController', 'Controller');

class RegisterController extends AppController
{
    const MODEL_NAME = 'CustomerEntry';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        // ログイン不要なページ
        $this->checkLogined = false;
        AppController::beforeFilter();
    }

    /**
     * 
     */
    public function customer_add()
    {
        if ($this->request->is('post')) {
            $this->loadModel($this::MODEL_NAME);
            $this->CustomerEntry->set($this->request->data);

            if ($this->CustomerEntry->validates()) {
                // 仮登録
                $res = $this->CustomerEntry->entry();
                if (!empty($res->error_message)) {
                    // TODO: 例外処理
                    $this->request->data[$this::MODEL_NAME]['password'] = '';
                    $this->request->data[$this::MODEL_NAME]['password_confirm'] = '';
                    $this->Session->setFlash($res->error_message);
                    return $this->render('customer_add');
                }

                // TODO: ログイン
                $this->loadModel('CustomerLogin');
                $this->CustomerLogin->data['CustomerLogin']['email'] = $this->request->data[$this::MODEL_NAME]['email'];
                $this->CustomerLogin->data['CustomerLogin']['password'] = $this->request->data[$this::MODEL_NAME]['password'];

                $res = $this->CustomerLogin->login();
                if (!empty($res->error_message)) {
                    $this->Session->setFlash($res->error_message);
                    return $this->render('customer_add');
                }

                // カスタマー情報を取得しセッションに保存
                // token
                $this->customer->setTokenAndSave($res->results[0]);
                // entry
                $res = $this->CustomerEntry->apiGet();
                $this->customer->setInfoAndSave($res->results[0]);

                return $this->redirect('/');

            } else {
                $this->request->data[$this::MODEL_NAME]['password'] = '';
                $this->request->data[$this::MODEL_NAME]['password_confirm'] = '';
                return $this->render('customer_add');
            }

        }
    }
}
