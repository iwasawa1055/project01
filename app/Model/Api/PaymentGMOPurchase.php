<?php

App::uses('ApiModel', 'Model');

class PaymentGMOPurchase extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PaymentGMOPurchase', '/purchase', 'gmopayment_v5');
    }

    public $validate = [
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
    ];

    public function setAddress($data, $address)
    {
        if (empty($address)) {
            return $data;
        }

        // $data['name'] = "{$address['lastname']}　{$address['firstname']}";
        // $data['tel1'] = $address['tel1'];
        // $data['postal'] = $address['postal'];
        // $data['address'] = "{$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}";

        $data['lastname'] = $address['lastname'];
        $data['firstname'] = $address['firstname'];
        $data['lastname_kana'] = $address['lastname_kana'];
        $data['firstname_kana'] = $address['firstname_kana'];
        $data['tel1'] = $address['tel1'];
        $data['postal'] = $address['postal'];
        $data['pref'] = $address['pref'];
        $data['address1'] = $address['address1'];
        $data['address2'] = $address['address2'];
        $data['address3'] = $address['address3'];

        return $data;
    }
}
