<?php

App::uses('ApiModel', 'Model');

class UnusedGiftInfo extends ApiModel
{
    public function __construct()
    {
        parent::__construct('UnusedGiftInfo', '/unused_gift_info', 'minikura_v5');
    }

    public function apiGet($data = [])
    {
        $data['oem_key'] = $this->oem_key;
        $d = $this->request($this->end_point, $data, 'GET');
        return $d;
    }

    public $validate = [
        'gift_cd' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'gift_cd'],
            ],
        ],
    ];
}
