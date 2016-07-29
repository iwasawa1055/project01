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

    public function beforeFilter () {
        parent::beforeFilter();	
        $this->loadModel(self::MODEL_NAME);
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
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }

    /**
     * 暫定 complete 販売設定完了
     */
    public function complete()
    {
		$model = new Sale();	
		$model->set($this->request->data);
		//debug($model->data);
		//debug($model->toArray());
		if ($this->request->is('post')) {
            //* To APIiでき次第

            //* APIできるまで、ひとまずsession
            CakeSession::write(self::MODEL_NAME, $model->toArray());
		}

		//debug(CakeSession::read());

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
