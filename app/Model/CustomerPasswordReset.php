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
                    'message' => 'メールアドレスは必須です',
             ],
             'isMail' => [
                    'rule' => ['isMailAddress'],
                    'message' => 'メールアドレスの形式が正しくありません',
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
