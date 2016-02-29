<?php

App::uses('MinikuraController', 'Controller');

class ItemController extends MinikuraController
{
    const MODEL_NAME = 'InfoItem';
    const SELECT_SORT_KEY = [
        'box_id' => '箱NO',
        'box_name' => '箱タイトル',
        'item_id' => '個品NO',
        'item_name' => '個品タイトル',
        'item_status' => 'ステータス',
        'item_group_cd' => 'カテゴリ'
    ];
    const MODEL_NAME_ITEM_EDIT = 'Item';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
        $this->loadModel('InfoBox');
        $this->loadModel(self::MODEL_NAME_ITEM_EDIT);

        $this->set('sortSelectList', $this->makeSelectSortUrl());
        $this->set('select_sort_value', Router::reverse($this->request));
    }

    private function makeSelectSortUrl()
    {
        $data = [];
        foreach (self::SELECT_SORT_KEY as $key => $value) {
            $desc = Router::url(['action'=>'index', '?' => ['order' => $key, 'direction' => 'desc']]);
            $data[$desc] = $value . '（降順）';

            $asc = Router::url(['action'=>'index', '?' => ['order' => $key, 'direction' => 'asc']]);
            $data[$asc] = $value . '（昇順）';
        }

        return $data;
    }

    /**
     * 一覧.
     */
    public function index()
    {
        // 並び替えキー指定
        $sortKey = $this->getRequestSortKey();
        $results = $this->InfoItem->getListForServiced($sortKey);
        // paginate
        $list = $this->paginate(self::MODEL_NAME, $results);
        $this->set('itemList', $list);
    }

    private function getRequestSortKey()
    {
        $order = $this->request->query('order');
        $direction = $this->request->query('direction');
        if (!empty($order)) {
            return [$order => ($direction === 'asc')];
        }
        return [];
    }

    /**
     *
     */
    public function detail()
    {
        $id = $this->params['id'];
        $item = $this->InfoItem->apiGetResultsFind([], ['item_id' => $id]);
        $this->set('item', $item);

        $box = $this->InfoBox->apiGetResultsFind([], ['box_id' => $item['box_id']]);
        $this->set('box', $box);

        $linkToAuction = null;
        if (in_array($box['product_cd'], [PRODUCT_CD_MONO, PRODUCT_CD_CLEANING_PACK], true)) {
            $linkToAuction = "/mini_auction/lite/item/${item['box_id']}/${item['item_id']}";
        }
        $this->set('linkToAuction', $linkToAuction);

    }

    /**
     *
     */
    public function edit()
    {
        $id = $this->params['id'];
        $item = $this->InfoItem->apiGetResultsFind([], ['item_id' => $id]);
        $this->set('item', $item);

        $box = $this->InfoBox->apiGetResultsFind([], ['box_id' => $item['box_id']]);
        $this->set('box', $box);

        if ($this->request->is('get')) {

            $this->request->data[self::MODEL_NAME_ITEM_EDIT] = $item;
            return $this->render('edit');

        } elseif ($this->request->is('post')) {

            $this->Item->set($this->request->data);
            if (!$this->Item->validates()) {
                return $this->render('edit');
            }

            $res = $this->Item->apiPatch($this->Item->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['controller' => 'item', 'action' => 'detail', 'id' => $id]);
            }

            return $this->redirect(['controller' => 'item', 'action' => 'detail', 'id' => $id]);
        }
    }
}
