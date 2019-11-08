<?php

App::uses('ApiModel', 'Model');

class DevWorkLinkageId extends ApiModel
{
    protected $checkZeroResultsKey = 'linkage_id';
    public function __construct()
    {
        parent::__construct('DevWorkLinkageId', '/dev_work_linkage_id');
    }
}
