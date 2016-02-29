<?php

App::uses('ApiCachedModel', 'Model');

class ContactUs extends ApiModel
{
    public function __construct($name = 'ContactUs', $end = '/contact', $access_point_key = 'minikura_v3')
    {
        parent::__construct($name, $end, $access_point_key);
    }

    public $validate = [
        'division' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => ['notBlank', 'contact_division'],
             ],
        ],
        'text' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => ['notBlank', 'contact_text'],
             ],
        ],
    ];
}
