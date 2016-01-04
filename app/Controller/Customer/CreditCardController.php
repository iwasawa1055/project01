<?php

App::uses('AppController', 'Controller');

class CreditCardController extends AppController
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
        $this->render('/Customer/CreditCard/edit');
    }

    /**
     * 
     */
    public function confirm()
    {
        $this->render('/Customer/CreditCard/confirm');
    }

    /**
     * 
     */
    public function complete()
    {
        $this->render('/Customer/CreditCard/complete');
    }
}
