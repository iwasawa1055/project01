<?php

App::uses('Model', 'Model');

class AppModel extends Model
{

	//* Init
	public $models = null;
	public $tables = null;
	public $auto_commit = null;
	public $confs = null;
	public $locked_type = null;
	public $locked_tables = array();

	//* Config
	public $locked = false;

	// Object Init
	public $Pdo = null;
	public $Stmt = null;

	// Active Record Object Init
	public $Record = null;
	public $Records = null;
	public $Field = null;
	public $Where = null;
	public $Wheres = null;
	public $Option = null;
	public $Describe = null;
	public $Column = null;
	public $PrimaryKey = null;
	public $RecordsKey = false;
	protected $_record = null;
	protected $_filed = null;
	protected $_where = null;
	protected $_option = null;
	protected $_binds = null;


	//* Constant
	const TO_CONDITION_PARAMETER_WHERE = 0;
	const TO_CONDITION_PARAMETER_HAVING = 1;

	/**
	 * インスタンス
     *
	 * @access		public
	 * @param		void
	 * @return		void
	 * @todo		devel
     **/
	public function __construct()
	{
		$use_db_config = $this->useDbConfig;
		require_once APP . 'Config' . DS . 'database.php';
		$Database = new DATABASE_CONFIG();
		$confs = empty($this->useDbConfig) ? $Database->default : $Database->$use_db_config;
		//debug($confs);

		$this->confs = $confs;
		$datasource = $confs['datasource'];
		$schema = ! empty($confs['database']) ? $confs['database'] : 'test';
		$host = ! empty($confs['host']) ? $confs['host'] : 'localhost';
		$port = ! empty($confs['port']) ? $confs['port'] : '3306';
		$charset = ! empty($confs['encoding']) ? $confs['encoding'] : 'utf8';
		$user = ! empty($confs['login']) ? $confs['login'] : 'root';
		$password = ! empty($confs['password']) ? $confs['password'] : '';
		$source_type = null;
		if (0 === strcasecmp($datasource, 'Database/Mysql')) {
			$source = 'mysql';
			$source_type = 1;
			$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $charset);
		} else if (0 === strcasecmp($datasource, 'Database/Postgres')) {
			$source = 'pgsql';
			$source_type = 1;
			$options = array();
		} else if (0 === strcasecmp($datasource, 'Database/Sqlite')) {
			$source = 'sqlite';
			$source_type = 2;
		}

		//$dsn = 'mysql:host=127.0.0.1;dbname=tamashiigarage';
		$dsn = $source . ':host=' . $host . ';port=' . $port . ';dbname=' . $schema . ';charset=' . $charset;
		if ($source_type === 1) {
			$this->Pdo = new PDO($dsn, $user, $password);
			//debug($dsn);
			//debug($user);
			//debug($password);
		} else if ($source_type === 2) {
			$this->Pdo = new PDO($source . ':' . $host . $schema);
		}
		$this->Pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$this->Pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//* Column Get
		$sql =<<<DOC
			DESCRIBE
				{$this->useTable}
DOC;

		$this->Stmt = $this->Pdo->prepare($sql);
		$this->Stmt->execute();
		$columns = array();
		$columns = $this->Stmt->fetchAll();
		$this->Stmt->closeCursor();

