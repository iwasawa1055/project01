<?php

App::uses('MinikuraController', 'Controller');
App::uses('InfoBox', 'Model');
App::uses('InfoItem', 'Model');
App::uses('News', 'Model');

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

        $res = $this->Announcement->apiGetResults(['limit' => 5]);
        $this->set('notice_announcements', $res);

        $this->set('newsList', $newsList);        
        $this->set('boxList', $boxList);
        $this->set('itemList', $itemList);
    }
}
