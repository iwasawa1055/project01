<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');

class InboundManual extends ApiModel
{
    public function __construct()
    {
        parent::__construct('InboundManual', '/inbound_manual');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
    }

}
