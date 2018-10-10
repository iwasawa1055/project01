<?php

App::uses('ApiModel', 'Model');

class AmazonPayInfo extends ApiModel
{
    /**
    * API
    */
    public function __construct()
    {
        parent::__construct('AmazonPayInfo', '/amazon_pay_info', 'amazon_pay_v5');
    }

    public $validate = [
        'amazon_user_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'amazon_user_id']
            ],
        ],
        'amazon_billing_agreement_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'amazon_billing_agreement_id']
            ],
        ],
    ];
}
