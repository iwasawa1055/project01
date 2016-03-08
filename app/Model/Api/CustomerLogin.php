<?php

App::uses('ApiModel', 'Model');

class CustomerLogin extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CustomerLogin', '/login', 'minikura_v5');
    }

    public function login()
    {
        $this->data[$this->model_name]['oem_key'] = $this->oem_key;
        $responses = $this->request('/login', $this->data[$this->model_name], 'GET');
        // api error
        if (empty($responses->error_message)) {
            CakeSession::write(self::SESSION_API_TOKEN, $responses->results[0]['token']);
            CakeSession::write(self::SESSION_API_DIVISION, $responses->results[0]['division']);
        }

        return $responses;
    }

    public function logout()
    {
        CakeSession::delete(self::SESSION_API_TOKEN);
        CakeSession::delete(self::SESSION_API_DIVISION);
    }

    public $validate = [
        'email' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => ['notBlank', 'email'],
            ],
            'isMailAddress' => [
                'rule' => 'isMailAddress',
                'message' => ['format', 'email'],
            ],
        ],
        'password' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => ['notBlank', 'password'],
             ],
        ],
    ];
}
