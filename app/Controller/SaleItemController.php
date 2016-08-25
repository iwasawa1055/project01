<?php

App::uses('MinikuraController', 'Controller');

/**
* 販売機能 アイテム　各ページ  
*
* 
*/
class SaleItemController extends MinikuraController
{
    const MODEL_NAME_SALES = 'Sales';
    const MODEL_NAME_INFO_ITEM = 'InfoItem';

    public function beforeFilter () {
        parent::beforeFilter(); 
        $this->loadModel(self::MODEL_NAME_SALES);
        $this->loadModel(self::MODEL_NAME_INFO_ITEM);
    }

    /**
     * index
     */
    public function index()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }

    /**
     * 暫定 edit
     * アイテムページのitem/detail/から遷移 
     */
    public function edit()
    {
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->params, true));
        //* todo 販売設定onか確認いる

        //* get
        if ($this->request->is('get')) {
            $id = $this->params['id'];
        }
        //* post
        if ($this->request->is('post')) {
            $data = $this->request->data[self::MODEL_NAME_SALES];
            $id = $data['item_id'];
            $this->Sales->set($data);
            if ( $this->Sales->validates()) {
                
                CakeSession::write(self::MODEL_NAME_SALES, $data);

            } else {
                $this->set('validErrors', $this->Sales->validationErrors);
                CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->Sales->validationErrors, true));
                //todo render
            }
        }
        $item = $this->InfoItem->apiGetResultsFind([], ['item_id' => $id]);
        if (empty($item)) {
            //* no data
        }
        $this->set('item', $item);

        $box = $item['box'];
        $this->set('box', $box);

    }

    /**
     * 暫定 complete
     */
    public function complete()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->data, true));
        //todo reload
        $data = CakeSession::read([self::MODEL_NAME_SALES]);
        CakeSession::delete(self::MODEL_NAME_SALES);
        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['controller' => 'item', 'action' => 'index' ]);
        }
        $this->Sales->set($data);

        //* post
        if ($this->request->is('post')) {
            if ( $this->Sales->validates()) {

                $this->set('sale_item', $data);
                //* to API
                $sales_result = $this->Sales->apiPost($data);
                //* todo error

                //* item, box
                $id = $data['item_id'];
                $item = $this->InfoItem->apiGetResultsFind([], ['item_id' => $id]);
                $this->set('item', $item);

                $box = $item['box'];
                $this->set('box', $box);

                //* sales 
                $sales_result = $this->Sales->apiGet(['item_id' => $id, 'sales_status' => SALES_STATUS_ON_SALE ]);
                if (!empty($sales_result->results[0])) {
                    $sales = $sales_result->results[0];
                    $sales_id = $sales['sales_id'];
                    //* market page url
                    $market_url = Configure::read('site.market.url').$sales_id;
                }
                $this->set('sales', $sales);
                $this->set('market_url', $market_url);

            } else {
                $this->set('validErrors', $this->Sales->validationErrors);
                //todo render
            }
        }
    }

    /**
     * 暫定 cancel
     */
    public function cancel()
    {
        CakeLog::write(BENCH_LOG, get_class($this) . __METHOD__);
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->data, true));
        if (!$this->request->is('post')) {
            //* error
        }
        //* post
        if ($this->request->is('post')) {
            $data = $this->request->data[self::MODEL_NAME_SALES];
            $id = $data['item_id'];
            $this->Sales->set($data);
            if ( $this->Sales->validates(['fieldList' => ['sales_id']])) {
                //* to API
                $sales_put_result = $this->Sales->apiPut($data);
                CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($sales_put_result, true));
                //todo error

            } else {
                $this->set('validErrors', $this->Sales->validationErrors);
                CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->Sales->validationErrors, true));
                //todo render
            }
            //* 表示用
            $item = $this->InfoItem->apiGetResultsFind([], ['item_id' => $id]);
            if (empty($item)) {
                //* no data
            }
            $this->set('item', $item);

            $box = $item['box'];
            $this->set('box', $box);
        }

    }

}
