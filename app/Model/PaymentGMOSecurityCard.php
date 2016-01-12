<?php

App::uses('AppHttp', 'Lib');
App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class PaymentGMOSecurityCard extends ApiModel
{
    public function __construct()
    {
        // parent::__construct('PaymentGMOSecurityCard', '/security_card', 'gmopayment_v4');
        parent::__construct('PaymentGMOSecurityCard', '/security_card', 'gmopayment_v4');

        // default setting
        // card_seq
        $this->data['PaymentGMOSecurityCard']['card_seq'] = '0';
        // default_flag
        $this->data['PaymentGMOSecurityCard']['default_flag'] = '1';
    }

    public $validate = [
        'card_no' => [
            'notEmpty' => [
                'rule'     => 'notEmpty',
                'message'  => '必須入力です'
            ],
            'numeric' => [
                'rule'     => 'numeric',
                'message'  => 'numbers only'
            ],
            'between' => [
                'rule' => ['between', 14, 16],
                'message' => 'Between 14 to 16 characters'
            ],
        ],
        'holder_name' => [
            'rule' => ['maxLength', 29],
            'required' => true,
            'allowEmpty' => false,
        ],
        'expire' => [
            'rule' => ['maxLength', 4],
            'required' => true,
            'allowEmpty' => false,
        ],
        'card_seq' => [
            'rule' => ['maxLength', 29],
            'required' => true,
            'allowEmpty' => false,
        ],
        'security_cd' => [
            'rule' => ['maxLength', 29],
            'required' => true,
            'allowEmpty' => false,
        ],
        'card_name' => [
            'rule' => ['maxLength', 29],
        ],
        'default_flag' => [
            'rule' => ['maxLength', 29],
        ],
    ];
}
