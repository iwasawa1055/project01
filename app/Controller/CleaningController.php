<?php

App::uses('MinikuraController', 'Controller');

/**
* クリーニングメニュー　各ページ  
*
* 
*/
class CleaningController extends MinikuraController
{
    const MODEL_NAME = 'InfoItem';
    const MODEL_NAME_ITEM_EDIT = 'Item';
    const MODEL_NAME_SALES = 'Sales';

    protected $paginate = array(
        'limit' => 20,
        'paramType' => 'querystring'
    );

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
        $this->loadModel('InfoBox');
        $this->loadModel(self::MODEL_NAME_ITEM_EDIT);
        $this->loadModel(self::MODEL_NAME_SALES);

        $this->set('sortSelectList', $this->makeSelectSortUrl());
        $this->set('select_sort_value', Router::reverse($this->request));
    }

    private function makeSelectSortUrl()
    {
        // 並び替え選択
        $selectSortKeys = [
            'box_id' => __('box_id'),
            'box_name' => __('box_name'),
            'item_id' => __('item_id'),
            'item_name' => __('item_name'),
            'item_status' => __('item_status'),
            // 'item_group_cd' => __('item_group_cd'),
        ];

        // 出庫済み　hide_outbound=0：表示、hide_outbound=1：非表示、初期表示：非表示
        $withOutboundDone = !empty(Hash::get($this->request->query, 'hide_outbound', 1));
        $page = $this->request->query('page');
        $data = [];
        foreach ($selectSortKeys as $key => $value) {
            $desc = Router::url(['action'=>'index', '?' => ['order' => $key, 'direction' => 'desc', 'hide_outbound' => $withOutboundDone, 'page' => $page]]);
            $data[$desc] = $value . __('select_sort_desc');
            $asc = Router::url(['action'=>'index', '?' => ['order' => $key, 'direction' => 'asc', 'hide_outbound' => $withOutboundDone, 'page' => $page]]);
            $data[$asc] = $value . __('select_sort_asc');
        }

        return $data;
    }

    private function getProductName($_product)
    {
        $productName = '';
        if ($_product === 'mono') {
            $productName = 'minikuraMONO';
        } else if ($_product === 'hako') {
            $productName = 'minikuraHAKO';
        } else if ($_product === 'cargo01') {
            $productName = 'minikura CARGO じぶんでコース';
        } else if ($_product === 'cargo02') {
            $productName = 'minikura CARGO ひとまかせコース';
        } else if ($_product === 'cleaning') {
            $productName = 'クリーニングパック';
        } else if ($_product === 'shoes') {
            $productName = 'シューズパック';
        } else if ($_product === 'sneakers') {
            $productName = 'minikura SNEAKERS';
        }
        return $productName;
    }

    protected function setQueryParameter()
    {
        $query = $this->request->query;
        $results = [];
        // keyword
        if (empty($query['keyword'])) {
            $results['keyword'] = null;
        } else {
            $results['keyword'] = $query['keyword'];
        }

        // order
        if (empty($query['order'])) {
            $results['order'] = null;
        } else {
            $results['order'] = $query['order'];
        }

        // direction
        if (empty($query['direction'])) {
            $results['direction'] = null;
        } else {
            $results['direction'] = $query['direction'];
        }

        // フォームhidden値設定
        if (isset($query['hide_outbound'])) {
            if ($query['hide_outbound'] === '0' ) {
                $results['hide_outbound'] = '0';
            } else {
                $results['hide_outbound'] = '1';
            }
        } else {
            $results['hide_outbound'] = '1';
        }

        return $results;
    }

    private function getRequestSortKey()
    {
        $order = $this->request->query('order');
        $direction = $this->request->query('direction');
        if (!empty($order)) {
            return [$order => ($direction === 'asc')];
        }
        return [];
    }

    /**
     * input
     */
    public function input()
    {
        // 商品指定
        $where = [];
        $where['product'] = null;
        
        // 並び替えキー指定
        $sortKey = $this->getRequestSortKey();

        // Sessionにデータがある場合
        $results = $this->InfoItem->getListForServiced($sortKey, $where, false, true);
        $results = $this->InfoItem->editBySearchTerm($results, $this->request->query);
        
        $item_all_count = count($results);

        // paginate
        $list = $this->paginate(self::MODEL_NAME, $results);
        
        $this->set('itemList', $list);
        $this->set('item_all_count', $item_all_count);

        $query = $this->request->query;
        $query['page'] = 1;
        $url = Router::url(['action'=>'input', '?' => http_build_query($query)]);

        $query_params = $this->setQueryParameter();

        $this->set('keyword', $query_params['keyword']);
        $this->set('order', $query_params['order']);
        $this->set('direction', $query_params['direction']);
        
        $this->set('price',Configure::read('app.kit.cleaning.item_group_cd'));
    }

    /**
     * input
     */
    public function input_confirm()
    {
        //print_r($this->request);
        
        
        
        return $this->redirect(['controller' => 'cleaning', 'action' => 'confirm']);
    }

    /**
     *  confirm
     */
    public function confirm()
    {
/*
        if ($this->request->is('post')) {
            $this->CustomerSales->set($this->request->data[self::MODEL_NAME_CUSTOMER_SALES]);
            if ($this->CustomerSales->validates()) {
                //* To API
                $result = $this->CustomerSales->apiPatch($this->CustomerSales->toArray());
                //* error
                if (!empty($result->error_message)) {
                    $this->Flash->set($result->error_message);
                    return $this->redirect(['action' => 'index']);
                }
                //* 販売設定　状態set
                $this->set('is_customer_sales', $this->Customer->isCustomerSales());

                //* 口座情報 set
                $customer_bank_account = null;
                if (!empty($this->Customer->getCustomerBankAccount())){
                    $customer_bank_account = $this->Customer->getCustomerBankAccount();
                }
                $this->set('customer_bank_account', $customer_bank_account);
            } else {
                //* test exception info
                //new AppInternalInfo('Error : sales_flag != 0 or 1', $code = 500);
                //* message
                $this->Flash->set(__($this->CustomerSales->validationErrors['sales_flag'][0]));
                //* redirect
                return $this->redirect(['action' => 'index']);
            }
        }
*/
    }

    /**
     *  complete 
     */
    public function complete()
    {
/*
        if ($this->request->is('get')) {
            //* 口座情報
            $customer_bank_account = null;
            if (!empty($this->Customer->getCustomerBankAccount())){
                $customer_bank_account = $this->Customer->getCustomerBankAccount();
            }
            $this->set('customer_bank_account', $customer_bank_account);
            //CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($customer_bank_account, true));

            //*  振り込み可能リスト 金額
            $sales = null;
            $transfer_price = 0;
            $sales_result = $this->Sales->apiGet(['sales_status' => SALES_STATUS_TRANSFER_ALLOWED]);
            if (!empty($sales_result->results)) {
                $transfer_price_all = $this->Sales->sumPrice($sales_result->results);
                $transfer_price = $this->Sales->subtractCharge($transfer_price_all);
                $sales =  $sales_result->results;
            }
            $this->set('sales', $sales);
            $this->set('transfer_price', $transfer_price);
            $this->set('transfer_price_all', $transfer_price_all);
            //CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($sales, true));

            //* 振り込み済み履歴 
            $transfer_completed = null;
            //$transfer_completed_result = $this->Transfer->apiGet(['limit' => '3']);
            $transfer_completed_result = $this->Transfer->apiGet();
            if (!empty($transfer_completed_result->results)) {
                $transfer_completed = $transfer_completed_result->results;
            }
            $this->set('transfer_completed', $transfer_completed);

            // 手数料関連
            $transfer_charge_price = TRANSFER_CHARGE_PRICE;
            $this->set('transfer_charge_price', $transfer_charge_price);
        }

*/
    }
}
