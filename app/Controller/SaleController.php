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
        //* stub 販売情報
        $sale_results = [];
        $sale_results[] = ['created' => '2016/09/25', 'title' => '商品名1111', 'price' => '999'];
        $sale_results[] = ['created' => '2016/09/25', 'title' => '商品名222', 'price' => '222'];
        $sale_results[] = ['created' => '2016/09/25', 'title' => '商品名333', 'price' => '3000'];
        $sale_results[] = ['created' => '2016/09/25', 'title' => '商品名444', 'price' => '4000'];
        $sale_results[] = ['created' => '2016/09/25', 'title' => '商品名555', 'price' => '5000'];
        $this->set('sale_results', $sale_results);

    }

    /**
     * 暫定 change 販売設定
     */
    public function change()
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



}
