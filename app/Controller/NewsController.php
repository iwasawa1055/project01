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
        // ニュース稼働フラグが0の場合、トップへリダイレクト
        if (NEWS_ACTIVE_FLAG === 0) {
            return $this->redirect('/');
        }
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
        $id = $this->params['id'];       
        $news = $this->News->getNews(null, $id);
        $this->set('news', $news);
    }

}
