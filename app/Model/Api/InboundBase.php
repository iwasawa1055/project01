<?php

App::uses('ApiModel', 'Model');

class InboundBase extends ApiModel
{

    public function __construct()
    {
        parent::__construct('InboundBase', '/dummy', 'minikura_v5');
    }
}