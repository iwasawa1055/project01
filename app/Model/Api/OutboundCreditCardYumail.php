<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');
App::uses('InfoBox', 'Model');
App::uses('InfoItem', 'Model');

class OutboundCreditCardYumail extends ApiModel
{
    public function __construct()
    {
        parent::__construct('OutboundCreditCardYumail', '/outbound_credit_card_yumail', 'gmopayment_v5');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
        (new InfoBox())->deleteCache();
        (new InfoItem())->deleteCache();
    }
}
