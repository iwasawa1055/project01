<?php

App::uses('ApiCachedModel', 'Model');

class Announcement extends ApiCachedModel
{
    const SESSION_CACHE_KEY = 'ANNOUNCEMENT_CACHE';

    private $defaultSortKey = [
    ];

    public function __construct()
    {
        parent::__construct(self::SESSION_CACHE_KEY, 60, 'Announcement', '/announcement');
    }

    public $validate = [
        'announcement_id' => [
            'required' => false,
        ],
    ];
}
