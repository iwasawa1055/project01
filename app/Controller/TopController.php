<?php

App::uses('AppController', 'Controller');
App::uses('UserAddress', 'Model');

class TopController extends AppController
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
		// $this->layout = null;

		// test error Critical系 ALERT
		//new AppExternalCritical(AppE::EXTERNAL_SERVER_ERROR , $status_code=500);

		// test エラーレベル追加 Error系  ABORT
		//new AppTerminalError(AppE::NOT_FOUND, 404);


		$this->loadModel('UserAddress');

		$email = '73@terrada.co.jp';
		$password = 'happyhappy';
		$this->UserAddress->login($email, $password);
		print_rh($this->UserAddress->apiGet());


		$this->UserAddress->set(['lastname' => 111]);
		$this->UserAddress->validates();
		$errors = $this->UserAddress->validationErrors;
		print_rh($errors);
		print_rh($this->UserAddress->data);
		// exit;


		//* Render
		// $this->render($this->root_index_render);
	}
}
