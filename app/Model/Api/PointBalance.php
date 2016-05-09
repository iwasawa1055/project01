<?php

App::uses('ApiModel', 'Model');

class PointBalance extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PointBalance', '/point_balance', 'cpss_v5');
    }
}