		$this->Describe = $columns;
		foreach ($columns as $i => $column) {
			//** Primary Key Get
			if ($column['Key'] === 'PRI') {
				$this->PrimaryKey = $column['Field'];
			}
			//* Column Name Paremeter
			$this->Column[$i] = $column['Field'];
		}
	}

	/**
	 * 拡張テーブル排他ロック
     *
	 * @access		public
	 * @param		mixed $_tables = テーブル名
	 * @return		void
	 * @todo		devel
     **/
	public function writeLock($_tables)
	{
		$tables = ! is_array($_tables) ? array($_tables) : $_tables;
		$this->locked_tables = $tables;
		$table_serial = implode(' WRITE, ', $tables);
		//* SQL
		$sql = 'LOCK TABLES ' . $table_serial . ' WRITE';
		if (! $this->Pdo->query($sql)) {
			throw new PdoException('Database Failed: Could not write lock table', 500);
		}
		$this->locked = true;
		$this->locked_type = 'write';
	}

	/**
	 * 拡張テーブル共有ロック
     *
	 * @access		public
	 * @param		mixed $_tables = テーブル名
	 * @return		void
	 * @todo		devel
     **/
	public function readLock($_tables)
	{
		$tables = ! is_array($_tables) ? array($_tables) : $_tables;
		$this->locked_tables = $tables;
		$table_serial = implode(' READ, ', $tables);
		//* SQL
		$sql = 'LOCK TABLES ' . $table_serial . ' READ';
		if (! $this->Pdo->query($sql)) {
			throw new PdoException('Database Failed: Could not read lock table', 500);
		}
		$this->locked = true;
		$this->locked_type = 'read';
	}

	/**
	 * 拡張テーブルロック解除
     *
	 * @access		public
	 * @param		void
	 * @return		void
	 * @todo		devel
     **/
	public function unlock()
	{
		if (! $this->Pdo->query('UNLOCK TABLES')) {
			throw new PdoException('Database Failed: Could not unlock table', 500);
		}
		$this->locked = false;
		$this->locked_type = null;
		$this->locked_tables = null;
	}

	/**
	 * 拡張オートコミットチェック
     *
	 * @access		public
	 * @param		void
	 * @return		bool
	 * @todo		devel
     **/
	public function isAutoCommit()
	{
		foreach ($this->Pdo->query('SELECT @@autocommit') as $row) {
			if ($row[0]['@@autocommit']) {
				return true;
			}
			return false;
		}
	}

	/**
	 * 拡張オートコッミット有効化
     *
	 * @access		public
	 * @param		void
	 * @return		void
	 * @todo		devel
     **/
	public function onAutoCommit()
	{
		if (! $this->Pdo->query('SET AUTOCOMMIT = 1')) {
			throw new PdoException('Database Failed: Could not set autocommit', 500);
		}
		$this->auto_commit = 1;
	}

	/**
	 * 拡張オートコッミット無効化
     *
	 * @access		public
	 * @param		vild
	 * @return		void
	 * @todo		devel
     **/
	public function unAutoCommit()
	{
		if (! $this->Pdo->query('SET AUTOCOMMIT = 0')) {
			throw new PdoException('Database Failed: Could not set unautocommit', 500);
		}
		$this->auto_commit = 0;
	}

	/**
	 * 拡張トランザクション
     *
	 * @access		public
	 * @param		mixed $_tables = テーブル名
	 * @return		void
	 * @todo		devel
     **/
	public function begins($_tables, $_lock_type = null)
	{
		if (empty($_tables)) {
			throw new PdoException('Invalid Arg: Not Arg', 500);
		}

		$tables = ! is_array($_tables) ? array($_tables) : $_tables;

		//** auto commit 無効化
		$this->isAutoCommit ? $this->unAutoCommit : null;

		//* Write Lock
		if ($_lock_type === 'wrirte') {
			$this->writeLock($tables);
		//* Read Lock
		} else if ($_lock_type === 'read') {
			$this->readLock($tables);
		}

		//* Transaction
		$this->Pdo->beginTransaction();
	}

	/**
	 * 拡張コミット
     *
	 * @access	public
	 * @param	void
	 * @return	void
	 * @todo	devel
     **/
	public function commits()
	{
		//* Unlock
		$this->locked ? $this->unlock() : null;
		//* Auto Commit Avairable
		! $this->autocommit ? $this->onAutoCommit() : null;
		//* Commit

		$this->Pdo->inTransaction() ? $this->Pdo->commit() : null;
	}

	/**
	 * 拡張ロールバック
     *
	 * @access	public
	 * @param	void
	 * @return	void
	 * @todo	devel
     **/
	public function rollbacks()
	{
		//* Unlock
		$this->locked ? $this->unlock() : null;
		//* Auto Commit Arairable
		! $this->autocommit ? $this->onAutoCommit() : null;
		//* Rollback
		$this->Pdo->inTransaction() ? $this->Pdo->rollBack() : null;
	}

	/**
	 * CakePHP 用拡張トランザクション
     *
	 * @access		public
	 * @param		void or mixed $_models = モデル名
	 *				引数なしの場合は共有ロック
	 *				モデル名指定の場合は排他ロック
	 * @return		void
	 * @todo		devel
     **/
	public function beginForCakeExtension($_models = array())
	{
		$models = ! is_array($_models) ? array($_models) : $_models;

		$dataSource = $this->getDataSource();
		//* Read Lock Of CakePHP
		// ロック順に注意
		$dataSource->begin();
		
		//* Write Lock Of Extension
		if (! empty($models)) {
			//** auto commit 無効化
			$auto_commit = $this->query('SELECT @@autocommit');
			if ($auto_commit[0][0]['@@autocommit']) {
				$this->query('SET AUTOCOMMIT=0;');
			}
			$this->auto_commit = 0;

			//** CakePHPライブラリ用にリアルテーブル名とエイリアステーブル名のパラメータ設定
			$this->models = $models;
			$i = 0;
			foreach ($models as $model) {
				//*** Table Name
				$snake = preg_replace('/([A-Z])/', '_$1', $model);
				$lower = strtolower($snake);
				$table = preg_replace('/^_(.+)$/', '$1', $lower);
				$this->tables[$i] = $table;
				//*** Real Table Name For Update
				$names[] = $table;
				//*** Alias Table Name For Select
				$names[] = $table . ' AS ' . $model;
				$i++;
			}
			$names_serial = implode(' WRITE, ', $names);

			//* SQL
			$sql = 'LOCK TABLES ' . $names_serial . ' WRITE';

			//* Write Lock Of Exetnsion
			$this->query($sql);
			$this->locked = true;
		}
	}

	/**
	 * initParameter
	 *
	 * @access		public
	 * @param		void
	 * @return		void
	 * @todo
	 */
	public function initParameter()
	{
		$this->RecordsKey = false;
		$this->Record = array();
		$this->Records = array();
		$this->Field = null;
		$this->Where = array();
		$this->Wheres = array();
		$this->Option = array();
		$this->_record = null;
		$this->_field = null;
		$this->_where = null;
		$this->_option = null;
		$this->_binds = null;
	}

	/**
	 * AR 参照
	 *
	 * @access		public
	 * @param		void
	 * @return		array 結果セット
	 * @todo
	 */
	public function selects()
	{
		//* Parameters
		$this->_field = $this->_toFieldParameter();
		$where = $this->_checkWhere();
		$this->_where = $this->_toConditionParameter($where);
		$this->_option = $this->_toOptionParameter();

		//* Exec
		$sql =<<<DOC
			SELECT
				{$this->_field}
			FROM
				{$this->useTable}
				{$this->_where}
				{$this->_option}
DOC;

		//* Prepared Statement
		$this->Stmt = $this->Pdo->prepare($sql);
		$this->Stmt->execute($this->_binds);
		//debug($this->Stmt);

		//* Fetch
		$results = array();
		$results = $this->Stmt->fetchAll();

		//* Close
		$this->Stmt->closeCursor();

		//* Return
		$this->Records = $results;
		$this->_binds = array();
		return $results;
	}

	/**
	 * AR レコード新規作成
	 *
	 * @access		public
	 * @param		void
	 * @return		mixed Success：primary key, Failure: false
	 * @object		+ primary key
	 * @todo
	 */
	public function inserts()
	{
		//* Record Check
		$record = $this->_checkRecord();

		//* Parameters
		$this->_record = $this->_toRecordParameter($record);

		try {
			//* Transaction
			$this->begins($this->useTable);

			//* Place Holder
			$sql =<<<DOC
				INSERT INTO
					{$this->useTable}
				SET
					{$this->_record}
DOC;
			//debug($sql);

			//* Prepared Statement
			$this->Stmt = $this->Pdo->prepare($sql);
			$flag = $this->Stmt->execute($this->_binds);
			//debug($this->Stmt);

			if ($flag) {
				//* Fetch
				$primary_key = $this->PrimaryKey;
				$id = $this->Pdo->lastInsertId($primary_key);
				$result = $id;
				$this->Record[$primary_key] = $id;
			} else {
				$result = $flag;
			}

			//* Close
			$this->commits();
			$this->Stmt->closeCursor();

			//* Return
			$this->_binds = array();
			return $result;

		} catch (PdoException $e) {
			$this->rollbacks();
			throw $e;
		}
	}

	/**
	 * AR 複数レコード新規作成
	 *
	 * @access		public
	 * @param		void
	 * @return		array 結果セット
	 * @todo
	 */
	public function insertsAll()
	{
		//* Records Check
		$records = $this->_checkRecordAll();

		try {
			$results = array();
			foreach ($records as $i => $record) {
				//* Parameters
				$this->_record = $this->_toRecordParameter($record);

				//* Place Holder
				$sql =<<<DOC
					INSERT INTO
						{$this->useTable}
					SET
						{$this->_record}
DOC;
				//debug($sql);

				//* Transaction
				$this->begins($this->useTable);

				//* Prepared Statement
				$this->Stmt = $this->Pdo->prepare($sql);
				$flag = $this->Stmt->execute($this->_binds);
				//debug($this->Stmt);

				//* Fetch
				if ($flag) {
					$primary_key = $this->PrimaryKey;
					$id = $this->Pdo->lastInsertId($primaty_key);
					$this->Recordis[$i][$primaty_key] = $id;
					$results[$i] = $id;
				} else {
					$results[$i] = $flag;
				}

				//* Close
				$this->commits();
				$this->Stmt->closeCursor();
			}

			//* Return
			$this->_binds = array();
			return $results;

		} catch (PdoException $e) {
			$this->rollbacks();
			throw $e;
		}
	}

	/**
	 * AR レコード更新
	 *
	 * @access		public
	 * @param		void
	 * @return		bool Success: true, Failure: false
	 * @todo
	 */
	public function updates()
	{
		//* Record Check
		$record = $this->_checkRecord();

		//* Where Check
		$where = $this->_checkWhere();

		//* Parameters
		$this->_record = $this->_toRecordParameter($record);
		$this->_where = $this->_toConditionParameter($where);

		//* Place Holder
		$sql =<<<DOC
			UPDATE
				{$this->useTable}
			SET
				{$this->_record}
				{$this->_where}
DOC;

		//* Prepared Statement
		$this->Stmt = $this->Pdo->prepare($sql);
		$flag = $this->Stmt->execute($this->_binds);
		//debug($this->Stmt);

		//* Close
		$this->Stmt->closeCursor();

		//* Return
		$this->_binds = array();
		return $flag;
	}

	/**
	 * AR 複数レコード更新
	 *
	 * @access		public
	 * @param		void
	 * @return		array 結果セット
	 * @todo
	 */
	public function updatesAll()
	{
		//* Records Check
		$records = $this->_checkRecordAll();

		//* Conditions Check
		$wheres = $this->_checkWhereAll();

		$results = array();
		foreach ($records as $i => $record) {
			//* Parameters
			$this->_record = $this->_toRecordParameter($record);
			//* Parameters
			$this->_where = $this->_toConditionParameter($wheres[$i]);

			//* Place Holder
			$sql =<<<DOC
				UPDATE
					{$this->useTable}
				SET
					{$this->_record}
					{$this->_where}
DOC;

			//* Prepared Statement
			$this->Stmt = $this->Pdo->prepare($sql);
			$flag = $this->Stmt->execute($this->_binds);
			//debug($this->Stmt);
			$results[$i] = $flag;

			//* Close
			$this->Stmt->closeCursor();
			$this->_binds = array();
		}

		//* Return
		return $results;
	}

	/**
	 * AR レコード新規作成または更新
	 *
	 * @access		public
	 * @param		void
	 * @return		mixed Success: primary key, Failure: false
	 * @object		+ primary key
	 * @todo
	 */
	public function saves()
	{
		//* Record Check
		$record = $this->_checkRecord();

		//* Primary Key Check
		$primary_key = $this->PrimaryKey;
		if (empty($record[$primary_key])) {
			$flag = 'insert';
		} else {
			$primary_value = $record[$primary_key];
			$this->Where = array();
			$this->Where[$primary_key] = array('=', $primary_value, 'END');
			$this->Option = array();
			$this->Option['ORDER BY'] = $primary_key . ' ASC';
			$this->Option['LIMIT'] = '1';
			$selects = $this->selects();
			$flag = empty($selects) ? 'insert' : 'update';
		}

		//* Insert
		if ($flag === 'insert') {
			$results = $this->inserts();
		//* Update
		} else {
			$results = $this->updates();
		}

		//* Return
		$this->_binds = array();
		return $results;
	}

	/**
	 * AR 複数レコード新規作成または更新
	 *
	 * @access		public
	 * @param		void
	 * @return		array 結果セット
	 * @todo
	 */
	public function savesAll()
	{
		//* Records Check
		$records = $this->_checkRecordAll();

		$results = array();
		foreach ($records as $i => $record) {
			//* Primary Key Check
			$primary_key = $this->PrimaryKey;
			if (empty($record[$primary_key])) {
				$flag = 'insert';
			} else {
				$primary_value = $record[$primary_key];
				$this->Where = array();
				$this->Where[$primary_key] = array('=', $primary_value, 'END');
				$this->Option = array();
				$this->Option['ORDER BY'] = $primary_key . ' ASC';
				$this->Option['LIMIT'] = '1';
				$this->RecordsKey = false;
				$selects = $this->selects();
				$flag = empty($selects) ? 'insert' : 'update';
			}

			$this->Record = $record;
			$this->RecordsKey = false;

			//* Insert
			if ($flag === 'insert') {
				$results[$i] = $this->inserts();
			//* Update
			} else {
				$results[$i] = $this->updates();
			}
			$this->_binds = array();
		}

		//* Return
		return $results;
	}

