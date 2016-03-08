<?php

App::uses('ContactUs', 'Model');

class EntryContactUs extends ContactUs
{
    public function __construct()
    {
        parent::__construct('ContactUsCorporate', '/contact_corporate');
    }
}
