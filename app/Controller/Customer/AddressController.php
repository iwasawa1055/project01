<?php

App::uses('AppController', 'Controller');

class AddressController extends AppController
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
    }

    /**
     * 
     */
    public function edit()
    {
    }

    /**
     * 
     */
    public function delete()
    {
        $this->render('/Customer/Address/confirm');
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
