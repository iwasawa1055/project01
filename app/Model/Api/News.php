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
}
