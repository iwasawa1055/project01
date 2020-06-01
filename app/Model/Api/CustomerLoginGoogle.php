<?php

App::uses('ApiModel', 'Model');

class CustomerLoginGoogle extends ApiModel
{
    public function __construct()
    {
        // TODO API実装後に確認すること
        parent::__construct('CustomerLoginGoogle', '/login', 'google_v5');
    }

    public function login()
    {
        // TODO API実装後に確認すること
        $this->data[$this->model_name]['oem_key'] = $this->oem_key;
        $responses = $this->request('/login', $this->data[$this->model_name], 'GET');
        // api error
        if (empty($responses->error_message)) {
            CakeSession::write(self::SESSION_API_TOKEN, $responses->results[0]['token']);
            CakeSession::write(self::SESSION_API_DIVISION, $responses->results[0]['division']);
            CakeSession::write(CustomerLogin::SESSION_GOOGLE_ACCESS_KEY, $this->data[$this->model_name]['access_token']);

        }
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
