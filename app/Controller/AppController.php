<?php
App::uses('Controller', 'Controller');
class AppController extends Controller
{
    public function beforeFilter()
    {
        parent::beforeFilter();
        CakeLog::write(DEBUG_LOG, get_class($this) . '::beforeFilter()');
    }

    public function afterFilter()
    {
        parent::afterFilter();
        CakeLog::write(DEBUG_LOG, get_class($this) . '::afterFilter()');
    }

    public function beforeRender()
    {
        parent::beforeRender();
        CakeLog::write(DEBUG_LOG, get_class($this) . '::beforeRender()');
    }
}
