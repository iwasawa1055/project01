<?php

App::uses('MinikuraController', 'Controller');

class NewsManagementController extends MinikuraController
{
    const MODEL_NAME = 'News';

    // ログイン不要なページ
    protected $checkLogined = false;

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);

        if ($this->Customer->isLogined()) {
            // ご利用中サービスの集計
            $this->set('product_summary', []);
            if (!$this->Customer->isEntry()) {
                $summary = $this->InfoBox->getProductSummary(false);
                $this->set('product_summary', $summary);
                // 出庫済み含めた利用
                $summary_all = $this->InfoBox->getProductSummary(true, 'summary_all');
                $this->set('summary_all', $summary_all);
            }
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
     * ニュース登録
     */
    public function add()
    {
        if ($this->request->is('get')) {
            return $this->render('add');
        } elseif ($this->request->is('post')) {

            $this->News->set($this->request->data);
            if (!$this->News->validates()) {
                return $this->render('add');
            }

            // ニュースファイル読み込み
            $xml = simplexml_load_string(file_get_contents(News::NEWS_FEED_XML_PATH), 'SimpleXMLExtended');
            if (empty($xml)) {
                return $this->redirect('/news_management');
            }

            $max_id = 0;
            $tmp = 0;
            foreach ($xml->channel->item as $v) {
                $tmp = (int)$v->id;
                if ($max_id < $tmp) {
                    $max_id = $tmp;
                }
            }

            $channel = $xml->xpath("/rss/channel");
            $item = $channel[0]->addChild("item");
            $item->addChild("id", ($max_id + 1));

            $item->addChild("title", $this->News->data['News']['title']);
            $date = empty($this->News->data['News']['date']) ? "" : date('r', strtotime($this->News->data['News']['date']));
            $item->addChild("pubDate", $date);
            $item->addChild("disable", "");
            // $item->addChild("content:encoded", $this->News->data['News']['detail'], "content");
            $node_content = $item->addChild("content:encoded", "", "content");
            $newsData = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $this->News->data['News']['detail']);
            $node_content->addCData($newsData);

            $xml->asXml("../webroot/news_feed.xml");

            return $this->redirect('/news_management');
        }
    }

    /**
     * ニュース編集
     */
    public function edit()
    {
        $id = $this->params['id'];
        $news = $this->News->getNews(null, $id);

        if ($this->request->is('get')) {
            if (empty($news)) {
                return $this->redirect('/news_management'); 
            }

            $this->set('news', $news[0]);
            $this->request->data[self::MODEL_NAME] = $news[0];

            return $this->render('edit');
        }


        $this->News->set($this->request->data);
        if (!$this->News->validates()) {
            return $this->render('edit');
        }

        // ニュースファイル読み込み
        $xml = simplexml_load_string(file_get_contents(News::NEWS_FEED_XML_PATH), 'SimpleXMLExtended');
        if (empty($xml)) {
            return $this->redirect('/news_management');
        }

        $target = null;
        foreach ($xml->channel->item as $v) {
            if($v->id != $id) {
                continue;
            }
            $target = $v;
        }

        $target->title = $this->News->data['News']['title'];
        $date = empty($this->News->data['News']['date']) ? "" : date('r', strtotime($this->News->data['News']['date']));
        $target->pubDate = $date;
        $target->disable = "";
        $target->children('content', true)->encoded = '';
        $newsData = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $this->News->data['News']['detail']);
        $target->children('content', true)->encoded->addCData($newsData);

        $xml->asXml("../webroot/news_feed.xml");

        return $this->redirect('/news_management');
    }

    /**
     * ニュース削除
     */
    public function delete()
    {
        if ($this->request->is('get')) {
            return $this->redirect('/news_management');
        }

        $id = $this->params['id'];

        // ニュースファイル読み込み
        $xml = simplexml_load_string(file_get_contents(News::NEWS_FEED_XML_PATH), 'SimpleXMLExtended');
        if (empty($xml)) {
            return $this->redirect('/news_management');
        }

        $target = null;
        foreach ($xml->channel->item as $v) {
            if($v->id != $id) {
                continue;
            }
            $target = $v;
        }

        // 無効日を設定
        $target->disable = date('r', time());

        $xml->asXml("../webroot/news_feed.xml");

        return $this->redirect('/news_management');
    }
}

class SimpleXMLExtended extends SimpleXMLElement
{
    public function addCData($cdata_text)
    {
        $node= dom_import_simplexml($this);
        $no = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($cdata_text));
    }
}
