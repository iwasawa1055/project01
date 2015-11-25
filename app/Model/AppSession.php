<?php

App::uses('AppModel', 'Model');
App::uses('AppController', 'Controller');

class AppSession extends AppModel {

	public $name = 'AppSession';
	public $useDbConfig = 'default';
	public $useTable = 'sessions';

	public $session_key = null;
	public $expiry = 86400;
	public $redirect_url = '/login';

	public $AppController = null;

	public function is()
	{
		session_write_close();
		session_name('MINIAUCTION');
		session_start();
		if (! $this->read()) {
			//$this->AppController->redirect($this->redirect_url);
		}
		if (! CakeSession::check('mini_auction.login')) {
			//$this->AppController->redirect($this->redirect_url);
		}
		return true;
	}

	public function read()
	{
		$this->session_key = $_COOKIE['zenid'];

		$now = time();
		$sql = <<<DOC
			SELECT
				value
			FROM
				{$this->useTable}
			WHERE
				sesskey = ? AND
				expiry > ?
			LIMIT 1
DOC;

		//* Prepared Statement
		$this->Stmt = $this->Pdo->prepare($sql);
		$this->Stmt->execute(array($this->session_key, $now));
		//debug($this->Stmt);

		//* Fetch
		$rows = array();
		$rows = $this->Stmt->fetchAll();

		//* Close
		$this->Stmt->closeCursor();

		if (! $rows) {
			return false;
		}
		if (! session_decode($rows[0]['value'])) {
			throw new PdoException(AppE::SESSION, 500);
		}

		CakeSession::write('mini_auction.login', true);
	}

	public function update()
	{
		$this->session_key = $_COOKIE['zenid'];

		if(! $session = $this->read()) {
			return false;
		}

		$expiry = time() + $this->expiry;
		$sql = <<<DOC
			UPDATE
				{$this->useTable}
			SET
				value = ?,
				expiry = ?
			WHERE
				sesskey = ?
DOC;

		$value = session_encode();
		//* Prepared Statement
		$this->Stmt = $this->Pdo->prepare($sql);
		$this->Stmt->execute(array($value, $expiry, $this->session_key));
		//debug($this->Stmt);

		//* Fetch
		$rows = array();
		$rows = $this->Stmt->fetchAll();

		//* Close
		$this->Stmt->closeCursor();

		if (! $rows) {
			return false;
		}
		return true;
	}

	public function drop()
	{
		$_SESSION = array();
		session_destroy();
		// cookie 削除あとでやる
	}

	public function toReferer()
	{
		$ref = CakeSession::check('mini_auction.current') ? CakeSession::read('mini_auction.current') : null;
		CakeSession::write('mini_auction.referer', $ref);
		$traces = debug_backtrace();
		CakeSession::write('mini_auction.current', $traces[1]['function']);
	}

	public function __destruct()
	{
		$this->Pdo = null;
	}

}

