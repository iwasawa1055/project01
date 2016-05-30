<?php

App::uses('ApiModel', 'Model');

class PointHistory extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PointHistory', '/point_history', 'cpss_v5');
    }

    public $validate = [
    ];
}
