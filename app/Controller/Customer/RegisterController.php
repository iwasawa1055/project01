<?php

App::uses('MinikuraController', 'Controller');

class RegisterController extends MinikuraController
{
    const MODEL_NAME = 'CustomerEntry';
    const MODEL_NAME_REGIST = 'CustomerRegistInfo';

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

                if (empty($this->CustomerEntry->data[self::MODEL_NAME]['alliance_cd'])) {
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

    /**
     *
     */
    public function customer_add_info()
    {
        // 紹介コード
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);

        if ($this->request->is('post')) {
            $this->loadModel(self::MODEL_NAME_REGIST);
            $data = $this->request->data[self::MODEL_NAME_REGIST];

            $birth = [];
            $birth[0] = $data['birth_year'];
            $birth[1] = $data['birth_month'];
            $birth[2] = $data['birth_day'];
            $data['birth'] = implode('-', $birth);

            $this->CustomerRegistInfo->set($data);

            // 紹介コード
            if (!empty($code)) {
                $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['alliance_cd'] = $code;
            }

            if (empty($this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['alliance_cd'])) {
                unset($this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['alliance_cd']);
            }

            if ($this->CustomerRegistInfo->validates()) {
                // 部屋番号
                $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['address3'] = $data['address3'] . $data['room'];

                // 本登録
                $res = $this->CustomerRegistInfo->regist();
                if (!empty($res->error_message)) {
                    $this->request->data[self::MODEL_NAME_REGIST]['password'] = '';
                    $this->request->data[self::MODEL_NAME_REGIST]['password_confirm'] = '';
                    $this->Flash->set($res->error_message);
                    return $this->render('customer_add_info');
                }
                
                // ログイン
                $this->loadModel('CustomerLogin');
                $this->CustomerLogin->data['CustomerLogin']['email'] = $this->request->data[self::MODEL_NAME_REGIST]['email'];
                $this->CustomerLogin->data['CustomerLogin']['password'] = $this->request->data[self::MODEL_NAME_REGIST]['password'];
                
                $res = $this->CustomerLogin->login();
                if (!empty($res->error_message)) {
                    $this->Flash->set($res->error_message);
                    return $this->render('customer_add_info');
                }
                
                // カスタマー情報を取得しセッションに保存
                $this->Customer->setTokenAndSave($res->results[0]);
                $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);
                $this->Customer->getInfo();
                
                if (isset($this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['alliance_cd'])) {
                    // 紹介コードがある場合はキット購入へ遷移
                    return $this->redirect(['controller' => 'order', 'action' => 'add', 'customer' => false]);
                } else {
                    return $this->redirect('/');
                }
            } else {
                $this->request->data[self::MODEL_NAME_REGIST]['password'] = '';
                $this->request->data[self::MODEL_NAME_REGIST]['password_confirm'] = '';
                return $this->render('customer_add_info');
            }
        }
    }
}
