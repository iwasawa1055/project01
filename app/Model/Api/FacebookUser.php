<?php

App::uses('ApiModel', 'Model');

class FacebookUser extends ApiModel
{
    public function __construct()
    {
        parent::__construct('FacebookUser', '/facebook_user', 'facebook_v5');
    }

    public function get_account_data()
    {
        $this->data[$this->model_name]['oem_key'] = $this->oem_key;
        $responses = $this->request('/facebook_user', $this->data[$this->model_name], 'GET');

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
