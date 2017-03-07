<?php

App::uses('ApiModel', 'Model');

// Cleaning Model
class Cleaning extends ApiModel
{
    public function __construct()
    {
        parent::__construct('Cleaning', '/cleaning', 'minikura_v5');
    }

    // Validate項目設定
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

    public function getWorkType($itemgroup_cd)
    {
        // Worktypeを取得する
        // WorktypeはConfig/EnvConfig/[Development]/AppConfig.phpを参照
        $worktypes = Configure::read('app.kit.cleaning.work_type');
        
        // Worktypesが設定されていればリターン
        if ( isset($worktypes[$itemgroup_cd]) ) {
            return $worktypes[$itemgroup_cd];
        } else {
            return false;
        }
    }
}
