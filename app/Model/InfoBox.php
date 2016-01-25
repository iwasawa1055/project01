<?php

App::uses('ApiModel', 'Model');
App::uses('ArraySorter', 'Model');

class InfoBox extends ApiModel
{
    const SESSION_LIST = 'INFO_BOX_LIST';

    public function __construct()
    {
        parent::__construct('InfoBox', '/info_box');
    }

    // 購入済みキット一覧
    // 利用中のBOX一覧　と　並び替え
    // 商品別集計

    // 入庫画面で表示
    public function getListForInbound()
    {
        $all = $this->getList();
        $list = [];
        foreach ($all as $a) {
            if (empty($a['product_cd'])) {
                $list[] = $a;
            }
        }
        return $list;
    }

    // 入庫済み一覧
    public function getListForServiced($product = null, $sortKey = [], $offset = 0, $limit = 20)
    {
        // デフォルトソートキー
        $sortKeyList = [
            'box_id' => true,
            'box_name' => true,
            'product_name' => true,
            'box_status' => true,
        ];
        $sortKeyList[] = $sortKey;

        // 商品の絞込
        $productCd = null;
        if ($product === 'hako') {
            $productCd = PRODUCT_CD_HAKO;
        } elseif ($product === 'mono') {
            $productCd = PRODUCT_CD_MONO;
        } elseif ($product === 'cleaning') {
            $productCd = PRODUCT_CD_CLEANING_PACK;
        }
        $all = $this->getList();
        $list = [];
        foreach ($all as $a) {
            if ($a['box_status'] === BOXITEM_STATUS_INBOUND_DONE &&
                (empty($productCd) || $a['product_cd'] === $productCd)) {
                $list[] = $a;
            }
        }
        $sort = new ArraySorter($sortKeyList);
        usort($list, [$sort, 'cmp']);
        return $list;
    }

    // 出庫画面で表示
    public function getListForOutbound($product = null)
    {
        $productCd = null;
        if ($product === 'hako') {
            $productCd = PRODUCT_CD_HAKO;
        } elseif ($product === 'mono') {
            $productCd = PRODUCT_CD_MONO;
        }
        $all = $this->getList();
        $list = [];
        foreach ($all as $a) {
            if ($a['box_status'] === BOXITEM_STATUS_INBOUND_DONE &&
                (empty($productCd) || $a['product_cd'] === $productCd)) {
                $list[] = $a;
            }
        }
        return $list;
    }

    public function refresh()
    {
        CakeSession::delete($this::SESSION_LIST);
    }

    private function getList($arg = [])
    {
        $list = CakeSession::read($this::SESSION_LIST);
        if (!empty($list)) {
            return $list;
        }

        // すべて取得
        $list = [];
        $offset = 0;
        $count = 0;
        $limit = 1000;
        do {
            $arg['offset'] = $offset;
            $arg['limit'] = $limit;
            $res = $this->apiGet($arg);
            $addList = $res->results;

            $count = count($addList);
            $list = array_merge($list, $addList);
            $offset++;
        } while ($limit === $count);
        return $list;
    }

    public $validate = [
    ];
}
