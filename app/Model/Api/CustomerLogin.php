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

    public function logout()
    {
        CakeSession::delete(self::SESSION_API_TOKEN);
        CakeSession::delete(self::SESSION_API_DIVISION);
        CakeSession::delete(self::SESSION_AMAZON_PAY_ACCESS_KEY);
    }

    public $validate = [
        'email' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
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
                'required' => true,
                'message' => ['notBlank', 'password'],
             ],
        ],
    ];
}
