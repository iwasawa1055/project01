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

        $customer_account = null;
        $customer_account_result = $this->CustomerAccount->apiGet();
        if (!empty($customer_account_result->results[0])) {
            $customer_account = $customer_account_result->results[0];
        }
        $this->set('customer_account', $customer_account);

    }

    /**
     * 暫定 add 追加
     */
    public function customer_add()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);

    }

    /**
     * 暫定 confirm
     */
    public function customer_confirm()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->data, true));
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->query, true));
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->params, true));

        //* get
        if (!$this->request->is('post')) {
            //error
        }
        //* for UI ,  PUT or POST
        $step = Hash::get($this->request->params, 'step');
        $this->set('step', $step);
        //* post
        if ($this->request->is('post')) {
            $data = $this->request->data[self::MODEL_NAME_ACCOUNT];
            $this->CustomerAccount->set($data);
            if ( $this->CustomerAccount->validates()) {
                
                CakeSession::write(self::MODEL_NAME_ACCOUNT, $data);

            } else {
                $this->set('validErrors', $this->CustomerAccount->validationErrors);
                CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->CustomerAccount->validationErrors, true));
                return $this->render('customer_add');
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
        $customer_account = null;
        $customer_account_result = $this->CustomerAccount->apiGet();
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($customer_account_result, true));
        if (!empty($customer_account_result->results[0])) {
            $customer_account = $customer_account_result->results[0];
        } else {
            //todo empty
            $this->Flash->set(__('empty_session_data'));
            //* test exception info
            new AppInternalInfo('Error : no customer_account data', $code = 500);
            //todo render or redirect
            return $this->redirect(['action' => 'customer_index']);
        }
        $this->set('customer_account', $customer_account);

        //* api post
        //* post
        if ($this->request->is('post')) {
            $this->CustomerAccount->set($this->request->data[self::MODEL_NAME_ACCOUNT]);
            if ( $this->CustomerAccount->validates()) {

                CakeSession::write(self::MODEL_NAME_ACCOUNT, $data);

            } else {
                $this->set('validErrors', $this->CustomerAccount->validationErrors);
                CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->CustomerAccount->validationErrors, true));
                //todo render
                return $this->render('customer_edit');
            }
        }

    }

    /**
     * 暫定 complete 完了
     */
    public function customer_complete()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export(CakeSession::read(self::MODEL_NAME_ACCOUNT), true));
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->data, true));
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->params, true));

        //todo reload
        $data = CakeSession::read([self::MODEL_NAME_ACCOUNT]);
        CakeSession::delete(self::MODEL_NAME_ACCOUNT);
        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'customer_index']);
        }
        $this->CustomerAccount->set($data);

        //* for API,  PUT or POST
        $existing_flag = false;
        $existing_customer_account = $this->CustomerAccount->apiGet();
        if (!empty($existing_customer_account->results[0])) {
            $existing_flag = true;
        }

        //* post
        if ($this->request->is('post')) {
            if ( $this->CustomerAccount->validates()) {

                $this->set('customer_account', $data);
                //* to API
                if ($existing_flag === true) {
                    $customer_account_result = $this->CustomerAccount->apiPut($data);
                } else {
                    $customer_account_result = $this->CustomerAccount->apiPost($data);
                }
                CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($customer_account_result, true));
                //* todo error
                if (!empty($customer_account_result->error_message)) {
                    $this->Flash->set($customer_account_result->error_message);
                    $this->redirect(['action' => 'customer_index']);
                }
            } else {
                $this->set('validErrors', $this->CustomerAccount->validationErrors);
                CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->CustomerAccount->validationErrors, true));
                //todo render redirectにする
                //return $this->render('customer_add');
            }
        }

    }


}
