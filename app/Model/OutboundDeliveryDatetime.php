<?php

App::uses('AppHttp', 'Lib');
App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class OutboundDeliveryDatetime extends ApiModel
{
    public function __construct()
    {
        parent::__construct('OutboundDeliveryDatetime', '/outbound_delivery_datetime', 'minikura_v4');
    }

    public function apiGet($data = [])
    {
        $data['oem_key'] = $this->oem_key;
        $data['postal'] = '110-0001';
        return parent::apiGet($data);
    }

    public $validate = [
    ];
}
