<?php

App::uses('ApiModel', 'Model');

class InfoItem extends ApiModel
{
    public function __construct()
    {
        parent::__construct('InfoItem', '/info_item');
    }

    public $validate = [
    ];
}
