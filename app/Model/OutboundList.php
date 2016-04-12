<?php

App::uses('InfoBox', 'Model');
App::uses('InfoItem', 'Model');

/**
 * 取り出しリスト管理クラス
 */
class OutboundList
{

    const SESSION_KEY = 'OUTBOUND_LIST_CACHE';

    private $boxList = [];
    private $itemList = [];
    private $monoList = [];

    public function __construct()
    {
    }

    public static function restore()
    {
        $d = CakeSession::read(OutboundList::SESSION_KEY);
        if (empty($d)) {
            $d = new OutboundList();
        }
        return $d;
    }

    public static function save($d)
    {
        CakeSession::write(OutboundList::SESSION_KEY, $d);
    }

    public static function delete()
    {
        CakeSession::delete(OutboundList::SESSION_KEY);
    }

    public function getBoxList()
    {
        return $this->boxList;
    }
    public function getBoxIdFromBoxList()
    {
        return Hash::extract($this->getBoxList(), '{s}.box_id');
    }

    public function getItemList()
    {
        return $this->itemList;
    }
    public function getItemIdFromItemList()
    {
        return Hash::extract($this->getItemList(), '{s}.item_id');
    }
    public function getBoxIdFromItemList()
    {
        return Hash::extract($this->getItemList(), '{s}.box_id');
    }

    public function getMonoList()
    {
        return $this->monoList;
    }
    public function getBoxIdFromMonoList()
    {
        return Hash::extract($this->getMonoList(), '{s}.box_id');
    }

    public function setMono($idList = [], $needClear = true, $needSave = true)
    {
        $box = new InfoBox();
        $list = $box->apiGetResultsWhere([], ['box_id' => $idList]);

        $this->monoList = [];
        $errorList = [];
        foreach ($list as $a) {
            $boxId = $a['box_id'];
            $msg = $this->canAddMono($a);
            if (!empty($msg)) {
                $errorList[$boxId][] = $msg;
            }
            $this->monoList[$boxId] = $a;
        }

        // 選択ボックス以外のアイテムは外す
        $boxIdList = Hash::extract($list, '{n}.box_id');
        $newList = [];
        foreach ($this->itemList as $key => $a) {
            if (in_array($a['box_id'], $boxIdList, true)) {
                $newList[$key] = $a;
            }
        }
        $this->itemList = $newList;

        OutboundList::save($this);
        return $errorList;
    }

    public function setBox($idList = [], $needClear = true, $needSave = true)
    {
        // list
        $box = new InfoBox();
        $list = $box->apiGetResultsWhere([], ['box_id' => $idList]);

        $this->boxList = [];
        $errorList = [];
        foreach ($list as $a) {
            $boxId = $a['box_id'];
            $msg = $this->canAddBox($a);
            if (!empty($msg)) {
                $errorList[$boxId][] = $msg;
            }
            $this->boxList[$boxId] = $a;
        }
        OutboundList::save($this);
        return $errorList;
    }

    public function setItem($idList = [])
    {
        // list
        $item = new InfoItem();
        $list = $item->apiGetResultsWhere([], ['item_id' => $idList]);

        $this->itemList = [];
        $errorList = [];
        foreach ($list as $a) {
            $itemId = $a['item_id'];
            $msg = $this->canAddItem($a);
            if (!empty($msg)) {
                $errorList[$itemId][] = $msg;
            }
            $this->itemList[$itemId] = $a;
        }
        OutboundList::save($this);
        return $errorList;
    }

    /**
     * ボックス追加可否
     * @param  array $box
     * @return string      不可の原因メッセージ。可能の場合null
     */
    public function canAddBox($box)
    {
        if (in_array($box['product_cd'], [PRODUCT_CD_CARGO_JIBUN, PRODUCT_CD_CARGO_HITOMAKASE], true)) {
            return 'お手数をお掛けしますが、この商品の取り出しは<a href="/contact_us/add">各種情報変更</a>からお問い合わせください。';
        }
        if ($box['box_status'] !== BOXITEM_STATUS_INBOUND_DONE) {
            return '追加可能なステータスではありません。';
        }
        if (in_array($box['box_id'], $this->getBoxIdFromItemList(), true)) {
            return 'ボックスに含まれるアイテムが既に取り出しリストに追加されています。';
        }
        $item = new InfoItem();
        $itemList = $item->apiGetResultsWhere([], ['box_id' => $box['box_id']]);
        foreach ($itemList as $i) {
            if (!in_array($i['item_status'], [BOXITEM_STATUS_INBOUND_DONE * 1, BOXITEM_STATUS_OUTBOUND_DONE * 1], true)) {
                return 'ボックスに含まれるアイテムが出庫またはオプション作業中です。';
            }
        }
        return null;
    }

    /**
     * アイテム追加向けボックス選択可否
     * @param  array $box
     * @return string      不可の原因メッセージ。可能の場合null
     */
    public function canAddMono($box)
    {
        if (in_array($box['product_cd'], [PRODUCT_CD_CARGO_JIBUN, PRODUCT_CD_CARGO_HITOMAKASE], true)) {
            return 'お手数をお掛けしますが、この商品の取り出しは<a href="/contact_us/add">各種情報変更</a>からお問い合わせください。';
        }
        if (in_array($box['box_id'], $this->getBoxIdFromBoxList(), true)) {
            return 'ボックスとして既に取り出しリストに追加されています。';
        }
        return null;
    }

    /**
     * アイテム追加可否
     * @param  array $item
     * @return string      不可の原因メッセージ。可能の場合null
     */
    public function canAddItem($item)
    {
        if (in_array(Hash::get($item, 'box.product_cd'), [PRODUCT_CD_CARGO_JIBUN, PRODUCT_CD_CARGO_HITOMAKASE], true)) {
            return 'お手数をお掛けしますが、この商品の取り出しは<a href="/contact_us/add">各種情報変更</a>からお問い合わせください。';
        }
        if ($item['item_status'] !== BOXITEM_STATUS_INBOUND_DONE * 1) {
            return '追加可能なステータスではありません。';
        }
        if (in_array($item['box_id'], $this->getBoxIdFromBoxList(), true)) {
            return 'ボックスとして既に取り出しリストに追加されています。';
        }
        return null;
    }
}
