<?php

App::uses('ApiCachedModel', 'Model');
App::uses('ImageItem', 'Model');

class InfoItem extends ApiCachedModel
{

    const SESSION_CACHE_KEY = 'INFO_ITEM_CACHE';

    public function __construct()
    {
        parent::__construct($this::SESSION_CACHE_KEY, 'InfoItem', '/info_item');
    }

    public function apiGetResults($data = [])
    {
        $imageModel = new ImageItem();
        $list = parent::apiGetResults($data);
        foreach ($list as $index => $item) {
            $image = $imageModel->apiGetResultsFind([], ['item_id' => $item['item_id']]);
            $list[$index]['images_item'] = $image;
        }
        return $list;
    }

    public $validate = [
    ];
}
