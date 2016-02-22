<?php

App::uses('ApiCachedModel', 'Model');

class DatePickup extends ApiCachedModel
{
    public function __construct()
    {
        parent::__construct('DATE_PICKUP_CACHE', 'DatePickup', '/date_pickup');
    }
}
