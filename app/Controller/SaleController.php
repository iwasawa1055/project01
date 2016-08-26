<?php

App::uses('MinikuraController', 'Controller');

/**
* アイテム販売メニュー　各ページ  
*
* 
*/
class SaleController extends MinikuraController
{
    const MODEL_NAME_SALES = 'Sales';
    const MODEL_NAME_SALES_STATUS = 'SalesStatus';
    const MODEL_NAME_CUSTOMER_ACCOUNT = 'CustomerAccount';
    const MODEL_NAME_CUSTOMER_SALES = 'CustomerSales';
    const MODEL_NAME_TRANSFER = 'Transfer';
    const MODEL_NAME_INFO_ITEM = 'InfoItem';

    //* for test 
    protected $paginate = array(
        'limit' => 5,
        'paramType' => 'querystring'
    );

    public function beforeFilter () {
        parent::beforeFilter(); 
        $this->loadModel(self::MODEL_NAME_SALES);
        $this->loadModel(self::MODEL_NAME_SALES_STATUS);
        $this->loadModel(self::MODEL_NAME_CUSTOMER_ACCOUNT);
        $this->loadModel(self::MODEL_NAME_CUSTOMER_SALES);
        $this->loadModel(self::MODEL_NAME_TRANSFER);
        $this->loadModel(self::MODEL_NAME_INFO_ITEM);
    }

