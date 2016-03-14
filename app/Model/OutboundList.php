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
        $okStatus = [
            BOXITEM_STATUS_INBOUND_DONE,
        ];

        $itemListBoxId = Hash::extract($this->getItemList(), '{s}.box_id');

        // list
        $box = new InfoBox();
        $list = $box->apiGetResultsWhere([], ['box_id' => $idList]);

        $this->boxList = [];
        $errorList = [];
        foreach ($list as $a) {
            $boxId = $a['box_id'];
            // check status
            if (!in_array($a['box_status'], $okStatus, true)) {
                $errorList[$boxId][] = '追加出来るステータスではありません';
            }
            // chcek item
            if (in_array($boxId, $itemListBoxId, true)) {
                $errorList[$boxId][] = 'アイテムが既に追加されています。';
            }
            $this->boxList[$boxId] = $a;
        }
        OutboundList::save($this);
        return $errorList;
    }

    public function setItem($idList = [])
    {
        $okStatus = [
            BOXITEM_STATUS_INBOUND_DONE * 1,
        ];

        $boxKeyList = array_keys($this->getBoxList());

        // list
        $item = new InfoItem();
        $list = $item->apiGetResultsWhere([], ['item_id' => $idList]);

        $this->itemList = [];
        $errorList = [];
        foreach ($list as $a) {
            $itemId = $a['item_id'];
            // check status
            if (!in_array($a['item_status'], $okStatus, true)) {
                $errorList[$itemId][] = '追加出来るステータスではありません';
            }
            // chcek item
            if (in_array($a['box_id'], $boxKeyList, true)) {
                $errorList[$itemId][] = 'ボックスが既に追加されています。';
            }
            $this->itemList[$itemId] = $a;
        }
        OutboundList::save($this);
        return $errorList;
    }

    public function canAddBox()
    {
    }
    public function canAddItem()
    {
        $result = [''];
    }
}
