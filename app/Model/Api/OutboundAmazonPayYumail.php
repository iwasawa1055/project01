<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');
App::uses('InfoBox', 'Model');
App::uses('InfoItem', 'Model');

class OutboundAmazonPayYumail extends ApiModel
{
    public function __construct()
    {
        parent::__construct('OutboundAmazonPayYumail', '/outbound_amazon_pay_yumail', 'amazon_pay_v5');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
        (new InfoBox())->deleteCache();
        (new InfoItem())->deleteCache();
    }
}
