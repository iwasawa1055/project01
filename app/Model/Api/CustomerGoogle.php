<?php

App::uses('ApiModel', 'Model');

class CustomerGoogle extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CustomerGoogle', '/customer', 'google_v5');
    }

    public function regist()
    {
        $responses = $this->apiPost($this->data[$this->model_name]);
        return $responses;
    }

    public $validate = [
        'google_user_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'google_user_id'],
            ],
        ],
    ];
}