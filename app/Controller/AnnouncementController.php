<?php

App::uses('MinikuraController', 'Controller');
App::uses('Receipt', 'Model');

class AnnouncementController extends MinikuraController
{
    const MODEL_NAME = 'Announcement';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
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
        $id = $this->params['id'];
        $data = $this->Announcement->apiGetResultsFind([], ['announcement_id' => $id]);
        $this->set('announcement', $data);
        // 既読更新
        $this->Announcement->apiPatch(['announcement_id' => $id]);
    }

    /**
     * 領収証ダウンロード
     * @return [type] [description]
     */
    public function receit()
    {
        $id = $this->params['id'];
        $receit = new Receipt();
        $data = $this->Announcement->apiGetResultsFind([], ['announcement_id' => $id]);
        $res = $receit->apiGet([
            'announcement_id' => $id,
            'category_id' => $data['category_id']
        ]);
        if ($res->isSuccess() || count($res->results) === 1) {
            $name = $res->results[0]['file_name'];
            $binary = base64_decode($res->results[0]['receipt']);
            $this->autoRender = false;
            $this->response->type('pdf');
            $this->response->download($name);
            $this->response->body($binary);
        } else {
            $this->Flash->set($res->error_message);
            return $this->redirect(['action' => 'detail', 'id' => $id]);
        }
    }
}
