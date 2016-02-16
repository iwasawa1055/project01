<?php

App::uses('AppController', 'Controller');

class ItemController extends AppController
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

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel($this::MODEL_NAME);
        $this->loadModel('InfoBox');

        $this->set('sortSelectList', $this->makeSelectSortUrl());
        $this->set('select_sort_value', Router::reverse($this->request));
    }

    private function makeSelectSortUrl()
    {
        $data = [];
        foreach ($this::SELECT_SORT_KEY as $key => $value) {
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
        $list = $this->paginate($this::MODEL_NAME, $results);
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

    }

    /**
     *
     */
    public function edit()
    {
    }

    /**
     *
     */
    public function update()
    {
        return $this->redirect('/item/detail/1');
    }
}
