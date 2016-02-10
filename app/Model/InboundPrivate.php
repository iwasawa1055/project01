<?php

App::uses('InboundBase', 'Model');

class InboundPrivate extends InboundBase
{
    public function __construct()
    {
        parent::__construct('InboundPrivate', '/inbound_private');
    }

    public $validate = [
        // 'delivery_carrier' => [
        //     'notBlank' => [
        //         'rule' => 'notBlank',
        //         'message' => '預け入れ方法は必須です',
        //     ],
        // ],
        // 'address_cd' => [
        //     'notBlank' => [
        //         'rule' => 'notBlank',
        //         'message' => '集荷の住所は必須です',
        //     ],
        // ],
        'date_cd' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '集荷の日程は必須です',
            ],
        ],
        'time_cd' => [
           'notBlank' => [
                'rule' => 'notBlank',
                'message' => '集荷の時間は必須です',
            ],
        ]
    ];
}