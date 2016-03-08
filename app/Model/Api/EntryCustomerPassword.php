<?php

App::uses('CustomerPassword', 'Model');

class EntryCustomerPassword extends CustomerPassword
{
    public function __construct()
    {
        parent::__construct('EntryCustomerPassword', '/entry_password', 'minikura_v5');
    }
}
