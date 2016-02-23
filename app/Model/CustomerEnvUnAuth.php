<?php

App::uses('ApiModel', 'Model');

class CustomerEnvUnAuth extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CustomerEnvUnAuth', '/customer_env_unauth', 'minikura_v5');
    }

    public $validate = [
    ];

    public function apiPostEnv($email = null)
    {
        $aaaa = $this->apiPost([
            'oem_key' => $this->oem_key,
            'ip_address' => env('REMOTE_ADDR'),
            'user_agent' => env('HTTP_USER_AGENT'),
            'referer' => env('HTTP_REFERER'),
            'email' => $email
        ]);
    }
}
