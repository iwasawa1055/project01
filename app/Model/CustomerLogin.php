<?php

App::uses('AppHttp', 'Lib');
App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class CustomerLogin extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CustomerLogin', '/login');
    }

    public function isLogined()
    {
        return !empty(CakeSession::read($this::SESSION_API_TOKEN));
    }

    public function login()
    {
        // $params = [
            // 		'oem_key' => $this->oem_key,
            // 		'email' => $email,
            // 		'password' => $password,
            // ];

        $this->data[$this->model_name]['oem_key'] = $this->oem_key;
        $responses = $this->request('/login', $this->data[$this->model_name], 'GET');

        CakeSession::write($this::SESSION_API_TOKEN, $responses->results['token']);
        CakeSession::write($this::SESSION_API_DIVISION, $responses->results['division']);
        return $responses;
    }

    public function logout()
    {
        CakeSession::delete($this::SESSION_API_TOKEN);
        CakeSession::delete($this::SESSION_API_DIVISION);
    }

    public $validate = [
        // 'oem_key' => [
        // 	'required' => true,
        // ],
        'email' => [
            'rule' => ['maxLength', 29],
            'required' => true,
        ],
        'password' => [
            'rule' => '/^[0-9a-zA-Z!,.:?@^_~]{6,64}$/i',
            'required' => true,
        ],
    ];
}
