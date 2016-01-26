<?php

App::uses('AppController', 'Controller');
App::uses('UserAddress', 'Model');

class InquiryController extends AppController
{
    /**
     * 制御前段処理
     */
    public function beforeFilter()
    {
        // ログイン不要なページ
        $this->checkLogined = false;
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
