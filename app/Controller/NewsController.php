<?php

App::uses('MinikuraController', 'Controller');
App::uses('Receipt', 'Model');
App::uses('Billing', 'Model');
App::uses('ReceiptDetail', 'Model');

class NewsController extends MinikuraController
{
    const MODEL_NAME = 'News';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
    }

    /**
     * ニュース一覧
     */
    public function index()
    {
        $all = $this->News->getNews();
        $list = $this->paginate($all);
        $this->set('news', $list);
    }

    /**
     * ニュース詳細
     */
    public function detail()
    {
        
    }

}
