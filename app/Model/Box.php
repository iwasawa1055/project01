<?php

App::uses('ApiModel', 'Model');

class Box extends ApiModel
{
    public function __construct()
    {
        parent::__construct('Box', '/box');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new InfoBox())->deleteCache();
    }

    public $validate = [
        'box_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'ボックスIDは必須です',
            ],
        ],
        'box_name' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'ボックス名は必須です',
            ],
            'maxLength' => [
                'rule' => ['maxLength', 400],
                'message' => 'ボックス名は400文字以内で入力してください',
            ],
        ],
        'box_note' => [
            'maxLength' => [
                'rule' => ['maxLength', 1000],
                'message' => 'ボックスノートは1000文字以内で入力してください',
            ],
        ],
    ];
}
