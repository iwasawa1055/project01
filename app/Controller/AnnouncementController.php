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
        $all = $this->Announcement->apiGetResults();
        $list = $this->paginate($all);
        $this->set('announcements', $list);
    }

    /**
     *
     */
    public function detail()
    {
        $id = $this->params['id'];
        $data = $this->Announcement->apiGetResultsFind([], ['announcement_id' => $id]);
        if (!empty($data)) {
            $this->set('announcement', $data);
            $this->Announcement->apiPatch(['announcement_id' => $id]);
        }
    }

    /**
     * 領収証ダウンロード
     * @return [type] [description]
     */
    public function receit()
    {
        $id = $this->params['id'];
        if ($this->request->is('post')) {
            $receit = new Receipt();
            $data = $this->Announcement->apiGetResultsFind([], ['announcement_id' => $id]);
            if (!empty($data)) {
                $res = $receit->apiGet([
                    'announcement_id' => $id,
                    'category_id' => $data['category_id']
                ]);
                if ($res->isSuccess() || count($res->results) === 1) {
                    $name = $res->results[0]['file_name'];
                    $binary = base64_decode($res->results[0]['receipt']);
                    // $binary = file_get_contents('2631634_2003858_2R.pdf');
                    $this->autoRender = false;
                    $this->response->type('pdf');
                    $this->response->download($name);
                    $this->response->body($binary);
                    return;
                } else {
                    $this->Flash->set($res->error_message);
                }
            }
        }
        return $this->redirect(['action' => 'detail', 'id' => $id]);
    }
}
