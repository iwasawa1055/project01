<?php

App::uses('AppController', 'Controller');

class ContractController extends AppController
{
    const MODEL_NAME = 'Customer';

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
        $data = $this->Customer->apiGetResults();

        $this->set('data', $data[0]);
    }
}
