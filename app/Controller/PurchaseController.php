<?php

App::uses('MinikuraController', 'Controller');

class PurchaseController extends MinikuraController
{
    // const MODEL_NAME = 'Purchase';

    public function beforeFilter ()
    {
        parent::beforeFilter();
        // Layouts
        $this->layout = 'c2c_sale';
        // $this->loadModel(self::MODEL_NAME);
    }

    public function input()
    {
        $id = $this->params['id'];

        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);
        CakeLog::write(DEBUG_LOG, $id);

    }

    public function confirm()
    {
        $id = $this->params['id'];

        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);
        CakeLog::write(DEBUG_LOG, $id);
    }

    public function complete()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);
        CakeLog::write(DEBUG_LOG, $id);
    }

}
