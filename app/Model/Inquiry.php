<?php

App::uses('ApiCachedModel', 'Model');

class Inquiry extends ApiModel
{
    public function __construct()
    {
        parent::__construct('Inquiry', '/contact');
    }

    public $validate = [
        'lastname' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '姓は必須です',
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => '姓は29文字以内で入力してください',
            ],
        ],
        'lastname_kana' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '姓（カナ）は必須です',
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => '姓（カナ）は29文字以内で入力してください',
            ],
            'isFwKana' => [
                'rule' => 'isFwKana',
                'message' => '姓（カナ）は全角カタカナで入力してください',
            ],
        ],
        'firstname' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '名は必須です',
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => '名は29文字以内で入力してください',
            ],
        ],
        'firstname_kana' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '名（カナ）は必須です',
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => '名（カナ）は29文字以内で入力してください',
            ],
            'isFwKana' => [
                'rule' => 'isFwKana',
                'message' => '名（カナ）は全角カタカナで入力してください',
            ],
        ],
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
        'division' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'お問い合わせ種別は必須です',
             ],
        ],
        'text' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'お問い合わせ内容は必須です',
             ],
        ],
    ];

    public function apiPost($data)
    {
        if (array_key_exists($this->model_name, $data)) {
            $data = $data[$this->model_name];
        }
        $data['oem_key'] = $this->oem_key;
        $d = $this->request($this->end_point, $data, 'POST');

        return $d;
    }
}
