<?php

App::uses('ApiModel', 'Model');

class Item extends ApiModel
{
    public function __construct()
    {
        parent::__construct('Item', '/item');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new InfoItem())->deleteCache();
    }

    public $validate = [
        'item_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'item_id']
            ],
        ],
        'item_name' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'item_name'],
            ],
            'maxLength' => [
                'rule' => ['maxLength', 400],
                'message' => ['maxLength', 'item_name', 400],
            ],
            'isNot4ByteString' => [
                'rule' => 'isNot4ByteString',
                'message' => ['isNot4ByteString', 'item_name'],
            ],
        ],
        'item_note' => [
            'maxLength' => [
                'rule' => ['maxLength', 1000],
                'message' => ['maxLength', 'item_note', 1000],
            ],
            'isNot4ByteString' => [
                'rule' => 'isNot4ByteString',
                'message' => ['isNot4ByteString', 'item_note'],
            ],
        ],
    ];
}
