<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');
App::uses('InfoBox', 'Model');
App::uses('InfoItem', 'Model');

class Outbound extends ApiModel
{
    public function __construct()
    {
        parent::__construct('Outbound', '/outbound');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
        (new InfoBox())->deleteCache();
        (new InfoItem())->deleteCache();
    }

    public function buildParamProduct($boxList = [], $itemList = []) {
        $list = [];
        foreach ($boxList as $box) {
            $list[] = "${box['product_cd']}:${box['box_id']}";
        }
        foreach ($itemList as $item) {
            $box = $item['box'];
            $list[] = "${box['product_cd']}:${item['box_id']}:${item['item_id']}";
        }
        return implode(',', $list);
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
