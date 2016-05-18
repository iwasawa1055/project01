<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');
App::uses('InfoBox', 'Model');
App::uses('InfoItem', 'Model');

class ContactAny extends ApiModel
{
    public function __construct()
    {
        parent::__construct('ContactAny', '/contact_any');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
        (new InfoBox())->deleteCache();
        (new InfoItem())->deleteCache();
    }

    public $validate = [
        'title' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'title']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 50],
                'message' => ['maxLength', 'title', 50]
            ],
        ],
        'text' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'text']
            ],
        ],
        'contact_cd' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'contact_cd']
            ],
        ],
    ];
}
