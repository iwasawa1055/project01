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
                'message' => 'メールアドレスは必須です',
             ],
            'isMailAddress' => [
                'rule' => 'isMailAddress',
                'message' => 'メールアドレスの形式が正しくありません',
            ],
        ],
        'password' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'パスワードは必須です',
             ],
            'isLoginPassword' => [
                'rule' => 'isLoginPassword',
                'message' => 'パスワードの形式が正しくありません',
            ],
        ],
        'password_confirm' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'パスワード（確認用）は必須です',
             ],
            'isLoginPassword' => [
                'rule' => 'isLoginPassword',
                'message' => 'パスワード（確認用）の形式が正しくありません',
            ],
        ],
        'newsletter' => [
            'allowedChoice' => [
                'rule' => ['inList', ['0', '1']],
                'message' => 'お知らせ配信の形式が正しくありません',
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
