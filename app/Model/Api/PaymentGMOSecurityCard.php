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
                'required' => true,
                'message'  => ['notBlank', 'card_no'],
             ],
            'isCreditCardNumber' => [
                'rule'     => 'isCreditCardNumber',
                'message'  => ['format', 'card_no'],
            ],
        ],
        'holder_name' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'required' => true,
                'message'  => ['notBlank', 'holder_name'],
             ],
            'isCreditCardHolderName' => [
                'rule'     => 'isCreditCardHolderName',
                'message'  => ['format', 'holder_name'],
            ],
        ],
        'expire' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'required' => true,
                'message'  => ['notBlank', 'expire'],
             ],
            'isCreditCardExpireReverse' => [
                'rule'     => 'isCreditCardExpireReverse',
                'message'  => ['format', 'expire'],
            ],
        ],
        'card_seq' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'card_seq'],
            ],
            'isStringInteger' => [
                'rule'     => 'isStringInteger',
                'message'  => ['format', 'card_seq'],
            ],
        ],
        'security_cd' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'required' => true,
                'message'  => ['notBlank', 'security_cd'],
             ],
            'isCreditCardSecurityCode' => [
                'rule'     => ['isCreditCardSecurityCode'],
                'message'  => ['format', 'security_cd'],
            ],
        ],
        'expire_month' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'required' => true,
                'message'  => ['notBlank', 'expire'],
             ],
        ],
        'expire_year' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'required' => true,
                'message'  => ['notBlank', 'expire'],
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
