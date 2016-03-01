<?php

App::uses('ApiCachedModel', 'Model');

class TimePrivate extends ApiCachedModel
{
    public function __construct()
    {
        parent::__construct('TIME_PRIVATE_CACHE', 0, 'TimePrivate', '/time_private');
    }
}
