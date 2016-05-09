<?php

App::uses('MinikuraController', 'Controller');

class ContractController extends MinikuraController
{
    const MODEL_NAME = 'PointBalance';

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

        // ポイント取得
        $point = [];
        $this->loadModel(self::MODEL_NAME);
        $res = $this->PointBalance->apiGet();
        if (!empty($res->error_message)) {
            $this->Flash->set($res->error_message);
        } else {
            $point = $res->results[0];
        }
        $this->set('point', $point);
    }
}
