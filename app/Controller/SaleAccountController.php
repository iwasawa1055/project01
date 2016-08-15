<?php

App::uses('MinikuraController', 'Controller');

/**
* アイテム販売メニュー　各ページ  
*
* 
*/
class SaleAccountController extends MinikuraController
{
    const MODEL_NAME_SALE = 'Sale';
    const MODEL_NAME_SALE_INFO = 'SaleAccount';
    const MODEL_NAME_INFO_ITEM = 'InfoItem';

    public function beforeFilter () {
        parent::beforeFilter(); 
        $this->loadModel(self::MODEL_NAME_SALE);
        $this->loadModel(self::MODEL_NAME_SALE_INFO);
        $this->loadModel(self::MODEL_NAME_INFO_ITEM);
    }

    /**
     * index
     */
    public function index()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->data, true));

    }

    /**
     * 暫定 add 追加
     */
    public function add()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }

    /**
     * 暫定 confirm
     */
    public function confirm()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->data, true));

        //* post
        if ($this->request->is('post')) {
            $data = $this->request->data[self::MODEL_NAME_SALE_INFO];
            $this->SaleAccount->set($data);
            if ( $this->SaleAccount->validates()) {
                
                CakeSession::write(self::MODEL_NAME_SALE_INFO, $data);

            } else {
                $this->set('validErrors', $this->SaleAccount->validationErrors);
                CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->SaleAccount->validationErrors, true));
                //todo render
            }
        }
    }

    /**
     * 暫定 edit 編集
     */
    public function edit()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->data, true));
        //* api get

        //* api post

    }

    /**
     * 暫定 complete 完了
     */
    public function complete()
    {


        CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'.var_export(CakeSession::read(), true));

        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }

    /**
     * 暫定 delete 
     */
    public function delete()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }


}
