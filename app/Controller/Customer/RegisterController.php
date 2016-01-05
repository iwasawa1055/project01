<?php

App::uses('AppController', 'Controller');
App::uses('UserAddress', 'Model');

class RegisterController extends AppController
{
    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
    }

    /**
     * 
     */
    public function index()
    {
        return $this->redirect('/mypage');
    }

}
