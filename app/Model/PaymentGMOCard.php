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

    public function apiGetDefaultCard()
    {
        $d = $this->apiGet();
        foreach ($d->results['contents'] as $card) {
            if ($card['default_flag'] === '1') {
                return $card;
            }
        }
    }
}
