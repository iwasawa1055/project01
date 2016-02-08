<?php

App::uses('ApiModel', 'Model');

class Outbound extends ApiModel
{
    public function __construct()
    {
        parent::__construct('Outbound', '/outbound');
    }

    public function buildParamProduct($boxList = [], $itemList = []) {
        $product = '';
        foreach ($boxList as $box) {
            $product .= "${box['product_cd']}:${box['box_id']},";
        }
        return rtrim($product, ',');
    }

    public $validate = [
        'delivery_carrier' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '預け入れ方法は必須です',
            ],
        ],
        'address_cd' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '集荷の住所は必須です',
            ],
        ],
        'datetime_cd' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '集荷の日程は必須です',
            ],
        ],
    ];
}
