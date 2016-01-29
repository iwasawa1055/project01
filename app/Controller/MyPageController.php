<?php

App::uses('AppController', 'Controller');

class MyPageController extends AppController
{
    const MODEL_NAME_BOX = 'InfoBox';
    const MODEL_NAME_Item = 'InfoItem';
    const MODEL_NAME_IMAGE_Item = 'ImageItem';

    /**
     * ルートインデックス.
     */
    public function index()
    {
        $this->loadModel($this::MODEL_NAME_BOX);
        $this->loadModel($this::MODEL_NAME_Item);
        $this->loadModel($this::MODEL_NAME_IMAGE_Item);

        $boxList = $this->InfoBox->getListForServiced();
        $itemList = $this->InfoItem->apiGetResults(['limit' => 8]);

        $this->set('itemList', $itemList);
        $this->set('boxList', $boxList);
    }
}
