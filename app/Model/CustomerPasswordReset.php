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
    ];

    public function apiPut($data)
    {
        if (array_key_exists($this->model_name, $data)) {
            $data = $data[$this->model_name];
        }
        $data['oem_key'] = $this->oem_key;
        $data['new_password'] = 'happyhappy'; // TODO: 自動生成
            $d = $this->request($this->end_point, $data, 'PUT');

        return $d;
    }
}
