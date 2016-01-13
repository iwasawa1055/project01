<?php

App::uses('AppHttp', 'Lib');
App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class PaymentGMOSecurityCard extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PaymentGMOSecurityCard', '/security_card', 'gmopayment_v4');

        // init setting
        // card_seq
        $this->data['PaymentGMOSecurityCard']['card_seq'] = '0';
        // default_flag
        $this->data['PaymentGMOSecurityCard']['default_flag'] = '1';
    }

    public $validate = [
        'card_no' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'クレジットカード番号は必須です'
             ],
            'isCreditCardNumber' => [
                'rule'     => ['isCreditCardNumber'],
                'message'  => 'クレジットカード番号の形式が正しくありません'
            ],
        ],
        'holder_name' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                 'message'  => 'クレジットカード名義は必須です'
             ],
            'isCreditCardHolderName' => [
                'rule'     => ['isCreditCardHolderName'],
                'message'  => 'クレジットカード名義の形式が正しくありません'
            ],
        ],
        'expire' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                 'message'  => '有効期限は必須です'
             ],
            'isCreditCardExpireReverse' => [
                'rule'     => ['isCreditCardExpireReverse'],
                'message'  => '有効期限の形式が正しくありません'
            ],
        ],
        'security_cd' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                 'message'  => 'セキュリティコードは必須です'
             ],
            'isCreditCardSecurityCode' => [
                'rule'     => ['isCreditCardSecurityCode'],
                'message'  => 'セキュリティコードの形式が正しくありません'
            ],
        ],
    ];

    public function setExpire($_data)
    {
        $this->data['PaymentGMOSecurityCard']['expire'] = $_data['expire_month'] . $_data['expire_year'];
    }

    public function trimHyphenCardNo($_data)
    {
        $card_no = $_data['card_no'];
        $this->data['PaymentGMOSecurityCard']['card_no'] = str_replace('-', '', $card_no);
    }
}
