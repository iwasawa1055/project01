<?php

App::uses('Controller', 'Controller');
App::uses('AppSecurity', 'Lib');
App::uses('DocumentModel', 'Model');

class AppController extends Controller
{

	// public $root_index_render = '/root_index';

	/**
	 * 制御前段処理
	 *
	 * @access	public
	 * @param	void
	 * @return	void
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

		//* Layout
		// $this->layout = null;
	}

	/**
	 * レンダー前段処理
	 *
	 * @access	public
	 * @param	void
	 * @return	void
	 */
	public function beforeRender()
	{
		parent::beforeRender();
	}

	/**
	 * 制御後段処理
	 *
	 * @access	public
	 * @param	void
	 * @return	void
	 */
	public function afterFilter()
	{
		parent::afterFilter();

		//* Click Jacking Block
		AppSecurity::blockClickJacking();
	}

}
