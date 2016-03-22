<?php

App::uses('ApiModel', 'Model');

class CustomerPasswordReset extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CustomerPasswordReset', '/password');
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
        'new_password' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'new_password'],
             ],
            'isLoginPassword' => [
                'rule' => 'isLoginPassword',
                'message' => ['format', 'new_password'],
            ],
        ],
        'new_password_confirm' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'new_password_confirm'],
             ],
            'isLoginPassword' => [
                'rule' => 'isLoginPassword',
                'message' => ['format', 'new_password_confirm'],
            ],
            'confirmPassword' => [
                'rule' => 'confirmPassword',
                'message' => ['confirm', 'new_password_confirm'],
            ],
        ],
    ];

    public function confirmPassword()
    {
        if ($this->data[$this->model_name]['new_password'] !== $this->data[$this->model_name]['new_password_confirm']) {
            return false;
        } else {
            return true;
        }
    }

    public function apiPut($data)
    {
        if (array_key_exists($this->model_name, $data)) {
            $data = $data[$this->model_name];
        }
        $data['oem_key'] = $this->oem_key;
        $d = $this->request($this->end_point, $data, 'PUT');

        return $d;
    }
}
