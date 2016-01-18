<?php

App::uses('ApiModel', 'Model');

class CustomerAddress extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CustomerAddress', '/address');
    }

    public $validate = [
      'lastname' => [
        'notBlank' => [
            'rule' => 'notBlank',
            'message' => '苗字は必須です',
        ],
        'notBlank' => [
            'rule' => 'notBlank',
            'message' => '苗字は必須です',
        ],
      ],
      'lastname_kana' => [
        'notBlank' => [
            'rule' => 'notBlank',
            'message' => '苗字カナは必須です',
        ],
      ],
      'firstname' => [
        'notBlank' => [
          'rule' => 'notBlank',
          'message' => '名前は必須です',
        ],
      ],
      'firstname_kana' => [
        'notBlank' => [
            'rule' => 'notBlank',
            'message' => '名前カナは必須です',
        ],
      ],
      'tel1' => [
        'notBlank' => [
             'rule' => 'notBlank',
             'message' => 'メールアドレスは必須です',
        ],
      ],
      'postal' => [
        'notBlank' => [
              'rule' => 'notBlank',
              'message' => '郵便番号は必須です',
        ],
        'notBlank' => [
              'rule' => '/^\d{3}\-\d{4}$/i',
              'message' => '999-9999形式で入力してください',
        ],
      ],
      'pref' => [
        'notBlank' => [
         'rule' => 'notBlank',
         'message' => '都道府県は必須です',
        ],
        'notBlank22' => [
         'rule' => 'isPrefNameJp',
         'message' => '値が正しくありません',
        ],
      ],
      'address1' => [
        'notBlank' => [
         'rule' => 'notBlank',
         'message' => '市区は必須です',
        ],
        'maxLength' => [
         'rule' => ['maxLength', '8'],
         'message' => '市区は8文字以下です',
        ],
      ],
      'address2' => [
        'notBlank' => [
         'rule' => 'notBlank',
         'message' => '町村番地は必須です',
        ],
        'maxLength' => [
         'rule' => ['maxLength', '18'],
         'message' => '町村番地は18文字以下です',
        ],
      ],
      'address3' => [
        'maxLength' => [
         'rule' => ['maxLength', '30'],
         'message' => '建物名は30文字以下です',
        ],
      ],
    ];
}
