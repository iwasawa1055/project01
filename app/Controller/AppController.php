<?php

App::uses('Controller', 'Controller');
App::uses('AppSecurity', 'Lib');

class AppController extends Controller
{
    // public $root_index_render = '/root_index';

		// ログインチェックが必要か？
    protected $checkLogined = true;

    /**
     * 制御前段処理
     *
     * @param	void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        //* Attack Request Block
        AppSecurity::blockAttackRequest();

        //* Agent Check
        Configure::write('Session.checkAgent', false);
        CakeSession::start();
        CakeSession::write('session_start', true);

        //* Request Count
        CakeSession::$requestCountdown = 10000;

        if ($this->checkLogined) {
            $this->loadModel('UserLogin');
            if (!$this->UserLogin->isLogined()) {
                $this->redirect('/login');
            }
        }
    }

    /**
     * レンダー前段処理
     *
     * @param	void
     */
    public function beforeRender()
    {
        parent::beforeRender();
    }

    /**
     * 制御後段処理
     *
     * @param	void
     */
    public function afterFilter()
    {
        parent::afterFilter();

        //* Click Jacking Block
        AppSecurity::blockClickJacking();
    }
}
