<?php

App::uses('AppController', 'Controller');

class OrderController extends AppController
{
    const MODEL_NAME = 'PaymentGMOKitCard';
    const MODEL_NAME_CARD = 'PaymentGMOCard';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel($this::MODEL_NAME);
        $this->loadModel($this::MODEL_NAME_CARD);
    }

    /**
     *
     */
    public function add()
    {
        $res = $this->PaymentGMOCard->apiGet();
        if ($res->isSuccess()) {
            $this->set('payments', $res->results['contents']);
pr($res->results);
pr($res->results['contents']);
        } else {
            // TODO
        }
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
