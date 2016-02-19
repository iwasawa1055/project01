<?php

App::uses('ApiModel', 'Model');

class PaymentAccountTransferKit extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PaymentAccountTransferKit', '/kit');
    }

    public $validate = [
        'mono_num' => [
            'checkNotEmpty' => [
                'rule' => 'checkNotEmpty',
                'message' => 'いずれかのボックスを選択してください',
            ],
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'allowEmpty' => true,
                'message' => 'オーダー数の形式が正しくありません（minikuraMONO）',
            ],
        ],
        'hako_num' => [
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'allowEmpty' => true,
                'message' => 'オーダー数の形式が正しくありません（minikurHAKO）',
            ],
        ],
        'cleaning_num' => [
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'allowEmpty' => true,
                'message' => 'オーダー数の形式が正しくありません（クリーニングパック）',
            ],
        ],
        'address_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'お届け先は必須です',
            ],
        ],


        'kit' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'キットは必須です',
            ],
        ],
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
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => '名（カナ）は29文字以内で入力してください',
            ],
            'isFwKana' => [
                'rule' => 'isFwKana',
                'message' => '名（カナ）は全角カタカナで入力してください',
            ],
        ],
        'tel1' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '配送先電話番号は必須です',
            ],
            'isPhoneNumberJp' => [
                'rule' => 'isPhoneNumberJp',
                'message' => '配送先電話番号の形式が正しくありません',
            ],
        ],
        'postal' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '配送先郵便番号は必須です',
            ],
            'isPostalCodeJp' => [
                'rule' => 'isPostalCodeJp',
                'message' => '配送先郵便番号の形式が正しくありません',
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
        'datetime_cd' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => '配送日時は必須です',
            ],
            'isDatetimeDelivery' => [
                'rule' => 'isDatetimeDelivery',
                'message' => '配送日時の形式が正しくありません',
            ],
        ],
    ];

    public function checkNotEmpty()
    {
        if (!empty($this->data[$this->model_name]['mono_num']) ||
            !empty($this->data[$this->model_name]['hako_num']) ||
            !empty($this->data[$this->model_name]['cleaning_num'])) {
            return true;
        } else {
            return false;
        }
    }
}
