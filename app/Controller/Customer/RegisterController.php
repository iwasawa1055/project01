<?php

App::uses('MinikuraController', 'Controller');

class RegisterController extends MinikuraController
{
    const MODEL_NAME = 'CustomerEntry';

    // ログイン不要なページ
    protected $checkLogined = false;

    /**
     *
     */
    public function customer_add()
    {
        // 紹介コード
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);

        if ($this->request->is('post')) {
            $this->loadModel(self::MODEL_NAME);
            $this->CustomerEntry->set($this->request->data);

            // 紹介コード
            if (!empty($code)) {
                $this->CustomerEntry->data[self::MODEL_NAME]['alliance_cd'] = $code;
            }

            if ($this->CustomerEntry->validates()) {
                // 仮登録
                $res = $this->CustomerEntry->entry();
                if (!empty($res->error_message)) {
                    $this->request->data[self::MODEL_NAME]['password'] = '';
                    $this->request->data[self::MODEL_NAME]['password_confirm'] = '';
                    $this->Flash->set($res->error_message);
                    return $this->render('customer_add');
                }

                // ログイン
                $this->loadModel('CustomerLogin');
                $this->CustomerLogin->data['CustomerLogin']['email'] = $this->request->data[self::MODEL_NAME]['email'];
                $this->CustomerLogin->data['CustomerLogin']['password'] = $this->request->data[self::MODEL_NAME]['password'];

                $res = $this->CustomerLogin->login();
                if (!empty($res->error_message)) {
                    $this->Flash->set($res->error_message);
                    return $this->render('customer_add');
                }

                // カスタマー情報を取得しセッションに保存
                $this->Customer->setTokenAndSave($res->results[0]);
                $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);
                $this->Customer->getInfo();

                if (empty($code)) {
                    return $this->redirect('/');
                } else {
                    // 紹介コードがある場合はキット購入へ遷移
                    return $this->redirect(['controller' => 'order', 'action' => 'add', 'customer' => false]);
                }

            } else {
                $this->request->data[self::MODEL_NAME]['password'] = '';
                $this->request->data[self::MODEL_NAME]['password_confirm'] = '';
                return $this->render('customer_add');
            }

        }
    }
}
