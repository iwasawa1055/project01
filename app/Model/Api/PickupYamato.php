<?php

App::uses('ApiModel', 'Model');

class PickupYamato extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PickupYamato', '/pickup_yamato', 'minikura_v5');
    }

    public $validate = [
        'announcement_id' => [
            'required' => true,
        ],
    ];
}
