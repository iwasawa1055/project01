<?php

App::uses('ApiCachedModel', 'Model');
App::uses('Announcement', 'Model');

class PaymentGMOCreditCard extends ApiCachedModel
{
    const SESSION_CACHE_KEY = 'PaymentGMOCreditCard_CACHE';

    public function __construct()
    {
        parent::__construct(self::SESSION_CACHE_KEY, 0, 'PaymentGMOCreditCard', '/credit_card', 'gmopayment_v4');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new PaymentGMOCreditCard())->deleteCache();
        (new Announcement())->deleteCache();
    }

    public $validate = [
        'gmo_token' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'required' => true,
                'message'  => ['notBlank', 'gmo_token'],
            ],
        ],
    ];

    public function apiGetDefaultCard()
    {
        $d = $this->apiGetResults();
        if (empty($d)) {
            return null;
        }
        foreach ($d as $card) {
            if ($card['default_flag'] === '1') {
                $card['expire_month'] = substr($card['expire'], 0, 2);
                $card['expire_year'] = substr($card['expire'], 2, 2);
                return $card;
            }
        }
        # 19026 default_flgが0の場合があるので、その場合は0番目のカードを返却する
        if (isset($d[0])) {
            $d[0]['expire_month'] = substr($d[0]['expire'], 0, 2);
            $d[0]['expire_year'] = substr($d[0]['expire'], 2, 2);
            return $d[0];
        }
        return null;
    }
}
