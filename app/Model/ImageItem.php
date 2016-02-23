<?php

App::uses('ApiCachedModel', 'Model');

class ImageItem extends ApiCachedModel
{
    const SESSION_CACHE_KEY = 'IMAGE_ITEM_CACHE';

    public function __construct()
    {
        parent::__construct(self::SESSION_CACHE_KEY, 'ImageItem', '/image_item');
    }

    public $validate = [
    ];
}
