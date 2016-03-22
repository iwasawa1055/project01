<?php

App::uses('ApiModel', 'Model');

class Receipt extends ApiModel
{
    public function __construct()
    {
        parent::__construct('Receipt', '/receipt');
    }

    public $validate = [
        'announcement_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'announcement_id'],
            ],

        ],
    ];
}
