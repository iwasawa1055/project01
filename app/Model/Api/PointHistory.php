<?php

App::uses('ApiModel', 'Model');

class PointHistory extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PointHistory', '/point_history', 'cpss_v5');
    }

    public $validate = [
        'start_datetime' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'start_datetime'],
            ],
        ],
        'end_datetime' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'end_datetime'],
            ],
        ],
    ];
}
