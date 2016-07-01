<?php

App::uses('MinikuraController', 'Controller');
App::uses('InfoBox', 'Model');
App::uses('InfoItem', 'Model');
App::uses('News', 'Model');
App::uses('AnnouncementNoCache', 'Model');

class MyPageController extends MinikuraController
{
    /**
     * ルートインデックス.
     */
    public function index()
    {

        $News = new News();

        $newsList = $News->getNews(2);
        $this->set('newsList', $newsList);

        $announcement = new AnnouncementNoCache();
        $res = $announcement->apiGet(['limit' => 5]);
        $this->set('notice_announcements', $res->results);

    }
}
