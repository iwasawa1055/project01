<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');
App::uses('ContactUs', 'Model');

class ContactUsCorporate extends ContactUs
{
    public function __construct()
    {
        parent::__construct('ContactUsCorporate', '/contact_corporate', $access_point_key = 'minikura_v3');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
    }
}
