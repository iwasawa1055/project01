<?php

App::uses('ApiModel', 'Model');

class EntryCustomerEnv extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CustomerEnvAuthed', '/entry_customer_env', 'minikura_v5');
    }

    public $validate = [
    ];

    public function apiPostEnv($email)
    {
        $this->apiPost([
            'email' => $email,
            'ip_address' => env('REMOTE_ADDR'),
            'user_agent' => env('HTTP_USER_AGENT'),
            'referer' => env('HTTP_REFERER')
        ]);
    }
}
