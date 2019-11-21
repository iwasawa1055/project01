<?php

App::uses('ApiModel', 'Model');

class PickupYamato extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PickupYamato', '/pickup_yamato', 'minikura_v5');
    }

    public $validate = [
        'pickup_date' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'pickup_date']
            ],
            'isDate' => [
                'rule' => 'isDate',
                'message' => ['format', 'pickup_date'],
            ],
        ],
        'pickup_time' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'pickup_time']
            ],
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'message' => ['format', 'pickup_time']
            ],
        ],
    ];
}
