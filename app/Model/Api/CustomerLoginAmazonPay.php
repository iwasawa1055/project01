<?php

App::uses('ApiModel', 'Model');

class CustomerLoginAmazonPay extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CustomerLoginAmazonPay', '/login', 'amazon_pay_v5');
    }

    public function login()
    {
        $this->data[$this->model_name]['oem_key'] = $this->oem_key;
        $responses = $this->request('/login', $this->data[$this->model_name], 'GET');
        // api error
        if (empty($responses->error_message)) {
            CakeSession::write(self::SESSION_API_TOKEN, $responses->results['token']);
            CakeSession::write(self::SESSION_API_DIVISION, $responses->results['division']);

            //* Login Flag Set For contents.minikura.com Session
            //** Session Switch To contents.minikura.com
            session_write_close();
            ini_set('session.cookie_domain', '.minikura.com');
            $session_name = 'WWWMINIKURACOM';
            session_name($session_name);
            if (! empty($_COOKIE[$session_name])) {
                session_id($_COOKIE[$session_name]);
            }
            session_start();

            //** Login Flag Set
            $_SESSION['api.token'] = true;

            //** Session Switch To mypage.minikura.com
            session_write_close();
            Configure::write('session.cookie_domain', '.mypage.minikura.com');
            $session_name = 'MINIKURACOM';
            session_name($session_name);
            session_id($_COOKIE[$session_name]);
            session_start();
        }
        return $responses;
    }

    public $validate = [
        'amazon_user_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'amazon_user_id'],
            ],
        ],
        'access_token' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'access_token'],
            ],
        ],
    ];
}
