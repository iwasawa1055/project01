<?php

App::uses('AppController', 'Controller');

class ApiController extends AppController
{
	
	public $test = null;

	/**
	 * 制御前段処理
	 *
	 * @access		public
	 * @param		void
	 * @return		void
	 * @todo		test
	 */
	public function beforeFilter()
	{
		AppController::beforeFilter();
		//* Test Double
		$this->test = Configure::read('app.test');
	}

	/**
	 * ルートインデックス
	 *
	 * @access		public
	 * @param		void
	 * @return		void
	 * @todo
	 */
	public function index()
	{
		//* Layout
		$this->layout = null;
		
		// test error Critical系 ALERT 
		//new AppExternalCritical(AppE::EXTERNAL_SERVER_ERROR , $status_code=500); 
		
		// test エラーレベル追加 Error系  ABORT
		//new AppTerminalError(AppE::NOT_FOUND, 404);
		
		//* Render
		$this->render($this->root_index_render);
	}
	

}

