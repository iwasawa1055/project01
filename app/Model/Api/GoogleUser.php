<?php

App::uses('ApiModel', 'Model');

class GoogleUser extends ApiModel
{
    public function __construct()
    {
        parent::__construct('GoogleUser', '/google_user', 'google_v5');
    }

    public function get_account_data()
    {
        $this->data[$this->model_name]['oem_key'] = $this->oem_key;
        $responses = $this->request('/google_user', $this->data[$this->model_name], 'GET');

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
        'access_token' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'access_token'],
            ],
        ],
        'id_token' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'id_token'],
            ],
        ],
    ];
}
