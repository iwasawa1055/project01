<?php

App::uses('InfoBox', 'Model');
App::uses('InfoItem', 'Model');


class OutboundList
{

    const SESSION_KEY = 'OUTBOUND_LIST_CACHE';

    private $boxList = [];
    private $itemList = [];

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

    public function getItemList()
    {
        return $this->itemList;
    }

    public function setCheckboxBoxAndSave($boxList = [])
    {
        $where = [];
        foreach ($boxList as $k => $a) {
            if (!empty($a['checkbox'])) {
                $where['box_id'][] = $k;
            }
        }
        $infoBox = new InfoBox();
        $add = $infoBox->apiGetResultsWhere([], $where);
        $this->boxList = [];
        return $this->addBoxAndSave($add);
    }

    public function setBoxAndSave($list = [])
    {
        $this->boxList = [];
        return $this->addBoxAndSave($list);
    }
    public function setItemAndSave($list = [])
    {
        $this->itemList = [];
        return $this->addItemAndSave($list);
    }

    public function addBoxAndSave($list = [])
    {
        $r = $this->addBox($list);
        if ($r === true) {
            OutboundList::save($this);
        }
        return $r;
    }
    public function addItemAndSave($list = [])
    {
        $r = $this->addItem($list);
        if ($r === true) {
            OutboundList::save($this);
        }
    }

    public function addBox($list = [])
    {
        // ボックスステータス
        // アイテムステータスチェック
        $msg = [];
        foreach ($list as $a) {
            $boxId = $a['box_id'];
            $this->boxList[$boxId] = $a;
        }
        return true;
    }

    public function addItem($list = [])
    {
        $msg = [];
        foreach ($list as $a) {
            $itemId = $a['item_id'];
            $this->itemList[$itemId] = $a;
        }
        return true;
    }

    public function canAddBox()
    {
    }
    public function canAddItem()
    {
        $result = [''];
    }
}
