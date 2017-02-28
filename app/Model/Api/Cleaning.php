<?php

App::uses('ApiModel', 'Model');

class Cleaning extends ApiModel
{
    public function __construct()
    {
        parent::__construct('Cleaning', '/cleaning', 'minikura_v5');
    }

    public $validate = [
        'work_type' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'work_type'],
            ],
            'checkWorkType' => [
                'rule' => 'checkWorkType',
            ],
        ],
        'product' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'product'],
            ],
        ],
    ];

    public function buildParamProduct($itemList = []) {
        $list = [];
        foreach ($itemList as $item) {
            $list[] = "${item['product_cd']}:${item['box_id']}:${item['item_id']}";
        }
        return implode(',', $list);
    }

    public function checkWorkType()
    {
        $_priceconf = Configure::read('app.kit.cleaning.item_group_cd');
        
        if ( !in_array($this->data[$this->model_name]['work_type'], array_keys($_priceconf))) {
            return "クリーニングできないものです";
        }
        
        return true;
    }

}
