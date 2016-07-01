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
        $boxList = [];
        $itemList = [];
        if (!$this->Customer->isEntry()) {
            // 最近預けたボックスとアイテム
            $box = new InfoBox();
            $list = $box->getListLastInbound();
            $boxList = array_slice($list, 0, 5);
            $item = new InfoItem();
            $list = $item->getListLastInbound();
            $itemList =  array_slice($list, 0, 10);
        }

        $News = new News();

        $newsList = $News->getNews(2);
        $this->set('newsList', $newsList);

        $announcement = new AnnouncementNoCache();
        $res = $announcement->apiGet(['limit' => 5]);
        $this->set('notice_announcements', $res->results);

        $this->set('boxList', $boxList);
        $this->set('itemList', $itemList);
    }
}
