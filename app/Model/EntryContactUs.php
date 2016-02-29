<?php

App::uses('ContactUs', 'Model');

class EntryContactUs extends ContactUs
{
    public function __construct()
    {
        parent::__construct('EntryContactUs', '/entry_contact', 'minikura_v5');
    }
}
