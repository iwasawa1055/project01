<?php

App::uses('ApiModel', 'Model');

class CustomerEntry extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CustomerEntry', '/entry', 'minikura_v5');
    }

    public function entry()
    {
        $this->data[$this->model_name]['oem_key'] = $this->oem_key;
        $responses = $this->request('/entry', $this->data[$this->model_name], 'POST');
        // api error
        if (empty($responses->error_message)) {
        } else {
            $responses->error_message = 'ユーザー登録できませんでした。';
        }

        return $responses;
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
            'isLoginPassword' => [
                'rule' => 'isLoginPassword',
                'message' => ['format', 'password'],
            ],
        ],
        'password_confirm' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => ['notBlank', 'password_confirm'],
             ],
            'isLoginPassword' => [
                'rule' => 'isLoginPassword',
                'message' => ['format', 'password_confirm'],
            ],
        ],
        'newsletter' => [
            'allowedChoice' => [
                'rule' => ['inList', ['0', '1']],
                'message' => ['format', 'newsletter'],
            ],
        ],
    ];

    public function confirmPassword()
    {
        if ($this->data[$this->model_name]['password'] !== $this->data[$this->model_name]['password_confirm']) {
            return false;
        } else {
            return true;
        }
    }
}
