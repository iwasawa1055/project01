<?php

App::uses('AppController', 'Controller');

class MyPageController extends AppController
{
    /**
     * ルートインデックス.
     */
    public function index()
    {
        $this->loadModel('InfoBox');
        $this->loadModel('InfoItem');

        // お知らせ
        // AppControllerで取得(ヘッダー部と同一)

        // 最近預けたボックス
        $boxList = $this->InfoBox->getListForServiced();
        $this->set('boxList', array_slice($boxList, 0, 5));

        // 最近預けたアイテム
        $itemList = $this->InfoItem->getListForServiced();
        $this->set('itemList',  array_slice($itemList, 0, 10));
    }
}
