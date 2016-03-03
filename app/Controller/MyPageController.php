<?php

App::uses('MinikuraController', 'Controller');
App::uses('InfoBox', 'Model');
App::uses('InfoItem', 'Model');

class MyPageController extends MinikuraController
{
    /**
     * ルートインデックス.
     */
    public function index()
    {
        // 最近預けたボックス
        $box = new InfoBox();
        $list = $box->getListLastInbound();
        $this->set('boxList', array_slice($list, 0, 5));

        // 最近預けたアイテム
        $item = new InfoItem();
        $list = $item->getListLastInbound();
        $this->set('itemList', array_slice($list, 0, 10));
    }
}