// Private Method //////////////////////////////////////////////////////////////

	/**
	 * 2次元配列化
	 *
	 * @access		public
	 * @param		array $_befores = 変換前配列
	 * @return		array $_afters = 変換後配列
	 * @todo
	 */
	protected function _to2dArray($_befores)
	{
		if (! is_array($_befores)) {
			throw new PdoException('Argument Invalid In Prog: Not Array', 500);
		}

		$afters = array();
		foreach ($_befores as $first) {
			if (is_array($first)) {
				throw new PdoException('Argument Invalid In Prog: Not 1D Array', 500);
			}
		}
		$afters = $_befores;

		return $afters;
	}

	/**
	 * 3次元配列化
	 *
	 * @access		public
	 * @param		array $_befores = 変換前配列
	 * @return		array $_afters = 変換後配列
	 * @todo
	 */
	protected function _to3dArray($_befores)
	{
		$befores = ! is_array($_befores) ? array($_befores) : $_befores;

		$afters = array();
		foreach ($befores as $i => $first) {
			// 2D Array
			if (! is_array($first)) {
				$afters[] = $befores[$i];
				break;
			}
			foreach ($first as $key => $second) {
				// 3D Array
				if (! is_array($second)) {
					$afters = $befores;
					break;
				}
				// 4D Array
				if ($key !== $this->useTable) {
					continue;
				}
				$afters[$i] = $second;
			}
		}

		return $afters;
	}

	/**
	 * フィールドパラメータプリペアドステートメント
	 *
	 * @access		public
	 * @param		void
	 * @return		string ステートメント
	 * @todo
	 */
	protected function _toFieldParameter()
	{
		$field = $this->Field;

		if (empty($field) || $field === '*') {
			$field = '*';
			return $field;
		}

		if (is_array($field)) {
			throw new PdoException('Field Invalid: Not Scalar', 500);
		}

		if (preg_match('/[^\da-z_,()=<>!.\s]/i', $field)) {
			throw new PdoException('Field Invalid: Invalid Charecter (regex)', 500);
		}

		if (preg_match('/ from /i', $field)) {
			throw new PdoException('Field Invalid: Invalid Charecter (from)', 500);
		}

		return $field;
	}

	/**
	 * セットパラメータプリペアドステートメント
	 *
	 * @access		public
	 * @param		void
	 * @return		string ステートメント
	 * @todo
	 */
	protected function _toRecordParameter($_record)
	{
		//* to 2D Array
		$this->_record = $this->_to2dArray($_record);

		//ex. $this->Record['name'] = 'aaa';
		$params = array();
		foreach ($this->_record as $key => $value) {
			if (preg_match('/[^\da-z_]/i', $key)) {
				throw new PdoException('Invalid Arg: Invalid Set Parameter Key', 500);
			}
			$params[] = $key . ' = ?';
			$this->_binds[] = $value;
		}
		$result = implode(', ', $params);

		return $result;
	}

	/**
	 * 条件パラメータプリペアドステートメント
	 *
	 * @access		public
	 * @param		string $_type WHERE | HAVING
	 * @return		string ステートメント
	 * @todo
	 */
	protected function _toConditionParameter($_conds, $_type = self::TO_CONDITION_PARAMETER_WHERE)
	{
		if (empty($_conds)) {
			throw new PdoException('Member Variable Invalid: Not Where', 500);
		}

		//* Config
		//ex. $this->Where = array(array('name', '=', '111', 'AND'));
		$valids['key'] = '/[^\da-z_.]/i';
		$valids[0] = array('=', '<', '>', '<=', '>=', '!=', '<>', '<=>', 'LIKE', 'IS', 'IS NOT', 'BETWEEN');
		$valids[2] = array('AND', 'AND (', ') AND', '&&', '&& (', ') &&', 'OR', 'OR (', ') OR', '||', '|| (', ') ||', 'XOR', 'XOR (', ') XOR'. 'NOT', 'NOT (', ') NOT', '!', '! (', ') !', 'END', ') END');

		$type = $_type === self::TO_CONDITION_PARAMETER_WHERE ? 'WHERE' : 'HAVING';

		$stmt = ' ' . $type . ' ';

		//* Check
		foreach ($_conds as $key => $params) {
			//** Parameter Count
			if (3 !== count($params)) {
				throw new PdoException('Database Failed: aInvalid Condition Parameter', 500);
			}
			//** key
			if (preg_match($valids['key'], $key)) {
				throw new PdoException('Invalid Arg: bInvalid  Condition Parameter', 500);
			}
			//** 0th
			if (! isset($params[0]) || ! in_array($params[0], $valids[0])) {
				throw new PdoException('Invalid Arg: cInvalid  Condition Parameter', 500);
			}
			//** 1st
			if (! isset($params[1]) && $params[1] !== null) {
				throw new PdoException('Invalid Arg: dInvalid  Condition Parameter', 500);
			}

			//*** To Binder
			$this->_binds[] = $params[1];

			//** 2nd
			if (! isset($params[2]) || ! in_array($params[2], $valids[2])) {
				throw new PdoException('Invalid Arg: eInvalid  Condition Parameter', 500);
			}

			//** Join
			$stmt .= $key . ' ' . $params[0] . ' ? ' . $params[2] . ' ';
		}
		if (! $stmt = preg_replace('/END(?:\s*)$/', ' ', $stmt, -1, $count)) {
			throw new PdoException('Invalid Arg: fInvalid  Condition Parameter', 500);
		}
		if ($count === 0) {
			throw new PdoException('Invalid Arg: gInvalid  Condition Parameter', 500);
		}

		return $stmt;
	}

	/**
	 * オプションパラメータプリペアドステートメント
	 *
	 * @access		public
	 * @param		void
	 * @return		string ステートメント
	 * @todo
	 */
	protected function _toOptionParameter()
	{
		//* Variable Check
		if (! isset($this->Option)) {
			throw new PdoException('Member Variable Invalid: Not Option', 500);
		}

		$option = $this->Option;
		if ($option === null) {
			return $option;
		}

		//* Config
		/*
		$this->OPTION['ORDER BY'] = 'id, name ASC';
		$this->OPTION['GROUP BY'] = 'id, name ASC';
		$this->OPTION['LIMIT'] = '3, 10';
		$this->OPTION['ORDER BY'] = '10 OFFSET 3';
		$this->OPTION['HAVING']['name'] = array('=', 'aaa', 'AND')
		$this->Option['FOR UPDATE'] = '';
		$this->Option['LOCK IN SHARE MODE'] = '';
	*/
		$order_regex = '/^[\da-z_,.\s]* (?:ASC|DESC)$/i';
		$limit_regex = '/^\d+$|^\d+,(?:\s*?)\d+$|^\d+(?:\s+?)OFFSET(?:\s+?)\d+$/i';
		$stmt = '';

		//* Check
		foreach ($option as $key => $value) {
			//** ORDER GROUP
			if ($key === 'ORDER BY' || $key === 'GROUP BY') {
				//debug($param);
				if (! preg_match($order_regex, $value)) {
					throw new PdoException('Invalid Arg: Invalid Field Parameter', 500);
				}
				$stmt .= ' ' . $key . ' ' . $value . ' ';
				continue;
			}
			//** LIMIT
			if ($key === 'LIMIT') {
				if (! preg_match($limit_regex, $value)) {
					throw new PdoException('Invalid Arg: Invalid Field Parameter', 500);
				}
				$stmt .= ' ' . $key . ' ' . $value . ' ';
				continue;
			}
			//** HAVING
			if ($key === 'HAVING') {
				$having = $this->_toConditionParameter($value, self::TO_CONDITION_PARAMETER_HAVING);
				$stmt .= ' ' . $having . ' ';
				continue;
			}
		}

		return $stmt;
	}

	public function _checkRecord()
	{
		//* Variable Check
		if (! isset($this->RecordsKey)) {
			throw new PdoException('Member Variable Invalid: Not RecordsKey', 500);
		}
		if ($this->RecordsKey === false) {
			if (! isset($this->Record)) {
				throw new PdoException('Member Variable Invalid: Not Record', 500);
			}
			$record = $this->Record;
		} else if (is_int($this->RecordsKey)) {
			if (! isset($this->Records[$this->RecordsKey])) {
				throw new PdoException('Member Variable Invalid: Not Records[RecordsKey]', 500);
			}
			$record = $this->Records[$this->RecordsKey];
		} else {
			throw new PdoException('Member Variable Invalid: Invalid RecordsKey', 500);
		}
		return $record;
	}

	public function _checkWhere()
	{
		//* Variable Check
		if (! isset($this->RecordsKey)) {
			throw new PdoException('Member Variable Invalid: Not RecordsKey', 500);
		}
		if ($this->RecordsKey === false) {
			if (! isset($this->Where)) {
				throw new PdoException('Member Variable Invalid: Not Record', 500);
			}
			$where = $this->Where;
		} else if (is_int($this->RecordsKey)) {
			if (! isset($this->Wheres[$this->RecordsKey])) {
				throw new PdoException('Member Variable Invalid: Not Records[RecordsKey]', 500);
			}
			$where = $this->Wheres[$this->RecordsKey];
		} else {
			throw new PdoException('Member Variable Invalid: Invalid RecordsKey', 500);
		}
		return $where;
	}

	public function _checkRecordAll()
	{
		//* Variable Check
		if (! isset($this->RecordsKey)) {
			throw new PdoException('Member Variable Invalid: Not RecordsKey', 500);
		}

		$records = array();
		if ($this->RecordsKey === false) {
			if (! isset($this->Record)) {
				throw new PdoException('Member Variable Invalid: Not Record', 500);
			}
			$records[] = $this->Record;
		} else if (is_int($this->RecordsKey)) {
			if (! isset($this->Records[$this->RecordsKey])) {
				throw new PdoException('Member Variable Invalid: Not Records[RecordsKey]', 500);
			}
			$records[] = $this->Records[$this->RecordsKey];
		} else if (is_array($this->RecordsKey)) {
			foreach ($this->RecordsKey as $key) {
				if (! isset($this->Records[$key])) {
					throw new PdoException('Member Variable Invalid: Not Records[key]', 500);
				}
				$records[$key] = $this->Records[$key];
			}
		} else if ($this->RecordsKey === true) {
			$records = $this->Records;
		} else {
			throw new PdoException('Member Variable Invalid: Invalid RecordsKey', 500);
		}

		return $records;
	}

	public function _checkWhereAll()
	{
		//* Variable Check
		if (! isset($this->RecordsKey)) {
			throw new PdoException('Member Variable Invalid: Not RecordsKey', 500);
		}

		$wheres = array();
		if ($this->RecordsKey === false) {
			if (! isset($this->Where)) {
				throw new PdoException('Member Variable Invalid: Not Record', 500);
			}
			$wheres[] = $this->Where;
		} else if (is_int($this->RecordsKey)) {
			if (! isset($this->Wheres[$this->RecordsKey])) {
				throw new PdoException('Member Variable Invalid: Not Records[RecordsKey]', 500);
			}
			$wheres[] = $this->Wheres[$this->RecordsKey];
		} else if (is_array($this->RecordsKey)) {
			foreach ($this->RecordsKey as $key) {
				if (! isset($this->Wheres[$key])) {
					throw new PdoException('Member Variable Invalid: Not Records[key]', 500);
				}
				$wheres[$key] = $this->Wheres[$key];
			}
		} else if ($this->RecordsKey === true) {
			$wheres = $this->Wheres;
		} else {
			throw new PdoException('Member Variable Invalid: Invalid RecordsKey', 500);
		}

		return $wheres;
	}




}

