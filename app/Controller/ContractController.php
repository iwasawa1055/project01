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
            $r = $this->CustomerInfo->apiGet();
            if ($r->error_message) {
                return;
            }
            $data = $r->results;
        } else {
            // 法人
            $this->loadModel($this::MODEL_NAME_CORPORATE);
            $r = $this->CorporateInfo->apiGet();
            if ($r->error_message) {
                return;
            }
            $data = $r->results;
        }

        $this->set('data', $data[0]);
    }
}
