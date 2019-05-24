<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');
App::uses('InfoBox', 'Model');

class PaymentGMOPurchaseGift extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PaymentGMOPurchaseGift', '/purchase_gift', 'gmopayment_v5');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
        (new InfoBox())->deleteCache();
    }

    public $validate = [
        'security_cd' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'security_cd'],
            ],
            'isCreditCardSecurityCode' => [
                'rule' => 'isCreditCardSecurityCode',
                'message' => ['format', 'security_cd'],
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
