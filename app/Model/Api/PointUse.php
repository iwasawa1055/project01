<?php

App::uses('ApiModel', 'Model');

class PointUse extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PointUse', '/point_use', 'cpss_v5');
    }

    public $validate = [
        'use_point' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'use_point'],
            ],
        ],
    ];
}
