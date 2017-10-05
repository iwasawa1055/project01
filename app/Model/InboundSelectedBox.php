<?php

App::uses('AppModel', 'Model');

/**
 * ボックス入庫のボックス情報モデル
 */
class InboundSelectedBox extends AppModel
{

    public function __construct()
    {
        parent::__construct('InboundSelectedBox');
    }

    // DB処理を行わない
    public $useTable = false;

    public $validate = [
        'title' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'box_name'],
            ],
            'maxLength' => [
                'rule' => ['maxLength', 200],
                'message' => ['maxLength', 'box_name', 200],
            ],
            'isNot4ByteString' => [
                'rule' => 'isNot4ByteString',
                'message' => ['isNot4ByteString', 'box'],
            ],
        ],
        'option' => [
            'maxLength' => [
                'rule' => ['between', 2, 2],
                'allowEmpty' => true,
                'message' => ['format', 'box_option'],
            ],
        ],
    ];
}
