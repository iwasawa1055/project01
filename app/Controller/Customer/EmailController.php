<?php

App::uses('AppController', 'Controller');

class EmailController extends AppController
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
    public function edit()
    {
        $this->render('/Customer/Email/edit');
    }

    /**
     * 
     */
    public function confirm()
    {
        $this->render('/Customer/Email/confirm');
    }

    /**
     * 
     */
    public function complete()
    {
        $this->render('/Customer/Email/complete');
    }
}
