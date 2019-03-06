<?php

App::uses('ApiModel', 'Model');

class CustomerFacebook extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CustomerFacebook', '/customer', 'facebook_v5');
    }

    public function regist()
    {
        $responses = $this->apiPost($this->data[$this->model_name]);
        return $responses;
    }

    public $validate = [
        'facebook_user_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'facebook_user_id'],
            ],
        ],
    ];
}
