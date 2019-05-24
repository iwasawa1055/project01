<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');
App::uses('InfoBox', 'Model');

class PaymentAmazonGiftAmazonPay extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PaymentAmazonGiftAmazonPay', '/purchase_gift', 'amazon_pay_v5');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
        (new InfoBox())->deleteCache();
    }

    public $validate = [
        'amazon_order_reference_id' => [
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
        'kit' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit'],
            ],
        ],
        'email' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'email'],
            ],
            'isMailAddress' => [
                'rule' => 'isMailAddress',
                'message' => ['format', 'email'],
            ],
        ],
        'sender_name' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'sender_name'],
            ],
        ],
        'email_message' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'email_message'],
            ],
        ],
        'gift_cleaning_num' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'gift_cleaning_num'],
            ],
            'range' => [
                'rule' => ['range', 0, 21],
                'message' => ['range', 'gift_cleaning_num', '1', '20']
            ],
        ],
    ];
}
