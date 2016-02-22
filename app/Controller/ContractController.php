<?php

App::uses('AppController', 'Controller');

class ContractController extends AppController
{
    const MODEL_NAME = 'CustomerInfo';
    const MODEL_NAME_CORPORATE = 'CorporateInfo';

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
        if ($this->customer->isPrivateCustomer()) {
            // 個人
            $this->loadModel($this::MODEL_NAME);
            $data = $this->CustomerInfo->apiGetResults();
        } else {
            // 法人
            $this->loadModel($this::MODEL_NAME_CORPORATE);
            $data = $this->CorporateInfo->apiGetResults();
        }

        $this->set('data', $data[0]);
    }
}
