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

        $res = $this->InfoBox->apiGet([
            'limit' => 8
        ]);
        $boxList = $res->results;

        $res = $this->InfoItem->apiGet([
            'limit' => 8
        ]);
        $itemList = $res->results;

        $imageItemList = [];
        foreach ($itemList as $index => $item) {
            $res = $this->ImageItem->apiGet([
                'item_id' => $item['item_id'],
                'limit' => 1,
            ]);
            $itemList[$index]['images_item'] = $res->results[0];
        }

        $this->set('itemList', $itemList);
        $this->set('boxList', $boxList);
    }
}
