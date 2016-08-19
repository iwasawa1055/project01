<?php

App::uses('ApiModel', 'Model');

class CustomerSales extends ApiModel
{
    /**
    * API名は未定、以下はひとまず暫定
    */
    public function __construct()
    {
        parent::__construct('CustomerSales', '/customer_sales', 'minikura_v5');
    }

    public $validate = [
        'sales_flag' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'sales_flag']
            ],
        ],
    ];

}

