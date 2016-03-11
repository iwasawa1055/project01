<?php

App::uses('MinikuraController', 'Controller');
App::uses('AppMail', 'Lib');


class PasswordResetController extends MinikuraController
{
    const MODEL_NAME = 'CustomerPasswordReset';

    // ログイン不要なページ
    protected $checkLogined = false;

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
    }

    /**
     *
     */
    public function customer_index()
    {
        if ($this->request->is('post')) {
            $this->CustomerPasswordReset->set($this->request->data);
            if ($this->CustomerPasswordReset->validates(['fieldList' => ['email']])) {

                CakeSession::renew();

                $mail = new AppMail();
                $id = CakeSession::id();
                $to = $this->CustomerPasswordReset->toArray()['email'];
                $mail->sendPasswordReset($to, $id);

                CakeSession::write(self::MODEL_NAME, $this->CustomerPasswordReset->data);

                $this->Flash->set('送信しました。');
                return $this->redirect(['action' => 'customer_index']);
            } else {
                return $this->render('customer_index');
            }
        }
    }


    /**
     *
     */
    public function customer_add()
    {
        $hash = Hash::get($this->request->query, 'hash');
        if ($hash) {
            // セッション復元
            $newid = CakeSession::id();
            CakeSession::id($hash);
            session_reset();

            $this->request->data = CakeSession::read(self::MODEL_NAME);
            // $this->request->data[self::MODEL_NAME]['new_password'] = '';
            // $this->request->data[self::MODEL_NAME]['new_password_confirm'] = '';
            CakeSession::destroy();

            // 既存セッションに戻す
            CakeSession::id($newid);
            session_reset();
            CakeSession::write(self::MODEL_NAME, $this->request->data);
        }

        $this->request->data = CakeSession::read(self::MODEL_NAME);
        // $this->request->data[self::MODEL_NAME]['new_password'] = '';
        // $this->request->data[self::MODEL_NAME]['new_password_confirm'] = '';

        $this->CustomerPasswordReset->set($this->request->data);
        if (empty($this->CustomerPasswordReset->data) ||
            !$this->CustomerPasswordReset->validates(['fieldList' => ['email']])) {

            $this->Flash->set('無効なコードです。もう一度最初からやってください。');
            return $this->redirect(['action' => 'customer_index']);
        }

        CakeSession::write(self::MODEL_NAME, $this->CustomerPasswordReset->data);
    }

    /**
     *
     */
    public function customer_confirm()
    {
        // メールアドレスを上書き
        $data = CakeSession::read(self::MODEL_NAME);
        $email = $data['CustomerPasswordReset']['email'];
        $this->request->data['CustomerPasswordReset']['email'] = $email;
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
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }

        $this->CustomerPasswordReset->set($data);
        if ($this->CustomerPasswordReset->validates()) {
            // api
            $this->CustomerPasswordReset->apiPut($this->CustomerPasswordReset->toArray());
            $this->set('email', $this->CustomerPasswordReset->toArray()['email']);
        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }
    }
}
