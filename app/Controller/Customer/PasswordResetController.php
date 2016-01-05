<?php

App::uses('AppController', 'Controller');

class PasswordResetController extends AppController
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
    public function add()
    {
        $this->render('/Customer/PasswordReset/add');
    }

    /**
     * 
     */
    public function confirm()
    {
        $this->render('/Customer/PasswordReset/confirm');
    }

    /**
     * 
     */
    public function complete()
    {
        $this->render('/Customer/PasswordReset/complete');
    }
}
