<?php

App::uses('ApiCachedModel', 'Model');

class PaymentGMOCard extends ApiCachedModel
{
    const SESSION_CACHE_KEY = 'PaymentGMOCard_CACHE';

    public function __construct()
    {
        parent::__construct(self::SESSION_CACHE_KEY, 0, 'PaymentGMOCard', '/card', 'gmopayment_v4');
    }

    public $validate = [
    ];

    public function apiGetDefaultCard()
    {
        $d = $this->apiGetResults();
        if (empty($d)) {
            return [];
        }
        foreach ($d as $card) {
            if ($card['default_flag'] === '1') {
                $card['expire_month'] = substr($card['expire'], 0, 2);
                $card['expire_year'] = substr($card['expire'], 2, 2);
                return $card;
            }
        }
    }
}
