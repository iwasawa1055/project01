<?php

App::uses('ApiModel', 'Model');

class CustomerKitPrice extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CustomerKitPrice', '/customer_kit_price', 'minikura_v5');
    }

    public $validate = [
        'kit' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit']
            ],
        ],
    ];
}
