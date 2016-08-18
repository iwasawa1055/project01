<?php

App::uses('MinikuraController', 'Controller');

/**
* アイテム販売メニュー　各ページ  
*
* 
*/
class AccountController extends MinikuraController
{
    const MODEL_NAME_ACCOUNT = 'CustomerAccount';

    public function beforeFilter () {
        parent::beforeFilter(); 
        $this->loadModel(self::MODEL_NAME_ACCOUNT);
    }

    /**
     * index
     */
    public function customer_index()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->data, true));

    }

    /**
     * 暫定 add 追加
     */
    public function customer_add()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }

    /**
     * 暫定 confirm
     */
    public function customer_confirm()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->data, true));

        //* post
        if ($this->request->is('post')) {
            $data = $this->request->data[self::MODEL_NAME_ACCOUNT];
            $this->CustomerAccount->set($data);
            if ( $this->CustomerAccount->validates()) {
                
                CakeSession::write(self::MODEL_NAME_ACCOUNT, $data);

            } else {
                $this->set('validErrors', $this->CustomerAccount->validationErrors);
                CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->CustomerAccount->validationErrors, true));
                //todo render
            }
        }
    }

    /**
     * 暫定 edit 編集
     */
    public function customer_edit()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->data, true));
        //* api get

        //* api post

    }

    /**
     * 暫定 complete 完了
     */
    public function customer_complete()
    {


        CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'.var_export(CakeSession::read(), true));

        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }


}
