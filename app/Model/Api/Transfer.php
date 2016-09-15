<?php

App::uses('ApiModel', 'Model');

class Transfer extends ApiModel
{
    /**
    * API transfer 送金依頼
    */
    public function __construct()
    {
        parent::__construct('Transfer', '/transfer', 'minikura_v5');
    }

    public $validate = [];

}

