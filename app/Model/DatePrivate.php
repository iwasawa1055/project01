<?php

App::uses('ApiCachedModel', 'Model');

class DatePrivate extends ApiCachedModel
{
    public function __construct()
    {
        parent::__construct('DATE_PRIVATE_CACHE', 'DatePrivate', '/date_private');
    }
}
