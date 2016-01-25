<?php

App::uses('AppController', 'Controller');

class AnnouncementController extends AppController
{
    /**
     * 一覧.
     */
    public function index()
    {
        $this->loadModel('Announcement');
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
        $id = $this->params['id'];
        $this->loadModel('Announcement');
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
