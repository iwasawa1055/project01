<?php

App::uses('ApiModel', 'Model');

// TODO: 別ファイルに移動
class ArraySort {
    public $sortKeyList = [];
    public function __construct($sortKeyList) {
        $this->sortKeyList = $sortKeyList;
    }
    public function cmp($a, $b) {
        foreach ($this->sortKeyList as $key => $isAsc) {
            $aKeyExist = array_key_exists($key, $a);
            $bKeyExist = array_key_exists($key, $b);
            if ($a && $b && $a[$key] !== $b[$key]) {
                return strcmp($a[$key], $b[$key]) * ($isAsc? 1 : -1);
            } else if (!$a && !$b) {
                return $a ? 1 : -1;
            }
        }
        return 0;
    }
}

class InfoBox extends ApiModel
{
    const SESSION_LIST = 'INFO_BOX_LIST';

    // TODO: 定義を共通化
    const KIT_CD_HAKO = '64';
    const KIT_CD_HAKO_APPAREL = '65';
    const KIT_CD_HAKO_BOOK = '81';
    const KIT_CD_MONO = '66';
    const KIT_CD_MONO_APPAREL = '67';
    const KIT_CD_MONO_BOOK = '82';
    const KIT_CD_WINE_HAKO = '77';
    const KIT_CD_WINE_MONO = '83';
    const KIT_CD_CLEANING_PACK = '75';

    const PRODUCT_CD_MONO = '004025';
    const PRODUCT_CD_HAKO = '004024';
    const PRODUCT_CD_CLEANING_PACK = '004029';
    const PRODUCT_CD_SHOES_PACK = '005000';

    public function __construct()
    {
        parent::__construct('InfoBox', '/info_box');
    }

    // 購入済みキット一覧
    // 利用中のBOX一覧　と　並び替え
    // 商品別集計

    // TODO: 定義を共通化
    // box_status
    // キット購入・依頼
    // 10	完了
    // 20   進行中
    // 30	完了
    const BOXITEM_STATUS_BUYKIT_START = '10';
    const BOXITEM_STATUS_BUYKIT_IN_PROGRESS = '20';
    const BOXITEM_STATUS_BUYKIT_DONE = '30';
    // 入庫・依頼
    // 40	完了
    // 60	進行中
    // 70	完了
    const BOXITEM_STATUS_INBOUND_START = '40';
    const BOXITEM_STATUS_INBOUND_IN_PROGRESS = '60';
    const BOXITEM_STATUS_INBOUND_DONE = '70';
    // 出庫・依頼
    // 180	完了
    // 200	進行中
    // 210	完了
    // 再入庫・依頼
    // 220	完了
    // 230	進行中
    // オプション・依頼
    // 130	進行中
    // 140	完了
    // 150	進行中
    // 160	完了

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
    public function getListForServiced($product = null, $sortKeyList = [], $offset = 0, $limit = 20)
    {
        // デフォルトソートキー
        $sortKeyList = [
            'box_id' => true,
            'box_name' => true,
            'product_name' => true,
            'box_status' => true,
        ];
        // TODO: ソートキーを追加する

        $productCd = null;
        if ($product === 'hako') {
            $productCd = $this::PRODUCT_CD_HAKO;
        } elseif ($product === 'mono') {
            $productCd = $this::PRODUCT_CD_MONO;
        } elseif ($product === 'cleaning') {
            $productCd = $this::PRODUCT_CD_CLEANING_PACK;
        }
        $all = $this->getList();
        $list = [];
        foreach ($all as $a) {
            if ($a['box_status'] === $this::BOXITEM_STATUS_INBOUND_DONE &&
                (empty($productCd) || $a['product_cd'] === $productCd)) {
                $list[] = $a;
            }
        }
        $sort = new ArraySort($sortKeyList);
        usort($list, [$sort, 'cmp']);
        return $list;
    }

    // 出庫画面で表示
    public function getListForOutbound($product = null)
    {
        $productCd = null;
        if ($product === 'hako') {
            $productCd = $this::PRODUCT_CD_HAKO;
        } elseif ($product === 'mono') {
            $productCd = $this::PRODUCT_CD_MONO;
        }
        $all = $this->getList();
        $list = [];
        foreach ($all as $a) {
            if ($a['box_status'] === $this::BOXITEM_STATUS_INBOUND_DONE &&
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
