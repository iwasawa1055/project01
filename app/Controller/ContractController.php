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
        $data = [];
        $model = $this->Customer->getInfoGetModel();
        $res = $model->apiGet();
        if (!empty($res->error_message)) {
            $this->Flash->set($res->error_message);
        } else {
            $data = $res->results[0];
        }
        $this->set('data', $data);
    }
}
