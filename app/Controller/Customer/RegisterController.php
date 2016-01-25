<?php

App::uses('AppController', 'Controller');

class RegisterController extends AppController
{
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
    public function add()
    {
    }

    /**
     * 
     */
    public function create()
    {
        $this->redirect('/mypage');
    }
}
