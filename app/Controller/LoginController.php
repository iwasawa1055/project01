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
        $this->loadModel('UserLogin');
        $this->UserLogin->set($this->request->data);

        if ($this->UserLogin->validates()) {
            $res = $this->UserLogin->login();
            // TODO: 例外処理
            // TODO: カスタマー情報を取得しセッションに保存する
            return $this->redirect('/mypage');

        } else {
            $this->set('validerror', $this->UserLogin->validationErrors);

            return $this->render('/login');
        }
    }

    public function logout()
    {
        $this->loadModel('UserLogin');
        $this->UserLogin->logout();

        // セッション値をクリア
        ApiCachedModel::deleteAllCache();

        return $this->redirect('/login');
    }
}
