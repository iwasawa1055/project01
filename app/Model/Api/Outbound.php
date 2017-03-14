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
            if (in_array(Hash::get($box, 'product_cd'), [PRODUCT_CD_MONO, PRODUCT_CD_CLEANING_PACK, PRODUCT_CD_SHOES_PACK, PRODUCT_CD_SNEAKERS], true)) {
                $this->buildParamProductMono($list, $box);
            } else {
                $list[] = "${box['product_cd']}:${box['box_id']}";
            }
        }
        foreach ($itemList as $item) {
            $box = $item['box'];
            $list[] = "${box['product_cd']}:${item['box_id']}:${item['item_id']}";
        }
        return implode(',', $list);
    }

    /**
     * MONOなどをボックスごと出庫する場合はアイテムを展開する
     * @param  [type] $list [description]
     * @param  [type] $box  [description]
     * @return [type]       [description]
     */
    private function buildParamProductMono(&$list, $box) {
        $model = new InfoItem();
        $monoList = $model->apiGetResultsWhere([], [
            'box_id' => $box['box_id'],
            'item_status' => [BOXITEM_STATUS_INBOUND_DONE * 1]
        ]);
        foreach ($monoList as $item) {
            $box = $item['box'];
            $list[] = "${box['product_cd']}:${item['box_id']}:${item['item_id']}";
        }
    }

    public $validate = [
        'address_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'outbound_address'],
            ],
        ],


        'product' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['select', 'product'],
            ],
            'checkProductLimit' => [
                'rule' => 'checkProductLimit',
            ],
        ],
        'lastname' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'lastname']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'lastname', 29]
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'lastname']
            ],
        ],
        'lastname_kana' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'lastname_kana']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'lastname_kana', 29]
            ],
            'isFwKana' => [
                'rule' => 'isFwKana',
                'message' => ['isFwKana', 'lastname_kana']
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'lastname_kana']
            ],
        ],
        'firstname' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'firstname']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'firstname', 29]
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'firstname']
            ],
        ],
        'firstname_kana' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'firstname_kana']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'firstname_kana', 29]
            ],
            'isFwKana' => [
                'rule' => 'isFwKana',
                'message' => ['isFwKana', 'firstname_kana']
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'firstname_kana']
            ],
        ],
        'tel1' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'tel1']
            ],
            'isPhoneNumberJp' => [
                'rule' => 'isPhoneNumberJp',
                'message' => ['format', 'tel1']
            ],
        ],
        'postal' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'postal']
            ],
            'isPostalCodeJp' => [
                'rule' => 'isPostalCodeJp',
                'message' => ['format', 'postal']
            ],
        ],
        'pref' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'pref']
            ],
            'isPrefNameJp' => [
                'rule' => 'isPrefNameJp',
                'message' => ['format', 'pref']
            ],
        ],
        'address1' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'address1']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 8],
                'message' => ['maxLength', 'address1', 8]
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'address1']
            ],
        ],
        'address2' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'address2']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 18],
                'message' => ['maxLength', 'address2', 18]
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'address2']
            ],
        ],
        'address3' => [
            'maxLength' => [
                'rule' => ['maxLength', 30],
                'allowEmpty' => true,
                'message' => ['maxLength', 'address3', 30]
            ],
        ],
        'datetime_cd' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'outbound_datetime'],
            ],
            'isDatetimeDelivery' => [
                'rule' => 'isDatetimeDelivery',
                'required' => true,
                'message' => ['notBlank', 'outbound_datetime'],
            ],
        ],
        'aircontent_select' => [
            'checkAirContentSelect' => [
                'rule' => 'checkAirContentSelect',
            ],
        ],
        'aircontent' => [
            'checkAirContent' => [
                'rule' => 'checkAirContent',
            ],
        ],
    ];

    public function checkAirContentSelect()
    {
        if (empty($this->data[$this->model_name]['pref'])) {
            return true;
        }
        if (in_array($this->data[$this->model_name]['pref'], ISOLATE_ISLANDS) &&
            !in_array($this->data[$this->model_name]['aircontent_select'], [OUTBOUND_HAZMAT_NOT_EXIST, OUTBOUND_HAZMAT_EXIST])) {
                return "お届け品の確認は必須です。";
        }
        return true;
    }

    public function checkAirContent()
    {
        if (empty($this->data[$this->model_name]['pref'])) {
            return true;
        }
        if (in_array($this->data[$this->model_name]['pref'], ISOLATE_ISLANDS) &&
            $this->data[$this->model_name]['aircontent_select'] === '1' &&
            empty($this->data[$this->model_name]['aircontent'])) {
                return "品目の入力は必須です。";
        }
        return true;
    }

    public function checkProductLimit()
    {
        $product = $this->data[$this->model_name]['product'];
        $product_data = explode(',', $product);
        //* Count Check
        $limitNum = 100;
        $limitNumNext = $limitNum + 1;
        $productCount = count($product_data);
        if ($limitNum < $productCount) {
            return "一度にお申し込みいただけるアイテム数の上限は {$limitNum} 個です。{$limitNumNext} 個目からのお申し込みは分けてご依頼ください。（選択アイテム数：{$productCount} 個）";
        }
        return true;
    }
}
