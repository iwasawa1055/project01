<?php

App::uses('ApiModel', 'Model');

class CustomerEntry extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CustomerEntry', '/entry', 'minikura_v5');
    }

    public $validate = [
        // 'email' => [
        //     'notBlank' => [
        //         'rule' => 'notBlank',
        //         'message' => 'メールアドレスは必須です',
        //      ],
        //     'isMailAddress' => [
        //         'rule' => 'isMailAddress',
        //         'message' => 'メールアドレスの形式が正しくありません',
        //     ],
        // ],
        // 'email_confirm' => [
        //     'notBlank' => [
        //         'rule' => 'notBlank',
        //         'message' => 'メールアドレス（再入力）は必須です',
        //      ],
        //     'isMailAddress' => [
        //         'rule' => 'isMailAddress',
        //         'message' => 'メールアドレス（再入力）の形式が正しくありません',
        //     ],
        //     'confirmEmail' => [
        //         'rule' => 'confirmEmail',
        //         'message' => 'メールアドレスが一致していません',
        //     ],
        // ],
    ];
    // 
    // public function confirmEmail()
    // {
    //     if ($this->data[$this->model_name]['email'] !== $this->data[$this->model_name]['email_confirm']) {
    //         return false;
    //     } else {
    //         return true;
    //     }
    // }
}
