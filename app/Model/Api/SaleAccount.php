<?php

App::uses('ApiModel', 'Model');

class SaleAccount extends ApiModel
{
    /**
    * API名は未定、以下はひとまず暫定
    */
    public function __construct()
    {
        parent::__construct('SaleAccount', '/sale_account', 'minikura_v5');
    }

    public $validate = [
    /*
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
    */
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

