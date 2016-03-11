<?php

App::uses('ApiCachedModel', 'Model');
App::uses('ImageItem', 'Model');
App::uses('HashSorter', 'Model');

class InfoItem extends ApiCachedModel
{

    const SESSION_CACHE_KEY = 'INFO_ITEM_CACHE';

    const DEFAULTS_SORT_KEY = [
        'box.kit_cd' => true,
        'box.product_name' => true,
        'box.box_id' => true,
        'box.box_name' => true,
        'item_id' => true,
        'item_name' => true,
        'item_status' => true,
        'item_group_cd' => true,
    ];

    // 結果ゼロ件チェック
    protected $checkZeroResultsKey = 'item_id';

    public function __construct()
    {
        parent::__construct(self::SESSION_CACHE_KEY, 0, 'InfoItem', '/info_item');
    }

    protected function triggerNotUsingCache() {
        parent::triggerNotUsingCache();
        (new ImageItem())->deleteCache();

    }

    // 入庫済み一覧
    public function getListForServiced($sortKey = [], $where = [], $withOutboudDone = true)
    {
        $where['item_status'] = [
            BOXITEM_STATUS_INBOUND_IN_PROGRESS * 1,
            BOXITEM_STATUS_INBOUND_DONE * 1,
            BOXITEM_STATUS_OUTBOUND_START * 1,
            BOXITEM_STATUS_OUTBOUND_IN_PROGRESS * 1,
        ];
        if ($withOutboudDone) {
            $where['item_status'][] = BOXITEM_STATUS_OUTBOUND_DONE * 1;
        }

        $list = $this->apiGetResultsWhere([], $where);

        // sort
        HashSorter::sort($list, ($sortKey + self::DEFAULTS_SORT_KEY));
        return $list;
    }

    public function apiGetResults($data = [])
    {
        $imageModel = new ImageItem();
        $boxModel = new InfoBox();
        $list = parent::apiGetResults($data);
        foreach ($list as $index => $item) {
            $list[$index]['image_first'] = $imageModel->apiGetResultsFind([], ['item_id' => $item['item_id']]);
            $list[$index]['box'] = $boxModel->apiGetResultsFind([], ['box_id' => $item['box_id']]);
        }
        return $list;
    }

    public function getListLastInbound() {
        $where = [
            'item_status' => [
                BOXITEM_STATUS_INBOUND_DONE * 1,
                BOXITEM_STATUS_OUTBOUND_START * 1,
                BOXITEM_STATUS_OUTBOUND_IN_PROGRESS * 1,
            ]
        ];
        $list = $this->apiGetResultsWhere([], $where);
        $sortKey = ['box.inbound_date' => false];
        HashSorter::sort($list, ($sortKey + self::DEFAULTS_SORT_KEY));
        return $list;
    }
}