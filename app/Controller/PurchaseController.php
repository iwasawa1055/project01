<?php

App::uses('MinikuraController', 'Controller');

class PurchaseController extends MinikuraController
{
    // const MODEL_NAME = 'Purchase';

    public function beforeFilter ()
    {
        parent::beforeFilter();
        // Layouts
        $this->layout = 'market';
        // $this->loadModel(self::MODEL_NAME);
    }

    public function index()
    {
        $id = $this->params['id'];

        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);
        CakeLog::write(DEBUG_LOG, $id);

    }

    public function login()
    {
        $id = $this->params['id'];

        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);
        CakeLog::write(DEBUG_LOG, $id);

        return $this->redirect(['controller' => 'Purchase', 'action' => 'input', 'id' => $id]);
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
