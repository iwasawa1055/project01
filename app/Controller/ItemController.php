<?php

App::uses('AppController', 'Controller');

class ItemController extends AppController
{
    const MODEL_NAME = 'InfoItem';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel($this::MODEL_NAME);
        $this->loadModel('InfoBox');
    }

    /**
     * 一覧.
     */
    public function index()
    {
        // 並び替えキー指定
        // $sortKey = $this->getRequestSortKey();
        $list = $this->InfoItem->apiGetResults();
        // paginate
        $list = $this->paginate($this::MODEL_NAME, $list);
        $this->set('itemList', $list);
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
