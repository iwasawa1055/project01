<?php

App::uses('ApiCachedModel', 'Model');

class InfoBox extends ApiCachedModel
{
    const SESSION_CACHE_KEY = 'INFO_BOX_CACHE';

    private $defaultSortKey = [
        'box_id' => true,
        'box_name' => true,
        'product_name' => true,
        'box_status' => true,
    ];

    public function __construct()
    {
        parent::__construct($this::SESSION_CACHE_KEY, 'InfoBox', '/info_box');
    }

    // 購入済みキット一覧
    // 利用中のBOX一覧　と　並び替え
    // 商品別集計
    public function getProductSummary()
    {
        $key = 'summary';
        $summary = $this->readCache($key);
        if (!empty($summary)) {
            return $summary;
        }

        $all = $this->apiGetResults();
        $summary = [];
        foreach ($all as $a) {
            $productCd = $a['product_cd'];
            if (empty($productCd)) {
                continue;
            }
            if (empty($summary[$productCd])) {
                $summary[$productCd] = 1;
            } else {
                $summary[$productCd]++;
            }
        }
        $this->writeCache($key, $summary);
        return $summary;
    }

    // 入庫画面で表示
    public function getListForInbound($sortKeyList = [])
    {
        $all = $this->apiGetResults();
        $list = [];
        foreach ($all as $a) {
            if (empty($a['product_cd'])) {
                $list[] = $a;
            }
        }
        return $list;
    }

    // 入庫済み一覧
    public function getListForServiced($product = null, $sortKey = [])
    {
        // productCd
        $productCd = null;
        if ($product === 'hako') {
            $productCd = PRODUCT_CD_HAKO;
        } elseif ($product === 'mono') {
            $productCd = PRODUCT_CD_MONO;
        } elseif ($product === 'cleaning') {
            $productCd = PRODUCT_CD_CLEANING_PACK;
        }
        $all = $this->apiGetResults();
        // filter
        $list = [];
        foreach ($all as $a) {
            if ($a['box_status'] === BOXITEM_STATUS_INBOUND_DONE &&
                (empty($productCd) || $a['product_cd'] === $productCd)) {
                $list[] = $a;
            }
        }

        // sort
        $this->sort($list, $sortKey, $this->defaultSortKey);
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

    public $validate = [
    ];
}
