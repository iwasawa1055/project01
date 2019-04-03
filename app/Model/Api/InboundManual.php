<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');
App::uses('InfoBox', 'Model');

class InboundManual extends ApiModel
{
    public function __construct()
    {
        parent::__construct('InboundManual', '/inbound_manual', 'minikura_v5');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
        (new InfoBox())->deleteCache();
    }


    public $validate = [
        'box' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['select', 'box'],
            ],
        ],
    ];
}
