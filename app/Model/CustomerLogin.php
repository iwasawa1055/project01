<?php

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
        $this->data[$this->model_name]['oem_key'] = $this->oem_key;
        $responses = $this->request('/login', $this->data[$this->model_name], 'GET');

        // api error
        if (empty($responses->error_message)) {
            CakeSession::write($this::SESSION_API_TOKEN, $responses->results['token']);
            CakeSession::write($this::SESSION_API_DIVISION, $responses->results['division']);
        } else {
            $responses->error_message = 'メールアドレスまたはパスワードに誤りがあるか、登録されていません。';
        }

        return $responses;
    }

    public function logout()
    {
        CakeSession::delete($this::SESSION_API_TOKEN);
        CakeSession::delete($this::SESSION_API_DIVISION);
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
        ],
    ];
}
