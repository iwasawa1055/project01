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
    const MODEL_NAME_INFO_ITEM = 'InfoItem';

    public function beforeFilter () {
        parent::beforeFilter(); 
        $this->loadModel(self::MODEL_NAME_SALE);
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
     * 暫定 edit 販売設定
     */
    public function edit()
    {
        $sale_session = CakeSession::read(self::MODEL_NAME_SALE);

        CakeLog::write(DEBUG_LOG, var_export($sale_session, true));
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }

    /**
     * 暫定 complete 販売設定完了
     */
    public function complete()
    {
        //$model = new Sale();  
        //$model->set($this->request->data);

        //$this->Sale->set($this->request->data);
        $this->Sale->set($this->request->data[self::MODEL_NAME_SALE]);
        if ($this->request->is('post')) {
            //* To APIiでき次第
            //* on customer table update

            //* off customer table update, 出品情報all cancel
            

            //* APIできるまで、ひとまずsession
            //CakeSession::write(self::MODEL_NAME_SALE, $model->toArray());
            CakeSession::write(self::MODEL_NAME_SALE, $this->Sale->toArray());
        }

        CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'.var_export(CakeSession::read(), true));

        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }

    /**
     * 暫定 info
     */
    public function info()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }

    /**
     * 暫定 item
     */
    public function item()
    {
        CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->data, true));
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }

    /**
     * 暫定 confirm
     * アイテムページのdetail()から遷移してくる
     */
    public function confirm()
    {
        //* 販売用の情報 

        $data = $this->request->data[self::MODEL_NAME_SALE];
        $this->Sale->set($data);
        CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'.var_export($data, true));
        if ( $this->Sale->validates()) {
            //true
            //set , item情報もset
            $id = $data['item_id'];
            $item = $this->InfoItem->apiGetResultsFind([], ['item_id' => $id]);
            $this->set('item', $item);

            $box = $item['box'];
            $this->set('box', $box);
            CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'.var_export($item, true));
            CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'.var_export($box, true));
        } else {
            // todo sale/confirm.ctp 
            $this->set('validErrors', $this->Sale->validationErrors);
            //debug($this->Sale->validationErrors);
            //debug($this->Sale->invalidFields());
        }

        CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->referer(), true));
        CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->data, true));
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }

}
