<?php

App::uses('ApiModel', 'Model');

class PaymentGMOKitCard extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PaymentGMOKitCard', '/kit');
    }

    public $validate = [
        'card_seq' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'カード登録連番は必須です',
            ],
        ],
        'security_cd' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                 'message'  => 'セキュリティコードは必須です'
             ],
            'isCreditCardSecurityCode' => [
                'rule'     => 'isCreditCardSecurityCode',
                'message'  => 'セキュリティコードの形式が正しくありません'
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
            'isDatetimeDelivery' => [
                'rule' => 'isDatetimeDelivery',
                'message' => '配送日時の形式が正しくありません',
            ],
        ],
    ];

    // public function confirmEmail()
    // {
    //     if ($this->data[$this->model_name]['email'] !== $this->data[$this->model_name]['email_confirm']) {
    //         return false;
    //     } else {
    //         return true;
    //     }
    // }
}
