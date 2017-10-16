<?php

App::uses('ApiModel', 'Model');

class PaymentGMOCreditCard extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PaymentGMOCreditCard', '/credit_card', 'gmopayment_v4');
    }

    public $validate = [
        'gmo_token' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'required' => true,
                'message'  => ['notBlank', 'gmo_token'],
            ],
        ],
    ];
}
