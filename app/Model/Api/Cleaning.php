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

    public function getWorkType($_itemGroupCD)
    {
        $_worktypes = Configure::read('app.kit.cleaning.work_type');
        if ( isset($_worktypes[$_itemGroupCD]) ) {
            return $_worktypes[$_itemGroupCD];
        } else {
            return false;
        }
    }
}
