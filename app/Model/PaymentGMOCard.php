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
        $d = $this->apiGetResults();
        foreach ($d as $card) {
            if ($card['default_flag'] === '1') {
                $card['expire_month'] = substr($card['expire'], 0, 2);
                $card['expire_year'] = substr($card['expire'], 2, 2);
                return $card;
            }
        }
    }
}
