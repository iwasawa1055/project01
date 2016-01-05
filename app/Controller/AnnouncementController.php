<?php

App::uses('AppController', 'Controller');

class AnnouncementController extends AppController
{
    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
    }

    /**
     * 一覧.
     */
    public function index()
    {
        $this->loadModel('Announcement');

        $res = $this->Announcement->apiGet();

        if ($res->status === '1') {
            $this->set('announcements', $res->results);
        }
    }

    /**
     *
     */
    public function detail()
    {
    }
}
