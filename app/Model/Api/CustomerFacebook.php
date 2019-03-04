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
        $this->data[$this->model_name]['oem_key'] = $this->oem_key;
        $responses = $this->request('/customer', $this->data[$this->model_name], 'POST');

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
        'access_token' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'facebook_token'],
            ],
        ],
    ];
}
