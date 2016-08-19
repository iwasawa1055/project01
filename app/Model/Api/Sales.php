<?php

App::uses('ApiModel', 'Model');

class Sales extends ApiModel
{
    /**
    * API sales 販売情報
    */
    public function __construct()
    {
        parent::__construct('Sales', '/sales', 'minikura_v5');
    }

    public $validate = [
        'sales_title' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'sales_title']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 400],
                'message' => ['maxLength', 'sales_title', 400]
            ],
        ],
        'price' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'price']
            ],
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'message' => ['format', 'price']
            ],
        ],
        'sales_note' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'sales_note']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 1000],
                'message' => ['maxLength', 'sales_note', 1000]
            ],
        ],
        'item_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'item_id']
            ],
        ],
    ];

}

