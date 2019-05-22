<?php

App::uses('ApiModel', 'Model');

class GiftKitPrice extends ApiModel
{
    public function __construct()
    {
        parent::__construct('GiftKitPrice', '/gift_kit_price', 'minikura_v5');
    }

    public $validate = [
    ];
}
