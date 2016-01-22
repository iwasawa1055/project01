<?php

App::uses('AppController', 'Controller');

class InboundBoxController extends AppController
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
     *
     */
    public function add()
    {
        $product = $this->request->query('product');
        $list = $this->InfoBox->getListForInbound($product);
        $this->set('boxList', $list);
    }

    /**
     *
     */
    public function confirm()
    {
    }

    /**
     *
     */
    public function complete()
    {
    }
}
