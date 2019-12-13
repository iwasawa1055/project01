<?php

App::uses('ApiModel', 'Model');

class V5Box extends ApiModel
{

    public function __construct()
    {
        parent::__construct('V5Box', '/box', 'minikura_v5');
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
                'rule' => ['maxLength', 200],
                'message' => ['maxLength', 'box_name', 200],
            ],
            'isNot4ByteString' => [
                'rule' => 'isNot4ByteString',
                'message' => ['isNot4ByteString', 'box_name'],
            ],
        ],
    ];
}
