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
        $this->render('/Customer/Address/add');
    }

    /**
     * 
     */
    public function edit()
    {
        $this->render('/Customer/Address/edit');
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
        $this->render('/Customer/Address/confirm');
    }

    /**
     * 
     */
    public function complete()
    {
        $this->render('/Customer/Address/complete');
    }
}
