<?php

App::uses('ApiModel', 'Model');

class ReceiptOrderDetail extends ApiModel
{
    public function __construct()
    {
        parent::__construct('ReceiptOrderDetail', '/receipt_order_detail', 'minikura_v5');
    }

    public $validate = [
        'announcement_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'announcement_id'],
            ],
        ],
    ];
}
