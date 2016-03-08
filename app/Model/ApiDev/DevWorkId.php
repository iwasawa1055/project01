<?php

App::uses('ApiModel', 'Model');

class DevWorkId extends ApiModel
{
    protected $checkZeroResultsKey = 'work_id';
    public function __construct()
    {
        parent::__construct('DevWorkId', '/dev_work_id');
    }
}
