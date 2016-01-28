<?php

App::uses('ApiModel', 'Model');

class CustomerInfo extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CustomerInfo', '/user');
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
        'gender' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '性別は必須です',
            ],
            'allowedChoice' => [
                'rule' => ['inList', ['m', 'f']],
                'message' => '性別の形式が正しくありません',
            ],
        ],
        'birth' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '生年月日は必須です',
            ],
            'isDate' => [
                'rule' => 'isDate',
                'message' => '生年月日の形式が正しくありません',
            ],
        ],
        'tel1' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '電話番号は必須です',
            ],
            'isPhoneNumberJp' => [
                'rule' => 'isPhoneNumberJp',
                'message' => '電話番号の形式が正しくありません',
            ],
        ],
        'postal' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '郵便番号は必須です',
            ],
            'isPostalCodeJp' => [
                'rule' => 'isPostalCodeJp',
                'message' => '郵便番号の形式が正しくありません',
            ],
        ],
        'pref' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '都道府県は必須です',
            ],
            'isPrefNameJp' => [
                'rule' => 'isPrefNameJp',
                'message' => '都道府県の形式が正しくありません',
            ],
        ],
        'address1' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '住所は必須です',
            ],
            'maxLength' => [
                'rule' => ['maxLength', 8],
                'message' => '住所は8文字以内で入力してください',
            ],
        ],
        'address2' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '番地は必須です',
            ],
            'maxLength' => [
                'rule' => ['maxLength', 18],
                'message' => '番地は18文字以内で入力してください',
            ],
        ],
        'address3' => [
            'maxLength' => [
                'rule' => ['maxLength', 30],
                'allowEmpty' => true,
                'message' => '番地は30文字以内で入力してください',
            ],
        ],
        'newsletter' => [
            'allowedChoice' => [
                'rule' => ['inList', ['0', '1']],
                'message' => '配信希望の形式が正しくありません',
            ],
        ],
    ];
}
