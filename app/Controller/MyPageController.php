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
			/* hotfix_mypage_v22 最近預けたボックスとアイテムをカット、代わりに契約情報を差し込む [start]
            // 最近預けたボックスとアイテム
            $box = new InfoBox();
            $list = $box->getListLastInbound();
            $boxList = array_slice($list, 0, 5);
            $item = new InfoItem();
            $list = $item->getListLastInbound();
            $itemList =  array_slice($list, 0, 10);
			// hotfix_mypage_v22 最近預けたボックスとアイテムをカット、代わりに契約情報を差し込む [end]*/
			//* 契約情報
        }

        $News = new News();
        $newsList = $News->getNews(2);

        $this->set('newsList', $newsList);        
		/* hotfix_mypage_v22 最近預けたボックスとアイテムをカット、代わりに契約情報を差し込む [start]
        $this->set('boxList', $boxList);
        $this->set('itemList', $itemList);
		// hotfix_mypage_v22 最近預けたボックスとアイテムをカット、代わりに契約情報を差し込む [end]*/
		 
    }
}
