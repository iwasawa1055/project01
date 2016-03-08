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
                'message' => 'アイテムIDは必須です',
            ],
        ],
        'item_name' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'アイテム名は必須です',
            ],
            'maxLength' => [
                'rule' => ['maxLength', 400],
                'message' => 'アイテム名は400文字以内で入力してください',
            ],
        ],
        'item_note' => [
            'maxLength' => [
                'rule' => ['maxLength', 1000],
                'message' => 'アイテムノートは1000文字以内で入力してください',
            ],
        ],
    ];
}
