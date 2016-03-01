<?php

App::uses('ApiCachedModel', 'Model');

class DatePrivate extends ApiCachedModel
{
    public function __construct()
    {
        parent::__construct('DATE_PRIVATE_CACHE', 0, 'DatePrivate', '/date_private');
    }
}
