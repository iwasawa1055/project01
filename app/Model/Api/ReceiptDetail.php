<?php

App::uses('ApiModel', 'Model');

class ReceiptDetail extends ApiModel
{
    public function __construct()
    {
        parent::__construct('ReceiptDetail', '/receipt_detail', 'minikura_v5');
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
