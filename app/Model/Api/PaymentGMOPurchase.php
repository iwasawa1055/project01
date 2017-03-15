<?php

App::uses('ApiModel', 'Model');

class PaymentGMOPurchase extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PaymentGMOPurchase', '/purchase', 'gmopayment_v5');
    }

    public $validate = [
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
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'security_cd'],
            ],
            'isCreditCardSecurityCode' => [
                'rule' => 'isCreditCardSecurityCode',
                'message' => ['format', 'security_cd'],
            ],
        ],
        'sales_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'sales_id']
            ],
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'message' => ['format', 'sales_id']
            ],
        ],
        'name' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_address_name']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 59],
                'message' => ['maxLength', 'kit_address_name', 59]
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'kit_address_name']
            ],
            'checkHalfWordSpace' => [
                'rule' => 'checkHalfWordSpace',
                'message' => ['notBlank', 'kit_address_name']
            ],
        ],
        'tel1' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_tel1']
            ],
            'isPhoneNumberJp' => [
                'rule' => 'isPhoneNumberJp',
                'message' => ['format', 'kit_tel1']
            ],
        ],
        'postal' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_postal']
            ],
            'isPostalCodeJp' => [
                'rule' => 'isPostalCodeJp',
                'message' => ['format', 'kit_postal']
            ],
        ],
        'address' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_address']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 60],
                'message' => ['maxLength', 'kit_address', 60]
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'kit_address']
            ],
            'checkHalfWordSpace' => [
                'rule' => 'checkHalfWordSpace',
                'message' => ['notBlank', 'kit_address']
            ],
        ],
        'datetime_cd' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_datetime']
            ],
            'isDatetimeDelivery' => [
                'rule' => 'isDatetimeDelivery',
                'message' => ['format', 'kit_datetime']
            ],
        ],
        'address_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_address_name']
            ],
        ],
    ];

    public function setAddress($data, $address)
    {
        if (empty($address)) {
            return $data;
        }

        $data['name'] = "{$address['lastname']}　{$address['firstname']}";
        $data['tel1'] = $address['tel1'];
        $data['postal'] = $address['postal'];
        $data['address'] = "{$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}";

        return $data;
    }
}
