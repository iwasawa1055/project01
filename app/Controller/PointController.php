<?php

App::uses('MinikuraController', 'Controller');

class PointController extends MinikuraController
{
    const MODEL_NAME = 'PointHistory';

    /**
     *
     */
    public function index()
    {
        $histories = [];
        $this->loadModel(self::MODEL_NAME);
        $res = $this->PointHistory->apiGet();
        if (!empty($res->error_message)) {
            $this->Flash->set($res->error_message);
        } else {
            $histories = $res->results;
        }
        $this->set('histories', $histories);
    }
}
