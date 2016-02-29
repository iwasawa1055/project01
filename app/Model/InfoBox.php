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
        parent::__construct(self::SESSION_CACHE_KEY, 0, 'InfoBox', '/info_box');
    }

    // 購入済みキット一覧
    // 利用中のBOX一覧　と　並び替え
    // 商品別集計
    public function getProductSummary()
    {
        $key = 'summary';
        $summary = $this->readCache($key, []);
        if (!empty($summary)) {
            return $summary;
        }

        $all = $this->getListForServiced();
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
        $this->writeCache($key, [], $summary);
        return $summary;
    }

    // 入庫画面で表示
    public function getListForInbound()
    {
        $all = $this->apiGetResults();
        $list = [];
        foreach ($all as $a) {
            if (empty($a['product_cd'])) {
                $list[] = $a;
            }
        }
        $this->sort($list, [], $this->defaultSortKey);
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
        } elseif ($product === 'outbounditem') {
            $productCd = [PRODUCT_CD_MONO, PRODUCT_CD_CLEANING_PACK];
        }
        $okStatus = [
            BOXITEM_STATUS_INBOUND_IN_PROGRESS,
            BOXITEM_STATUS_INBOUND_DONE,
            BOXITEM_STATUS_OUTBOUND_START,
            BOXITEM_STATUS_OUTBOUND_IN_PROGRESS
        ];
        $list = $this->apiGetResultsWhere([], ['box_status' => $okStatus, 'product_cd' => $productCd]);
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
        $okStatus = [
            BOXITEM_STATUS_INBOUND_DONE,
        ];
        $list = $this->apiGetResultsWhere([], ['box_status' => $okStatus, 'product_cd' => $productCd]);
        $this->sort($list, [], $this->defaultSortKey);
        return $list;
    }

    public $validate = [
    ];


    /**
     * kit_cdからproduct_cdに変換
     * @param  [type] $kitCd [description]
     * @return [type]        [description]
     */
    public static function kitCd2ProductCd($kitCd) {
        $productCd = '';
        switch ($kitCd) {
            case KIT_CD_MONO:
            case KIT_CD_MONO_BOOK:
            case KIT_CD_MONO_APPAREL:
                $productCd = PRODUCT_CD_MONO;
                break;
            case KIT_CD_HAKO:
            case KIT_CD_HAKO_BOOK:
            case KIT_CD_HAKO_APPAREL:
                $productCd = PRODUCT_CD_HAKO;
                break;
            case KIT_CD_CLEANING_PACK:
                $productCd = PRODUCT_CD_CLEANING_PACK;
                break;
            default:
                break;
        }
        return $productCd;
    }
}
