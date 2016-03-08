<?php

App::uses('CustomerEmail', 'Model');

class EntryCustomerEmail extends CustomerEmail
{
    public function __construct()
    {
        parent::__construct('EntryCustomerEmail', '/entry_email', 'minikura_v5');
    }
}
