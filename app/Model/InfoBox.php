<?php

App::uses('ApiModel', 'Model');

class InfoBox extends ApiModel
{
    public function __construct()
    {
        parent::__construct('InfoBox', '/info_box');
    }

    public $validate = [
    ];
}
