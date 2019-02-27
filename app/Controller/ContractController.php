<?php

App::uses('MinikuraController', 'Controller');

class ContractController extends MinikuraController
{
    /** model */
    const MODEL_NAME_REGIST = 'CustomerRegistInfo';

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

        // SNS連携
        $data['facebook_flg'] = false;
        if ($this->Customer->isFacebook()) {
            $data['facebook_flg'] = true;
        }

        $this->set('data', $data);
    }

    /**
     * SNS連携
     */
    public function register_facebook()
    {

        // facebook情報
        $data = $this->request->data[self::MODEL_NAME_REGIST];

        // TODO facebook登録APIを実施する

        pr($data);
        exit;


        return $this->redirect(['controller' => 'contract', 'action' => 'index']);

    }
}
