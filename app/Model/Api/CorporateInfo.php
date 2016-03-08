<?php

App::uses('ApiModel', 'Model');

class CorporateInfo extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CorporateInfo', '/corporate');
    }

    public $validate = [
    ];
}