    /**
     * index
     */
    public function index()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);

        //*  販売機能　設定状況 
        $customer_sales = null;
        $customer_sales_result = $this->CustomerSales->apiGet();
        if (!empty($customer_sales_result->results[0])) {
            $customer_sales = $customer_sales_result->results[0];
        }
        $this->set('customer_sales', $customer_sales);

        //* 口座情報
        $customer_bank_account = null;
        if (!empty($this->Customer->getCustomerBankAccount())){
            $customer_bank_account = $this->Customer->getCustomerBankAccount();
        }
        $this->set('customer_bank_account', $customer_bank_account);
        //CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($customer_bank_account, true));

        //*  振り込み可能 金額
        $transfer_price = 0;
        $sales_transfer_allowed_result = $this->Sales->apiGet(['sales_status' => SALES_STATUS_TRANSFER_ALLOWED]);
        if (!empty($sales_transfer_allowed_result->results)) {
            $transfer_price = $this->Sales->sumPrice($sales_transfer_allowed_result->results);
        }
        $this->set('transfer_price', $transfer_price);

        //* todo 振り込み済み履歴 暫定 =>別途振り込み済みの取得APIが出来る予定
        $sales_completed = null;
        $sales_completed_result = $this->Sales->apiGet(['sales_status' => SALES_STATUS_REMITTANCE_COMPLETED]);
        if (!empty($sales_completed_result->results)) {
            $sales_completed = $sales_completed_result->results;
        }
        $this->set('sales_completed', $sales_completed);
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($sales_completed, true));

        //* set sales_status master for select box  
        $master_sales_status_array = [];
        $master_sales_status_array =  CakeSession::read([self::MODEL_NAME_SALES_STATUS]);
        if (empty($master_sales_status_array)) {
            $sales_status_result = $this->SalesStatus->apiGet();
            if (!empty($sales_status_result->results)) {
                foreach($sales_status_result->results as $master_sales_status){
                    $master_sales_status_array[$master_sales_status['sales_status']] = $master_sales_status['sales_status_name'];
                } 
                CakeSession::write(self::MODEL_NAME_SALES_STATUS, $master_sales_status_array);
            }
        }
        $this->set('master_sales_status_array', $master_sales_status_array);

        //* UI select box value
        $sales_status = $this->request->query('sales_status') ?  $this->request->query('sales_status') : SALES_STATUS_ON_SALE;
        $this->set('sales_status', $sales_status);

        //* 販売履歴 by select box
        $sales = null;
        $sales_result = $this->Sales->apiGet(['sales_status' => $sales_status]);
        if (!empty($sales_result->results)) {
            $sales =  $sales_result->results;
        }
        $list = $this->paginate($sales);
        $this->set('sales', $list);

    }


    /**
     *  edit 販売設定 on/off 実行
     */
    public function edit()
    {

        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->data, true));

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
    }

    /**
     *  transfer 振り込み依頼 
     */
    public function transfer()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);

        if ($this->request->is('get')) {
            //* 口座情報
            $customer_bank_account = null;
            if (!empty($this->Customer->getCustomerBankAccount())){
                $customer_bank_account = $this->Customer->getCustomerBankAccount();
            }
            $this->set('customer_bank_account', $customer_bank_account);
            CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($customer_bank_account, true));

            //*  振り込み可能リスト 金額
            $sales = null;
            $transfer_price = 0;
            $sales_result = $this->Sales->apiGet(['sales_status' => SALES_STATUS_TRANSFER_ALLOWED]);
            if (!empty($sales_result->results)) {
                $transfer_price = $this->Sales->sumPrice($sales_result->results);
                $sales =  $sales_result->results;
            }
            $this->set('sales', $sales);
            $this->set('transfer_price', $transfer_price);
            CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($sales, true));
            CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($transfer_price, true));
        }
    }

    /**
     * 暫定 transfer_complete 振り込みpost 
     */
    public function transfer_complete()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->data, true));

        if ($this->request->is('get')) {
            //* todo error
        }

        if ($this->request->is('post')) {
            //*  API parameterはtokenのみ　validate不要
            $data = [];
            $transfer_result = $this->Transfer->apiPost($data); 
            CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($transfer_result, true));

            //* success
            if (!empty($transfer_result->results[0])) {
                $this->set('transfer_result', $transfer_result->results[0]);
            }
            //* error
            if (!empty($transfer_result->error_message)) {
                $this->set('error_message', $transfer_result->error_message);
            }
        }

    }

    /**
     * 暫定 order_list 振り込み一覧 
     */
    public function order_list()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);
        //* 暫定 API　でき次第
        $stub = [];
        //* 暫定 type 1=販売中 2=購入手続き中 3=販売中 4=hoge
        $stub[] = ['id' =>1, 'type' =>1, 'hoge' => 'fuga'];
        $stub[] = ['id' =>2, 'type' =>1, 'hoge' => 'fuga2'];
        $stub[] = ['id' =>3, 'type' =>1, 'hoge' => 'fuga3'];
        $stub[] = ['id' =>4, 'type' =>1, 'hoge' => 'fuga4'];
        $stub[] = ['id' =>5, 'type' =>1, 'hoge' => 'fuga5'];
        $stub[] = ['id' =>6, 'type' =>1, 'hoge' => 'fuga6'];
        $stub[] = ['id' =>7, 'type' =>1, 'hoge' => 'fuga7'];
        $stub[] = ['id' =>8, 'type' =>1, 'hoge' => 'fuga8'];
        $stub[] = ['id' =>9, 'type' =>1, 'hoge' => 'fuga9'];
        $stub[] = ['id' =>10,'type' =>1,  'hoge' => 'fuga10'];
        $stub[] = ['id' =>11,'type' =>1,  'hoge' => 'fuga10'];
        $stub[] = ['id' =>12,'type' =>2,  'hoge' => 'fuga11'];
        $stub[] = ['id' =>13,'type' =>2,  'hoge' => 'fuga11'];
        $stub[] = ['id' =>14,'type' =>2,  'hoge' => 'fuga11'];
        $stub[] = ['id' =>15,'type' =>2,  'hoge' => 'fuga11'];
        $stub[] = ['id' =>16,'type' =>3,  'hoge' => 'fuga11'];
        $stub[] = ['id' =>17,'type' =>3,  'hoge' => 'fuga11'];
        $stub[] = ['id' =>18,'type' =>4,  'hoge' => 'fuga11'];
        $stub[] = ['id' =>19,'type' =>4,  'hoge' => 'fuga11'];
        $stub[] = ['id' =>20,'type' =>4,  'hoge' => 'fuga11'];
        $all = $stub;
        $list = $this->paginate($all);
        $this->set('order_list', $list);

    }

    /**
     * 暫定 order_detail 振り込み詳細 
     */
    public function order_detail()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);
        //* 暫定 API　でき次第
        $stub = [];
        //* 暫定 type 1=販売中 2=購入手続き中 3=販売中 4=hoge
        $stub[] = ['id' =>1, 'type' =>1, 'hoge' => 'fuga'];
        $stub[] = ['id' =>2, 'type' =>1, 'hoge' => 'fuga2'];
        $stub[] = ['id' =>3, 'type' =>1, 'hoge' => 'fuga3'];
        $stub[] = ['id' =>4, 'type' =>1, 'hoge' => 'fuga4'];
        $stub[] = ['id' =>5, 'type' =>1, 'hoge' => 'fuga5'];
        $stub[] = ['id' =>6, 'type' =>1, 'hoge' => 'fuga6'];
        $stub[] = ['id' =>7, 'type' =>1, 'hoge' => 'fuga7'];
        $stub[] = ['id' =>8, 'type' =>1, 'hoge' => 'fuga8'];
        $stub[] = ['id' =>9, 'type' =>1, 'hoge' => 'fuga9'];
        $stub[] = ['id' =>10,'type' =>1,  'hoge' => 'fuga10'];
        $stub[] = ['id' =>11,'type' =>1,  'hoge' => 'fuga10'];
        $stub[] = ['id' =>12,'type' =>2,  'hoge' => 'fuga11'];
        $stub[] = ['id' =>13,'type' =>2,  'hoge' => 'fuga11'];
        $stub[] = ['id' =>14,'type' =>2,  'hoge' => 'fuga11'];
        $stub[] = ['id' =>15,'type' =>2,  'hoge' => 'fuga11'];
        $stub[] = ['id' =>16,'type' =>3,  'hoge' => 'fuga11'];
        $stub[] = ['id' =>17,'type' =>3,  'hoge' => 'fuga11'];
        $stub[] = ['id' =>18,'type' =>4,  'hoge' => 'fuga11'];
        $stub[] = ['id' =>19,'type' =>4,  'hoge' => 'fuga11'];
        $stub[] = ['id' =>20,'type' =>4,  'hoge' => 'fuga11'];
        $all = $stub;
        $list = $this->paginate($all);
        $this->set('order_detail', $list);

    }
}
