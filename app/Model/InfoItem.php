<?php

App::uses('ApiCachedModel', 'Model');
App::uses('ImageItem', 'Model');

class InfoItem extends ApiCachedModel
{

    const SESSION_CACHE_KEY = 'INFO_ITEM_CACHE';

    private $defaultSortKey = [
        'box_id' => true,
        'box_name' => true,
        'item_id' => true,
        'item_name' => true,
        'item_status' => true,
        'item_group_cd' => true,
    ];

    // 結果ゼロ件チェック
    protected $checkZeroResultsKey = 'item_id';

    public function __construct()
    {
        parent::__construct($this::SESSION_CACHE_KEY, 'InfoItem', '/info_item');
    }


    // 入庫済み一覧
    public function getListForServiced($sortKey = [])
    {

      $all = $this->apiGetResults();
      $list = [];
      $okStatus = [
          BOXITEM_STATUS_INBOUND_IN_PROGRESS,
          BOXITEM_STATUS_INBOUND_DONE,
          BOXITEM_STATUS_OUTBOUND_START,
          BOXITEM_STATUS_OUTBOUND_IN_PROGRESS
      ];
      foreach ($all as $a) {
          if (in_array($a['item_status'] . '', $okStatus, true)) {
              $list[] = $a;
          }
      }

      // sort
      $this->sort($list, $sortKey, $this->defaultSortKey);
      return $list;
    }

    public function apiGet($data = [])
    {
        $imageModel = new ImageItem();
        $res = parent::apiGet($data);
        if ($res->isSuccess()) {
            foreach ($res->results as $index => $item) {
                $image = $imageModel->apiGetResultsFind([], ['item_id' => $item['item_id']]);
                $res->results[$index]['images_item'] = $image;
            }
        }
        return $res;
    }

    public $validate = [
    ];
}
