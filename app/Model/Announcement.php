<?php

App::uses('AppHttp', 'Lib');
App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class Announcement extends ApiModel
{
    public function __construct()
    {
        parent::__construct('Announcement', '/announcement');
    }

    public $validate = [
        'announcement_id' => [
            'required' => false,
        ],
    ];
}
