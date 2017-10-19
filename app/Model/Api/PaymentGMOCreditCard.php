<?php

App::uses('ApiModel', 'Model');

class PaymentGMOCreditCard extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PaymentGMOCreditCard', '/credit_card', 'gmopayment_v4');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new PaymentGMOCard())->deleteCache();
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
}
