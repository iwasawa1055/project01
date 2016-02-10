<?php

App::uses('InboundBase', 'Model');

class InboundManual extends InboundBase
{
    public function __construct()
    {
        parent::__construct('InboundManual', '/inbound_manual');
    }
}
