<?php

App::uses('ApiCachedModel', 'Model');

class TimePickup extends ApiCachedModel
{
    public function __construct()
    {
        parent::__construct('TIME_PICKUP_CACHE', 0, 'TimePickup', '/time_pickup');
    }
}
