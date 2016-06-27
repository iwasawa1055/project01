<?php

App::uses('MinikuraController', 'Controller');

class PointController extends MinikuraController
{
    const MODEL_NAME = 'PointHistory';
    const MODEL_NAME_POINT_BALANCE = 'PointBalance';

    /**
     *
     */
    public function index()
    {
        // ポイント取得
        $point = [];
        $this->loadModel(self::MODEL_NAME_POINT_BALANCE);
        $res = $this->PointBalance->apiGet();
        if (!empty($res->error_message)) {
            $this->Flash->set($res->error_message);
        } else {
            $point = $res->results[0];
        }
        $this->set('point', $point);

		//* 履歴
        $histories = [];
        $this->loadModel(self::MODEL_NAME);
        $res = $this->PointHistory->apiGet();
        if (!empty($res->error_message)) {
            $this->Flash->set($res->error_message);
        } else {
            $histories = $res->results;
        }
        $list = $this->paginate($histories);
        $this->set('histories', $list);
    }
}
