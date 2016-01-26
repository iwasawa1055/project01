<?php

App::uses('ApiModel', 'Model');

class PaymentGMOKitCard extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PaymentGMOKitCard', '/kit_card', 'gmopayment_v4');
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

        'card_seq' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'カード登録連番は必須です',
            ],
        ],
        'security_cd' => [
            'notBlank' => [
                'rule' => 'notBlank',
                 'message' => 'セキュリティコードは必須です',
            ],
            'isCreditCardSecurityCode' => [
                'rule' => 'isCreditCardSecurityCode',
                'message' => 'セキュリティコードの形式が正しくありません',
            ],
        ],
        'kit' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'キットは必須です',
            ],
        ],
        'name' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '配送先名は必須です',
            ],
            'maxLength' => [
                'rule' => ['maxLength', 50],
                'message' => '配送先名は50文字以下で入力してください',
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
        'address' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '配送先住所は必須です',
            ],
            'maxLength' => [
                'rule' => ['maxLength', 60],
                'message' => '配送先住所は60文字以下で入力してください',
            ],
        ],
        'datetime_cd' => [
            'notBlank' => [
                'rule' => 'notBlank',
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
