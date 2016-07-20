<?php

App::uses('ApiCachedModel', 'Model');

class News extends ApiCachedModel
{
    const SESSION_CACHE_KEY = 'NEWS_CACHE';
    const NEWS_FEED_XML_PATH = '../webroot/news_feed.xml';

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


    /**
     * @param $type 1: 全件 2: 新着
     * @param $id IDが指定された場合、個別記事を取得する
     */
    public function getNews($type = '1', $id = null)
    {
        if (NEWS_ACTIVE_FLAG === 0) {
            return [];
        }

        if (!file_exists(self::NEWS_FEED_XML_PATH)) {
            return [];
        }
        $xml = simplexml_load_file(self::NEWS_FEED_XML_PATH, 'SimpleXMLElement', LIBXML_NOCDATA);

        if (empty($xml)) {
            return [];
        }

        $results = null;
        foreach ($xml->channel->item as $v) {
            // disabled
            if(!empty($v->disable)) {
                continue;
            }

            // idが入っている場合、個別記事を取得するため、id以外の記事の場合continue
            if (!is_null($id)) {
                if($v->id != $id) {
                    continue;
                }
            }

            // id 取得
            $row['id'] = $v->id;

            $row['url'] = $v->link;
            $row['title'] = $v->title;
            // $row['date'] = date('Y年m月d日', strtotime($v->pubDate));
            $row['disp_date'] = date('Y年m月d日', strtotime($v->pubDate));
            $row['date'] = date('Y/m/d H:i', strtotime($v->pubDate));
            $row['detail'] = $v->children('content', true)->encoded;

            $results[] = $row;
            $row = null;

        }

        $results = Hash::sort($results, '{n}.date', 'desc');
        if ($type === 2) {
            $results = array_slice($results, 0, NEWS_LASTEST_ARTICLE_LIMIT);
        }

        return $results;
    }
}
