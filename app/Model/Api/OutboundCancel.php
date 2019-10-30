<?php

App::uses('ApiModel', 'Model');

class OutboundCancel extends ApiModel
{
    public function __construct()
    {

        // TODO 一旦paymentを使用する 後に変更する予定
        parent::__construct('OutboundCancel', '/payment_outbound_cancel', 'minikura_v5');
    }
}
