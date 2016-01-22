<?php

App::uses('AppController', 'Controller');

class BoxController extends AppController
{
    const MODEL_NAME_BOX = 'InfoBox';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel($this::MODEL_NAME_BOX);
    }

    /**
     * 一覧.
     */
    public function index()
    {
        $product = $this->request->query('product');
        // TODO: 並び替えキー指定
        $sortKeyList = [];
        $list = $this->InfoBox->getListForServiced($product, $sortKeyList);
        $this->set('boxList', $list);
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
