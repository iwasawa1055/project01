<?php

App::uses('ApiModel', 'Model');

class Sales extends ApiModel
{
    /**
    * API sales 販売情報
    */
    public function __construct()
    {
        parent::__construct('Sales', '/sales', 'minikura_v5');
    }

    public function apiGetSale($data)
    {
        if (array_key_exists($this->model_name, $data)) {
            $data = $data[$this->model_name];
        }

        // $data['oem_key'] = $this->oem_key;
        $d = $this->request($this->end_point, $data, 'GET');
        if (!$d->status) {
        }

        return $d->results;
    }

    public $validate = [
        'sales_id' => [
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'message' => ['format', 'sales_id']
            ],
        ],
        'sales_title' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'sales_title']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 50],
                'message' => ['maxLength', 'sales_title', 50]
            ],
        ],
        'price' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'price']
            ],
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'message' => ['format', 'price']
            ],
            'range' => [
                'rule' => ['range', 999, 50001],
                'message' => ['range', 'price', '1000円', '50000円']
            ],
        ],
        'sales_note' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'sales_note']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 1000],
                'message' => ['maxLength', 'sales_note', 1000]
            ],
        ],
        'item_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'item_id']
            ],
        ],
    ];

    /* checkSales
    * @params array $_item アイテムデータ 
    *
    * @return array sales情報 
    */
    public function checkSales($_item)
    {
        if (Hash::get($_item, 'sales')) {
            $sales = Hash::get($_item, 'sales');
            foreach ($sales as $sales_key => $sales_val) {
                //* 購入キャンセル, 販売キャンセルは複数できる continue、
                if ( in_array($sales_val['sales_status'], [SALES_STATUS_PURCHASE_CANCEL, SALES_STATUS_SALES_CANCEL])) {
                    continue;
                } else {
                    return $sales_val;
                }
            }
        }
        return null;
    }

    /* sumPrice
    * @param array $_data sales情報
    *
    * @return string $total_price  
    */
    public function sumPrice($_data)
    {
        $total_price = 0;
        if (empty($_data)) {
            return $total_price;
        }
        foreach ($_data as $sales) {
            $total_price += $sales['price'];
        }
        return $total_price;
    }
}

