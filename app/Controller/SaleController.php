<?php

App::uses('MinikuraController', 'Controller');

/**
* アイテム販売メニュー　各ページ  
*
* 
*/
class SaleController extends MinikuraController
{
    const MODEL_NAME_SALE = 'Sale';
    const MODEL_NAME_CUSTOMER_ACCOUNT = 'CustomerAccount';
    const MODEL_NAME_SALE_ORDER = 'SaleOrder';
    const MODEL_NAME_INFO_ITEM = 'InfoItem';

    public function beforeFilter () {
        parent::beforeFilter(); 
        $this->loadModel(self::MODEL_NAME_SALE);
        $this->loadModel(self::MODEL_NAME_CUSTOMER_ACCOUNT);
        $this->loadModel(self::MODEL_NAME_SALE_ORDER);
        $this->loadModel(self::MODEL_NAME_INFO_ITEM);
    }

    /**
     * index
     */
    public function index()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->data, true));
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->query, true));
        //* todo 設定状況
        //* 暫定 session APIできるまで
        $sale_session = CakeSession::read(self::MODEL_NAME_SALE);
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($sale_session, true));
        //* todo 口座情報
        //* todo 振り込み依頼履歴
        //* todo 販売履歴
        //* 暫定 API　でき次第
        $stub = [];
        //* 暫定 type 1=販売中 2=購入手続き中 3=販売中 4=hoge
        //* 暫定 販売履歴type
        $status_type = $this->request->query('status_type');
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
        $this->set('history', $list);

    }


    /**
     * 暫定 edit 販売設定完了
     */
    public function edit()
    {
        //$model = new Sale();  
        //$model->set($this->request->data);

        //$this->Sale->set($this->request->data);
        $this->Sale->set($this->request->data[self::MODEL_NAME_SALE]);
        if ($this->request->is('post')) {
            //* To APIでき次第
            //* on customer table update

            //* off customer table update, 出品情報all cancel
            

            //* APIできるまで、ひとまずsession
            //CakeSession::write(self::MODEL_NAME_SALE, $model->toArray());
            CakeSession::write(self::MODEL_NAME_SALE, $this->Sale->toArray());
        }

        //CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export(CakeSession::read(), true));
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);

    }

    /**
     * 暫定 order 振り込み依頼 
     */
    public function order()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);

    }

    /**
     * 暫定 order_complete 振り込みpost 
     */
    public function order_complete()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);

        if ($this->request->is('post')) {
            //* To APIでき次第
            CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->data, true));
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
