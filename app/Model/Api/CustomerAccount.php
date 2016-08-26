<?php

App::uses('ApiModel', 'Model');

class CustomerAccount extends ApiModel
{
    /**
    * API 
    */
    public function __construct()
    {
        parent::__construct('CustomerAccount', '/customer_account', 'minikura_v5');
    }

    public $validate = [
        'bank_name' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'bank_name']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 50],
                'message' => ['maxLength', 'bank_name', 50]
            ],
            /*
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'message' => ['format', 'price']
            ],
            */
        ],
        'bank_branch_name' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'bank_branch_name']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 50],
                'message' => ['maxLength', 'bank_branch_name', 50]
            ],
        ],
        'bank_account_type' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'bank_account_type']
            ],
        ],
        'bank_account_number' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'bank_account_number']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 7],
                'message' => ['maxLength', 'bank_account_number', 7]
            ],
        ],
        'bank_account_holder' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'bank_account_holder']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 50],
                'message' => ['maxLength', 'bank_account_holder', 50]
            ],
        ],
    ];

}

