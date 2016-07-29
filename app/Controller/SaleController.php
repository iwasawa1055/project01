<?php

App::uses('MinikuraController', 'Controller');

/**
* 販売機能設定ページ  
*
* 
*/
class SaleController extends MinikuraController
{
    const MODEL_NAME = 'Sale';

    /**
     * index
     */
    public function index()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }

    /**
     * 暫定 edit
     */
    public function edit()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }

    /**
     * 暫定 info
     */
    public function info()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }

}
