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
        ],
        'bank_account_holder' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'bank_account_holder']
            ],
        ],
    ];

    public function apiPost($data)
    {
        if (array_key_exists($this->model_name, $data)) {
            $data = $data[$this->model_name];
        }

        //$data['oem_key'] = $this->oem_key;
        $results = $this->request($this->end_point, $data, 'POST');

        return $results;
    }
}

