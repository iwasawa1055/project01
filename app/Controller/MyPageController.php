<?php

App::uses('MinikuraController', 'Controller');

class MyPageController extends MinikuraController
{
    /**
     * ルートインデックス.
     */
    public function index()
    {
        $this->loadModel('InfoBox');
        $this->loadModel('InfoItem');

        // 最近預けたボックス
        $boxList = $this->InfoBox->getListForServiced();
        $this->set('boxList', array_slice($boxList, 0, 5));

        // 最近預けたアイテム
        $itemList = $this->InfoItem->getListForServiced();
        $this->set('itemList',  array_slice($itemList, 0, 10));
    }
}
