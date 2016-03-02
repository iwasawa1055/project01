<?php

App::uses('Inquiry', 'Model');

class EntryContactUs extends Inquiry
{
    public function __construct()
    {
        parent::__construct('EntryContactUs', '/entry_contact', 'minikura_v5');
    }
}
