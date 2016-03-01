<?php

App::uses('ApiModel', 'Model');
App::uses('PaymentGMOCard', 'Model');
App::uses('Announcement', 'Model');

class PaymentGMOSecurityCard extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PaymentGMOSecurityCard', '/security_card', 'gmopayment_v4');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new PaymentGMOCard())->deleteCache();
        (new Announcement())->deleteCache();
    }

    public $validate = [
        'card_no' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'クレジットカード番号は必須です'
             ],
            'isCreditCardNumber' => [
                'rule'     => 'isCreditCardNumber',
                'message'  => 'クレジットカード番号の形式が正しくありません'
            ],
        ],
        'holder_name' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                 'message'  => 'クレジットカード名義は必須です'
             ],
            'isCreditCardHolderName' => [
                'rule'     => 'isCreditCardHolderName',
                'message'  => 'クレジットカード名義の形式が正しくありません'
            ],
        ],
        'expire' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                 'message'  => '有効期限は必須です'
             ],
            'isCreditCardExpireReverse' => [
                'rule'     => 'isCreditCardExpireReverse',
                'message'  => '有効期限の形式が正しくありません'
            ],
        ],
        'card_seq' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                 'message'  => 'カード登録シーケンス値は必須です'
             ],
            'isStringInteger' => [
                'rule'     => 'isStringInteger',
                'message'  => 'カード登録シーケンス値の形式が正しくありません'
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
        'expire_month' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                 'message'  => '有効期限は必須です'
             ],
        ],
        'expire_year' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                 'message'  => '有効期限は必須です'
             ],
        ],
    ];

    public function setExpire($_data)
    {
        $this->data[$this->model_name]['expire'] = $_data[$this->model_name]['expire_month'] . $_data[$this->model_name]['expire_year'];
    }

    public function trimHyphenCardNo($_data)
    {
        $card_no = $_data[$this->model_name]['card_no'];
        $this->data[$this->model_name]['card_no'] = str_replace('-', '', $card_no);
    }

    public function setDisplayExpire($_data)
    {
        $this->data[$this->model_name]['expire_year_disp'] = $_data[$this->model_name]['expire_year'] + 2000;
    }
}
