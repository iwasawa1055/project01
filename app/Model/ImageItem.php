<?php

App::uses('ApiModel', 'Model');

class ImageItem extends ApiModel
{
    public function __construct()
    {
        parent::__construct('ImageItem', '/image_item');
    }

    public $validate = [
    ];
}
