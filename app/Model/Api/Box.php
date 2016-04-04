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
                'required' => true,
                'message' => ['notBlank', 'box_id'],
            ],
        ],
        'box_name' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'box_name'],
            ],
            'maxLength' => [
                'rule' => ['maxLength', 15],
                'message' => ['maxLength', 'box_name', 15],
            ],
        ],
        'box_note' => [
            'maxLength' => [
                'rule' => ['maxLength', 1000],
                'message' => ['maxLength', 'box_note', 1000],
            ],
        ],
    ];
}
