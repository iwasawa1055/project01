<?php

App::uses('ApiCachedModel', 'Model');

class News extends ApiCachedModel
{
    const SESSION_CACHE_KEY = 'NEWS_CACHE';

    private $defaultSortKey = [
    ];

    public function __construct()
    {
        parent::__construct(self::SESSION_CACHE_KEY, 5, 'News', '/news');
    }

    public $validate = [
        'news_id' => [
            'required' => true,
        ],
    ];


    public function getNews()
    {
        $url = "http://news.minikura.com/info/news?cat=2&feed=rss2";
        $xml = simplexml_load_file($url, 'SimpleXMLElement', LIBXML_NOCDATA);
//        $xml = simplexml_load_file($url);

        $results = null;
        foreach ($xml->channel->item as $v) {

            $row['url'] = $v->link;
            $row['title'] = $v->title;
            $row['date'] = date('Y年n月j日', strtotime($v->pubDate));

            $results[] = $row;
            $row = null;
        }

        return $results;
    }
}
