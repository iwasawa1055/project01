<?php

App::uses('AppController', 'Controller');
App::uses('UserAddress', 'Model');

class InfoController extends AppController
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
        $this->render('/Customer/Info/edit');
    }

    /**
     * 
     */
    public function confirm()
    {
        $this->render('/Customer/Info/confirm');
    }

    /**
     * 
     */
    public function complete()
    {
        $this->render('/Customer/Info/complete');
    }
}
