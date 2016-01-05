<?php

App::uses('AppController', 'Controller');

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
            return $this->redirect('/mypage/index');
        } else {
            $this->set('validerror', $this->UserLogin->validationErrors);

            return $this->render('/login/index');
        }
    }

    public function logout()
    {
        $this->loadModel('UserLogin');
        $this->UserLogin->logout();

        return $this->redirect('/login');
    }
}
