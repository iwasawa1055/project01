<?php

App::uses('AppController', 'Controller');
App::uses('ApiCachedModel', 'Model');

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
    }

    /**
     * ログイン.
     */
    public function doing()
    {
        $this->loadModel('CustomerLogin');
        $this->CustomerLogin->set($this->request->data);

        if ($this->CustomerLogin->validates()) {
            $res = $this->CustomerLogin->login();
            // TODO: 例外処理
            // TODO: カスタマー情報を取得しセッションに保存する
            return $this->redirect('/mypage');

        } else {
            $this->set('validerror', $this->CustomerLogin->validationErrors);
// pr($this->CustomerLogin->validationErrors);

            return $this->render('/login');
        }
    }

    public function logout()
    {
        $this->loadModel('CustomerLogin');
        $this->CustomerLogin->logout();

        // セッション値をクリア
        ApiCachedModel::deleteAllCache();

        return $this->redirect('/login');
    }
}
