<?php

App::uses('ApiCachedModel', 'Model');

class TimePrivate extends ApiCachedModel
{
    public function __construct()
    {
        parent::__construct('TIME_PRIVATE_CACHE', 'TimePrivate', '/time_private');
    }
}
