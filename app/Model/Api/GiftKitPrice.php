<?php

App::uses('ApiModel', 'Model');

class GiftKitPrice extends ApiModel
{
    public function __construct()
    {
        parent::__construct('GiftKitPrice', '/gift_kit_price', 'minikura_v5');
    }

    public function apiGet($data = [])
    {
        $data['oem_key'] = $this->oem_key;
        $d = $this->request($this->end_point, $data, 'GET');
        return $d;
    }

    public $validate = [
    ];
}
