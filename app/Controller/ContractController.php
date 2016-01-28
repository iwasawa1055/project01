<?php

App::uses('AppController', 'Controller');

class ContractController extends AppController
{
    const MODEL_NAME = 'CustomerInfo';

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
        // TODO: 法人だった場合の分岐
        $this->loadModel($this::MODEL_NAME);
        $data = $this->CustomerInfo->apiGetResults();

        $this->set('data', $data[0]);
    }
}
