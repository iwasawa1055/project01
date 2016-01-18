<?php

App::uses('ApiModel', 'Model');

class PaymentGMOCard extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PaymentGMOCard', '/card', 'gmopayment_v4');
    }

    public $validate = [
    ];
}
