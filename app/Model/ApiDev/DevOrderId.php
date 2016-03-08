<?php

App::uses('ApiModel', 'Model');

class DevOrderId extends ApiModel
{
    protected $checkZeroResultsKey = 'order_id';

    public function __construct()
    {
        parent::__construct('DevOrderId', '/dev_order_id');
    }
}
