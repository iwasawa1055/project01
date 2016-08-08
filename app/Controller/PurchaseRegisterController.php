<?php

App::uses('MinikuraController', 'Controller');

class PurchaseRegisterController extends MinikuraController
{
    // const MODEL_NAME = 'Purchase';

    public function beforeFilter ()
    {
        parent::beforeFilter();
        // Layouts
        $this->layout = 'market';
        // $this->loadModel(self::MODEL_NAME);
    }

    public function address()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);
        CakeLog::write(DEBUG_LOG, $id);

    }

    public function credit()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);
        CakeLog::write(DEBUG_LOG, $id);

    }

    public function confirm()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);
        CakeLog::write(DEBUG_LOG, $id);
    }

    public function complete()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);
        CakeLog::write(DEBUG_LOG, $id);
    }

}
