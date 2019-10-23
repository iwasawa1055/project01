<?php

App::uses('ApiModel', 'Model');

class InboundAndOutboundHistory extends ApiModel
{
    public function __construct()
    {
        parent::__construct('InboundAndOutboundHistory', '/inbound_and_outbound_history', 'minikura_v5');
    }
}
