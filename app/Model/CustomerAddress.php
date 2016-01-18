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
            'message' => 'メールアドレスは必須です',
        ],
      ],
      'lastname_kana' => [
        'notBlank' => [
            'rule' => 'notBlank',
            'message' => 'メールアドレスは必須です',
        ],
      ],
      'firstname' => [
        'notBlank' => [
          'rule' => 'notBlank',
          'message' => 'メールアドレスは必須です',
        ],
      ],
      'firstname_kana' => [
        'notBlank' => [
            'rule' => 'notBlank',
            'message' => 'メールアドレスは必須です',
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
              'message' => 'メールアドレスは必須です',
        ],
      ],
      'pref' => [
        'notBlank' => [
         'rule' => 'notBlank',
         'message' => 'メールアドレスは必須です',
        ],
      ],
      'address1' => [
        'notBlank' => [
         'rule' => 'notBlank',
         'message' => 'メールアドレスは必須です',
        ],
      ],
      'address2' => [
        'notBlank' => [
         'rule' => 'notBlank',
         'message' => 'メールアドレスは必須です',
        ],
      ],
      'address3' => [
        'notBlank' => [
         'rule' => 'notBlank',
         'message' => 'メールアドレスは必須です',
        ],
      ],
    ];
}
