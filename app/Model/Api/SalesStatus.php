<?php

App::uses('ApiModel', 'Model');

class SalesStatus extends ApiModel
{
    /**
    * API sales_status 販売ステータス
    */
    public function __construct()
    {
        parent::__construct('SalesStatus', '/sales_status', 'minikura_v5');
    }

    public $validate = [
        'sales_status' => [
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'message' => ['format', 'sales_status']
            ],
        ],
    ];

}

