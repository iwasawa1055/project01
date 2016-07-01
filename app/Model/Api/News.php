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


    /**
     * @param $type 1: 全件 2: 新着
     * @param $id IDが指定された場合、個別記事を取得する
     */
    public function getNews($type = '1', $id = null)
    {
        if (NEWS_ACTIVE_FLAG === 0) {
            return [];
        }
        $xml = simplexml_load_file(NEWS_FEED_URL, 'SimpleXMLElement', LIBXML_NOCDATA);

        $results = null;
        foreach ($xml->channel->item as $v) {
            // id 取得
            $url_params = explode('/', $v->link);
            krsort($url_params);
            $url_params = array_values($url_params);
            $row['id'] = $url_params[0];

            // idが入っている場合、個別記事を取得するため、id以外の記事の場合continue
            if (!is_null($id)) {
                if($row['id'] != $id) {
                    continue;
                }
            }

            $row['url'] = $v->link;
            $row['title'] = $v->title;
            $row['date'] = date('Y年m月d日', strtotime($v->pubDate));
            $row['detail'] = $v->children('content', true)->encoded;

            $results[] = $row;
            $row = null;

            if ($type === 2) {
                if(count($results) === NEWS_LASTEST_ARTICLE_LIMIT) {
                    break;
                }
            }
        }

        return $results;
    }
}
