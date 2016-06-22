<?php

App::uses('ApiModel', 'Model');

class AnnouncementNoCache extends ApiModel
{
    public function __construct()
    {
        parent::__construct('AnnouncementNoCache', '/announcement');
    }

    public $validate = [
    ];
}
