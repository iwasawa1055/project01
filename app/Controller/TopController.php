<?php

App::uses('AppController', 'Controller');
App::uses('UserLogin', 'Model');

class TopController extends AppController
{
    public $test = null;

    /**
     * 制御前段処理.
     *
     * @todo		test
     */
    public function beforeFilter()
    {
        // AppController::beforeFilter();
        //* Test Double
        // $this->test = Configure::read('app.test');
    }

    /**
     * ルートインデックス.
     *
     * @todo
     */
    public function index()
    {

        // ログイン
        $email = '73@terrada.co.jp';
        $password = 'happyhappy';

        $this->loadModel('UserLogin');
        $this->UserLogin->set([
          'email' => $email,
          'password' => $password,
        ]);
        // $errors = $this->UserLogin->validationErrors;
        // print_rh($errors);
        $this->UserLogin->login();

        // 照会系API
        $this->loadModel('User');
        print_rh($this->User->apiGet());

        $this->loadModel('Announcement');
        print_rh($this->Announcement->apiGet());

    }
}
