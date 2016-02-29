<?php

App::uses('MinikuraController', 'Controller');

class ContractController extends MinikuraController
{
    const MODEL_NAME = 'CustomerInfo';
    const MODEL_NAME_CORPORATE = 'CorporateInfo';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    /**
     *
     */
    public function index()
    {
        if ($this->Customer->isPrivateCustomer()) {
            // 個人
            $this->loadModel(self::MODEL_NAME);
            $r = $this->CustomerInfo->apiGet();
            if ($r->error_message) {
                return;
            }
            $data = $r->results;
        } else {
            // 法人
            $this->loadModel(self::MODEL_NAME_CORPORATE);
            $r = $this->CorporateInfo->apiGet();
            if ($r->error_message) {
                return;
            }
            $data = $r->results;
        }

        $this->set('data', $data[0]);
    }
}
