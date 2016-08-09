<?php

App::uses('MinikuraController', 'Controller');

/**
* 販売機能 アイテム　各ページ  
*
* 
*/
class SaleItemController extends MinikuraController
{
    const MODEL_NAME_SALE = 'Sale';
    const MODEL_NAME_SALE_ITEM = 'SaleItem';
    const MODEL_NAME_INFO_ITEM = 'InfoItem';

    public function beforeFilter () {
        parent::beforeFilter(); 
        $this->loadModel(self::MODEL_NAME_SALE);
        $this->loadModel(self::MODEL_NAME_SALE_ITEM);
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
     * アイテムページのdetail()から遷移してくる
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
            $data = $this->request->data[self::MODEL_NAME_SALE_ITEM];
            $id = $data['item_id'];
            $this->SaleItem->set($data);
            if ( $this->SaleItem->validates()) {
                //* to API
                
                CakeSession::write(self::MODEL_NAME_SALE_ITEM, $data);

            } else {
                $this->set('validErrors', $this->SaleItem->validationErrors);
                CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->SaleItem->validationErrors, true));
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
        $data = CakeSession::read([self::MODEL_NAME_SALE_ITEM]);
        CakeSession::delete(self::MODEL_NAME_SALE_ITEM);
        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['controller' => 'item', 'action' => 'index' ]);
        }
        $this->SaleItem->set($data);

        //* post
        if ($this->request->is('post')) {
            if ( $this->SaleItem->validates()) {

                $this->set('sale_item', $data);
                //* to API

                //* item, box
                $id = $data['item_id'];
                $item = $this->InfoItem->apiGetResultsFind([], ['item_id' => $id]);
                $this->set('item', $item);

                $box = $item['box'];
                $this->set('box', $box);

            } else {
                $this->set('validErrors', $this->SaleItem->validationErrors);
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
            $data = $this->request->data[self::MODEL_NAME_SALE_ITEM];
            $id = $data['item_id'];
            $this->SaleItem->set($data);
            if ( $this->SaleItem->validates()) {
                //* to API
                
                CakeSession::write(self::MODEL_NAME_SALE_ITEM, $data);

            } else {
                $this->set('validErrors', $this->SaleItem->validationErrors);
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
