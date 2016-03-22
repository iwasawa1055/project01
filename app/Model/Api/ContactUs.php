<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');

class ContactUs extends ApiModel
{
    public function __construct($name = 'ContactUs', $end = '/contact', $access_point_key = 'minikura_v3')
    {
        parent::__construct($name, $end, $access_point_key);
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
    }

    public $validate = [
        'division' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'contact_division'],
             ],
        ],
        'text' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'contact_text'],
             ],
        ],
    ];
}
