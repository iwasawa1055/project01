<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');

class PaymentAmazonPay extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PaymentAmazonPay', '/amazon_pay', 'amazon_pay_v5');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
    }

    public $validate = [
        'amazon_billing_agreement_id' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'required' => true,
                'message'  => ['notBlank', 'amazon_pay_id'],
             ],
            'isAmazonPayId' => [
                'rule'     => 'isAmazonPayId',
                'message'  => ['format', 'amazon_pay_id'],
            ],
        ],
    ];

}
