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

        $boxList = $this->InfoBox->getListForServiced();
        $this->set('boxList', array_slice($boxList, 0, 8));

        $itemList = $this->InfoItem->getListForServiced();
        $this->set('itemList',  array_slice($itemList, 0, 8));
    }
}
