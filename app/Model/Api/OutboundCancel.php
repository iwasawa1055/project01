<?php

App::uses('ApiModel', 'Model');

class OutboundCancel extends ApiModel
{
    public function __construct()
    {
        parent::__construct('OutboundCancel', '/outbound_cancel', 'minikura_v5');
    }
}
