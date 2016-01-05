<?php

App::uses('AppController', 'Controller');
App::uses('UserAddress', 'Model');

class ContactUsController extends AppController
{
    /**
     * 制御前段処理
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
    }

    /**
     * ルートインデックス.
     */
    public function add()
    {
    }

    /**
     * 
     */
    public function confirm()
    {
    }

    /**
     * 
     */
    public function complete()
    {
    }
}
