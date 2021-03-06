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
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res (PointBalance) ' . print_r($res, true));
            $this->set('point_balance_error', POINT_BALANCE_ERROR);
        } else {
            $point = $res->results[0];
        }
        $this->set('point', $point);

	//* 履歴
        $histories = [];
        $this->loadModel(self::MODEL_NAME);
        $res = $this->PointHistory->apiGet();
        if (!empty($res->error_message)) {
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res (PointHistory) ' . print_r($res, true));
            $this->set('point_history_error', POINT_HISTORY_ERROR);
        } else {
            $histories = [];
            foreach ($res->results as $result) {
                if ($result['status'] === POINT_STATUS_CANCEL) {
                    // 不具合修正：意図しない資源元からのキャンセル項目は非表示
                    if ($result['point_source'] === 'terrada:GRP') {
                        continue;
                    }
                }
                $histories[] = $result;
            }
        }
        $list = $this->paginate($histories);
        $this->set('histories', $list);
    }
}
