<?php

App::uses('AppController', 'Controller');
App::uses('ApiCachedModel', 'Model');
App::uses('OutboundList', 'Model');

class LoginController extends AppController
{
    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        // ログイン不要なページ
        $this->checkLogined = false;
        parent::beforeFilter();
    }

    /**
     * ルートインデックス.
     */
    public function index()
    {
        if ($this->request->is('post')) {
            $this->loadModel('CustomerLogin');
            $this->CustomerLogin->set($this->request->data);

            if ($this->CustomerLogin->validates()) {

                $res = $this->CustomerLogin->login();
                if (!empty($res->error_message)) {
                    // TODO: 例外処理
                    $this->request->data['CustomerLogin']['password'] = '';
                    $this->Session->setFlash($res->error_message);
                    return $this->render('index');
                }

                // TODO: 債務ユーザーの場合
                // TODO: カスタマー情報を取得しセッションに保存する
                return $this->redirect('/');

            } else {
                $this->request->data['CustomerLogin']['password'] = '';
                return $this->render('index');
            }
        }
    }

    public function logout()
    {
        $this->loadModel('CustomerLogin');
        $this->CustomerLogin->logout();

        // セッション値をクリア
        ApiCachedModel::deleteAllCache();
        OutboundList::delete();

        return $this->redirect('/login');
    }
}
