<?php

App::uses('AppController', 'Controller');

class BoxController extends AppController
{
    const MODEL_NAME = 'InfoBox';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel($this::MODEL_NAME);
    }

    /**
     * 一覧.
     */
    public function index()
    {
        // 商品指定
        $product = $this->request->query('product');
        // 並び替えキー指定
        $sortKey = $this->getRequestSortKey();
        $results = $this->InfoBox->getListForServiced($product, $sortKey);
        // paginate
        $list = $this->paginate($this::MODEL_NAME, $results);
        $this->set('boxList', $list);
    }

    public function getRequestSortKey()
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
        return $this->redirect('/box/detail/1');
    }

    /**
     *
     */
    public function item()
    {
    }
}
