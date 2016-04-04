<?php

App::uses('ApiModel', 'Model');

class Billing extends ApiModel
{
    public function __construct()
    {
        parent::__construct('Billing', '/billing');
    }

    public $validate = [
        'announcement_id' => [
            'required' => true,
        ],
        'category_id' => [
            'required' => true,
        ],
    ];
}
