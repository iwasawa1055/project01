<?php

App::uses('ApiModel', 'Model');

class CustomerPassword extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CustomerPassword', '/password');
    }

    public $validate = [
        'password' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '現在のパスワードは必須です',
             ],
        ],
        'new_password' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '新しいパスワードは必須です',
             ],
            'isLoginPassword' => [
                'rule' => 'isLoginPassword',
                'message' => '新しいパスワードの形式が正しくありません',
            ],
        ],
        'new_password_confirm' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '新しいパスワード（再入力）は必須です',
             ],
            'isLoginPassword' => [
                'rule' => 'isLoginPassword',
                'message' => '新しいパスワード（再入力）の形式が正しくありません',
            ],
            'confirmPassword' => [
                'rule' => 'confirmPassword',
                'message' => '新しいパスワード（再入力）が一致していません',
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
}
