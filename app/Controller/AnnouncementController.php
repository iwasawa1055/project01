<?php

App::uses('AppController', 'Controller');

class AnnouncementController extends AppController
{
    const MODEL_NAME = 'Announcement';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel($this::MODEL_NAME);
    }

    /**
     * 一覧.
     */
    public function index()
    {
        $res = $this->Announcement->apiGet();
        if ($res->isSuccess()) {
            $list = $this->paginate($res->results);
            $this->set('announcements', $list);
        }
    }

    /**
     *
     */
    public function detail()
    {
        // TODO: セッションから取得
        
        $id = $this->params['id'];
        $res = $this->Announcement->apiGet([
          'limit' => 10,
          'offset' => 0
        ]);
        if ($res->isSuccess()) {
            foreach ($res->results as $a) {
                if ($a['announcement_id'] === $id) {
                    $this->set('announcement', $a);
                    // 既読更新
                    $this->Announcement->apiPatch([
                      'announcement_id' => $id
                    ]);
                    break;
                }
            }
        }
    }
}
