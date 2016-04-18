<?php

App::uses('MinikuraController', 'Controller');

class RegisterController extends MinikuraController
{
    const MODEL_NAME = 'CustomerEntry';
    const MODEL_NAME_REGIST = 'CustomerRegistInfo';

    // ログイン不要なページ
    protected $checkLogined = false;

    /**
     * アクセス拒否.
     */
    protected function isAccessDeny()
    {
        if ($this->Customer->isLogined()) {
            return true;
        }

        return false;
    }

    /**
     *
     */
    public function customer_add()
    {
        // 紹介コード
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);
        $this->request->data[self::MODEL_NAME]['alliance_cd'] = $code;

        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = [self::MODEL_NAME => CakeSession::read(self::MODEL_NAME)];
            $this->request->data[self::MODEL_NAME]['password'] = '';
            $this->request->data[self::MODEL_NAME]['password_confirm'] = '';
        }
        CakeSession::delete(self::MODEL_NAME);
    }

    /**
     * 
     */
    public function customer_confirm()
    {
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);

        $this->loadModel(self::MODEL_NAME);
        $this->CustomerEntry->set($this->request->data);

        if ($this->CustomerEntry->validates()) {
            CakeSession::write(self::MODEL_NAME, $this->CustomerEntry->toArray());
        } else {
            return $this->render('customer_add');
        }
    }

    /**
     *
     */
    public function customer_complete()
    {
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);

        $data = CakeSession::read(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME);
        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'customer_add', '?' => ['code' => $code]]);
        }

        $this->loadModel(self::MODEL_NAME);
        $this->CustomerEntry->set($data);

        if ($this->CustomerEntry->validates()) {
            // 仮登録
            $res = $this->CustomerEntry->entry();
            if (!empty($res->error_message)) {
                $this->CustomerEntry->data[self::MODEL_NAME]['password'] = '';
                $this->CustomerEntry->data[self::MODEL_NAME]['password_confirm'] = '';
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'customer_add', '?' => ['code' => $code]]);
            }

            // ログイン
            $this->loadModel('CustomerLogin');
            $this->CustomerLogin->data['CustomerLogin']['email'] = $this->CustomerEntry->data[self::MODEL_NAME]['email'];
            $this->CustomerLogin->data['CustomerLogin']['password'] = $this->CustomerEntry->data[self::MODEL_NAME]['password'];

            $res = $this->CustomerLogin->login();
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->render('customer_add');
            }

            // カスタマー情報を取得しセッションに保存
            $this->Customer->setTokenAndSave($res->results[0]);
            $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);
            $this->Customer->getInfo();

            // 完了画面
            $this->set('alliance_cd', $this->CustomerEntry->data[self::MODEL_NAME]['alliance_cd']);
            return $this->render('customer_complete');

        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'customer_add', '?' => ['code' => $code]]);
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
        $this->request->data[self::MODEL_NAME_REGIST]['alliance_cd'] = $code;

        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = [self::MODEL_NAME_REGIST => CakeSession::read(self::MODEL_NAME_REGIST)];
            $this->request->data[self::MODEL_NAME_REGIST]['password'] = '';
            $this->request->data[self::MODEL_NAME_REGIST]['password_confirm'] = '';
        }
        CakeSession::delete(self::MODEL_NAME_REGIST);
    }

    /**
     * 
     */
    public function customer_confirm_info()
    {
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);

        $this->loadModel(self::MODEL_NAME_REGIST);

        $data = $this->request->data[self::MODEL_NAME_REGIST];
        $birth = [];
        $birth[0] = $data['birth_year'];
        $birth[1] = $data['birth_month'];
        $birth[2] = $data['birth_day'];
        $data['birth'] = implode('-', $birth);
        $this->CustomerRegistInfo->set($data);

        if ($this->CustomerRegistInfo->validates()) {
            CakeSession::write(self::MODEL_NAME_REGIST, $this->CustomerRegistInfo->toArray());
        } else {
            return $this->render('customer_add_info');
        }
    }

    /**
     *
     */
    public function customer_complete_info()
    {
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);

        $data = CakeSession::read(self::MODEL_NAME_REGIST);
        CakeSession::delete(self::MODEL_NAME_REGIST);
        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'customer_add_info', '?' => ['code' => $code]]);
        }

        $this->loadModel(self::MODEL_NAME_REGIST);
        $this->CustomerRegistInfo->set($data);

        if ($this->CustomerRegistInfo->validates()) {
            // 部屋番号
            $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['address3'] = $data['address3'] . $data['room'];

            if (empty($this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['alliance_cd'])) {
                unset($this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['alliance_cd']);
            }

            // 本登録
            $res = $this->CustomerRegistInfo->regist();
            if (!empty($res->error_message)) {
                $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['password'] = '';
                $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['password_confirm'] = '';
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'customer_add_info', '?' => ['code' => $code]]);
            }

            // ログイン
            $this->loadModel('CustomerLogin');
            $this->CustomerLogin->data['CustomerLogin']['email'] = $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['email'];
            $this->CustomerLogin->data['CustomerLogin']['password'] = $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['password'];

            $res = $this->CustomerLogin->login();
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->render('customer_add_info');
            }

            // カスタマー情報を取得しセッションに保存
            $this->Customer->setTokenAndSave($res->results[0]);
            $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);
            $this->Customer->getInfo();

            // 完了画面
            $this->set('alliance_cd', Hash::get($this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST], 'alliance_cd'));
            return $this->render('customer_complete');

        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'customer_add_info', '?' => ['code' => $code]]);
        }
    }
}
