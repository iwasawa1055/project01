<?php

App::uses('MinikuraController', 'Controller');

class RegisterController extends MinikuraController
{
    const MODEL_NAME = 'CustomerEntry';
    const MODEL_NAME_REGIST = 'CustomerRegistInfo';
    const MODEL_NAME_CORP_REGIST = 'CorporateRegistInfo';
	//* nike_snkrs alliance_cd
	const SNEAKERS_ALLIANCE_CD = 'api.sneakers.alliance_cd';
	const SNEAKERS_FILE_KEY_LIST = 'api.sneakers.file.key_list';
	const SNEAKERS_FILE_REGISTERED_LIST = 'api.sneakers.file.registered_list';
	const SNEAKERS_FILE_ERROR_LIST = 'api.sneakers.file.error_list';
	const SNEAKERS_DIR = 'api.sneakers.dir';
	const SNEAKERS_REASON_NOT_EXIST = 'sneakers key is not exist';
	const SNEAKERS_REASON_REGISTERED = 'sneakers key has already registered';

    // ログイン不要なページ
    protected $checkLogined = false;

    /**
     * アクセス拒否.
     */
    protected function isAccessDeny()
    {
        if ($this->Customer->isLogined()) {
            return true;
        }

        return false;
    }

    /**
     * エントリー登録フォーム
     *  - 現在は非アクティブ 2017.2.16
     */
    public function customer_add()
    {
        $queries = array();
        $query = '';
        // 初回キット購入導線へリダイレクト
        if (!empty($this->request->query)) {
            foreach ($this->request->query as $param => $value) {
                $queries[] = "{$param}=$value";
            }
            $query = implode('&', $queries);
            $query = "?" . $query;
        }
        return $this->redirect('/first_order/' . $query);

        // 紹介コード
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);
        $this->request->data[self::MODEL_NAME]['alliance_cd'] = $code;

        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = [self::MODEL_NAME => CakeSession::read(self::MODEL_NAME)];
            $this->request->data[self::MODEL_NAME]['password'] = '';
            $this->request->data[self::MODEL_NAME]['password_confirm'] = '';
        }
        CakeSession::delete(self::MODEL_NAME);
    }

    /**
     * エントリー登録フォーム（確認）
     *  - 現在は非アクティブ 2017.2.16
     */
    public function customer_confirm()
    {
        $queries = array();
        $query = '';
        // 初回キット購入導線へリダイレクト
        if (!empty($this->request->query)) {
            foreach ($this->request->query as $param => $value) {
                $queries[] = "{$param}=$value";
            }
            $query = implode('&', $queries);
            $query = "?" . $query;
        }
        return $this->redirect('/first_order/' . $query);

        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);

        $this->loadModel(self::MODEL_NAME);
        $this->CustomerEntry->set($this->request->data);

        if ($this->CustomerEntry->validates()) {
            CakeSession::write(self::MODEL_NAME, $this->CustomerEntry->toArray());
        } else {
            return $this->render('customer_add');
        }
    }

    /**
     * エントリー登録フォーム（完了）
     *  - 現在は非アクティブ 2017.2.16
     */
    public function customer_complete()
    {
        $queries = array();
        $query = '';
        // 初回キット購入導線へリダイレクト
        if (!empty($this->request->query)) {
            foreach ($this->request->query as $param => $value) {
                $queries[] = "{$param}=$value";
            }
            $query = implode('&', $queries);
            $query = "?" . $query;
        }
        return $this->redirect('/first_order/' . $query);

        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);

        $data = CakeSession::read(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME);
        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'customer_add', '?' => ['code' => $code]]);
        }

        $this->loadModel(self::MODEL_NAME);
        $this->CustomerEntry->set($data);

        if ($this->CustomerEntry->validates()) {
            // 仮登録
            $res = $this->CustomerEntry->entry();
            if (!empty($res->error_message)) {
                $this->CustomerEntry->data[self::MODEL_NAME]['password'] = '';
                $this->CustomerEntry->data[self::MODEL_NAME]['password_confirm'] = '';
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'customer_add', '?' => ['code' => $code]]);
            }

            // ログイン
            $this->loadModel('CustomerLogin');
            $this->CustomerLogin->data['CustomerLogin']['email'] = $this->CustomerEntry->data[self::MODEL_NAME]['email'];
            $this->CustomerLogin->data['CustomerLogin']['password'] = $this->CustomerEntry->data[self::MODEL_NAME]['password'];

            $res = $this->CustomerLogin->login();
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->render('customer_add');
            }

            // カスタマー情報を取得しセッションに保存
            $this->Customer->setTokenAndSave($res->results[0]);
            $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);
            $this->Customer->getInfo();

            // 完了画面
            $this->set('alliance_cd', $this->CustomerEntry->data[self::MODEL_NAME]['alliance_cd']);
            return $this->render('customer_complete');

        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'customer_add', '?' => ['code' => $code]]);
        }
    }

    /**
     * 本登録フォーム
     *  - 現在はこちらのみアクティブ 2017.2.16
     */
    public function customer_add_info()
    {
        // 紹介コード
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);
        $this->request->data[self::MODEL_NAME_REGIST]['alliance_cd'] = $code;

        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = [self::MODEL_NAME_REGIST => CakeSession::read(self::MODEL_NAME_REGIST)];
            $this->request->data[self::MODEL_NAME_REGIST]['password'] = '';
            $this->request->data[self::MODEL_NAME_REGIST]['password_confirm'] = '';
        }
        CakeSession::delete(self::MODEL_NAME_REGIST);
    }

    /**
     * 
     */
    public function customer_confirm_info()
    {
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);

        $this->loadModel(self::MODEL_NAME_REGIST);

        $data = $this->request->data[self::MODEL_NAME_REGIST];
        $data['birth'] = CUSTOMER_DEFAULT_BIRTH;
        $data['gender'] = CUSTOMER_DEFAULT_GENDER;
        $this->CustomerRegistInfo->set($data);

        if ($this->CustomerRegistInfo->validates()) {
            CakeSession::write(self::MODEL_NAME_REGIST, $this->CustomerRegistInfo->toArray());
        } else {
            return $this->render('customer_add_info');
        }
    }

    /**
     *
     */
    public function customer_complete_info()
    {
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);

        $data = CakeSession::read(self::MODEL_NAME_REGIST);
        CakeSession::delete(self::MODEL_NAME_REGIST);
        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'customer_add_info', '?' => ['code' => $code]]);
        }

        $this->loadModel(self::MODEL_NAME_REGIST);
        $this->CustomerRegistInfo->set($data);

        if ($this->CustomerRegistInfo->validates()) {
            // 部屋番号
            $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['address3'] = $data['address3'] . $data['room'];

            if (empty($this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['alliance_cd'])) {
                unset($this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['alliance_cd']);
            }

            // 本登録
            $res = $this->CustomerRegistInfo->regist();
            if (!empty($res->error_message)) {
                $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['password'] = '';
                $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['password_confirm'] = '';
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'customer_add_info', '?' => ['code' => $code]]);
            }

            // ログイン
            $this->loadModel('CustomerLogin');
            $this->CustomerLogin->data['CustomerLogin']['email'] = $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['email'];
            $this->CustomerLogin->data['CustomerLogin']['password'] = $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['password'];

            $res = $this->CustomerLogin->login();
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->render('customer_add_info');
            }

            // カスタマー情報を取得しセッションに保存
            $this->Customer->setTokenAndSave($res->results[0]);
            $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);
            $this->Customer->getInfo();

            // ご利用中サービスの集計
            $this->set('product_summary', []);
            if (!$this->Customer->isEntry()) {
                $summary = $this->InfoBox->getProductSummary(false);
                $this->set('product_summary', $summary);
                // 出庫済み含めた利用
                $summary_all = $this->InfoBox->getProductSummary(true, 'summary_all');
                $this->set('summary_all', $summary_all);
            }

            // 完了画面
            $this->set('alliance_cd', Hash::get($this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST], 'alliance_cd'));
            return $this->render('customer_complete');

        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'customer_add_info', '?' => ['code' => $code]]);
        }
    }
	/**
	* N (minikura sneaker)
	*/
    public function customer_add_sneakers()
    {
        // 紹介コード sneakersのLPから遷移するorエラー時にredirectで
        $code = Hash::get($this->request->query, 'code');
		//* 暫定 nike_snkrs用のcode
		if (empty($code)) {
			$code = Configure::read(self::SNEAKERS_ALLIANCE_CD);
		}
		//* Formにhiddenでset
        $this->request->data[self::MODEL_NAME]['alliance_cd'] = $code;
		//* loginはこちらリンク logo切り替え用
        $this->set('code', $code);

/* Sneakers 要件変更 キー制限解除 2017/03/01 by osada@terrada
        //* sneakers key  sneakersのLPから遷移するorエラー時にredirectで
        $key = Hash::get($this->request->query, 'key');
		//* 値を入力欄にset
        $this->set('key', $key);
*/

        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = [self::MODEL_NAME => CakeSession::read(self::MODEL_NAME)];
            $this->request->data[self::MODEL_NAME]['password'] = '';
            $this->request->data[self::MODEL_NAME]['password_confirm'] = '';
        }
        CakeSession::delete(self::MODEL_NAME);
    }

    /**
	 * N (minikura sneaker)
     */
    public function customer_confirm_sneakers()
    {
		//* alliance_cd (hidden) 
		$code = $this->request->data[self::MODEL_NAME]['alliance_cd'];
		if (empty($code)) {
			$code = Configure::read(self::SNEAKERS_ALLIANCE_CD);
			$this->request->data[self::MODEL_NAME]['alliance_cd'] = $code;
		}
        $this->set('code', $code);

/* Sneakers 要件変更 キー制限解除 2017/03/01 by osada@terrada
		//* key (form) 
		$key = $this->request->data[self::MODEL_NAME]['key'];
        $this->set('key', $key);

		//* keyの有効性check
		$exist_flg = $this->_checkSneakersKey($key);
		//* リストに無い=無効なkey error
        if ($exist_flg === false) {
            $this->Flash->set(__('empty_sneakers_key_data'));
            $this->request->data[self::MODEL_NAME]['password'] = '';
            $this->request->data[self::MODEL_NAME]['password_confirm'] = '';
			return $this->render('customer_add_sneakers');
        }
		//* keyが登録済みでないか確認 
		$registered_flg = $this->_checkRegisteredSneakersKey($key);
		//* key登録済みerror
        if ($registered_flg === true) {
            $this->Flash->set(__('empty_sneakers_key_data'));
            $this->request->data[self::MODEL_NAME]['password'] = '';
            $this->request->data[self::MODEL_NAME]['password_confirm'] = '';
			return $this->render('customer_add_sneakers');
        }
*/

        $this->loadModel(self::MODEL_NAME);
        $this->CustomerEntry->set($this->request->data);

        if ($this->CustomerEntry->validates()) {
            CakeSession::write(self::MODEL_NAME, $this->CustomerEntry->toArray());
        } else {
            return $this->render('customer_add_sneakers');
        }
    }

    /**
     *
     */
    public function customer_complete_sneakers()
    {
		//* Session read
        $data = CakeSession::read(self::MODEL_NAME);

		//* alliance_cd 
		$code = $data['alliance_cd'];
        $this->set('code', $code);

/* Sneakers 要件変更 キー制限解除 2017/03/01 by osada@terrada
		//* key
		$key = $data['key'];
        $this->set('key', $key);
*/

        CakeSession::delete(self::MODEL_NAME);
        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
			/* Sneakers 要件変更 キー制限解除 2017/03/01 by osada@terrada
            //return $this->redirect(['action' => 'customer_add_sneakers', '?' => ['code' => $code, 'key' => $key]]);
			*/
            return $this->redirect(['action' => 'customer_add_sneakers', '?' => ['code' => $code]]);
        }

/* Sneakers 要件変更 キー制限解除 2017/03/01 by osada@terrada
		//* keyの有効性check
		$exist_flg = $this->_checkSneakersKey($key);
		//* リストに無い=無効なkey error
        if ($exist_flg === false) {
            $this->Flash->set(__('empty_sneakers_key_data'));
            return $this->redirect(['action' => 'customer_add_sneakers', '?' => ['code' => $code, 'key' => $key]]);
        }
		//* keyが登録済みでないか確認 
		$registered_flg = $this->_checkRegisteredSneakersKey($key);
		//* key登録済みerror
        if ($registered_flg === true) {
            $this->Flash->set(__('empty_sneakers_key_data'));
            return $this->redirect(['action' => 'customer_add_sneakers', '?' => ['code' => $code, 'key' => $key]]);
        }
*/

        $this->loadModel(self::MODEL_NAME);
        $this->CustomerEntry->set($data);

        if ($this->CustomerEntry->validates()) {
            // 仮登録 *暫定 nike_snkrs用 entry_sneakers()
            $res = $this->CustomerEntry->entry_sneakers();
            if (!empty($res->error_message)) {
                $this->CustomerEntry->data[self::MODEL_NAME]['password'] = '';
                $this->CustomerEntry->data[self::MODEL_NAME]['password_confirm'] = '';
                $this->Flash->set($res->error_message);
				/* Sneakers 要件変更 キー制限解除 2017/03/01 by osada@terrada
                return $this->redirect(['action' => 'customer_add_sneakers', '?' => ['code' => $code, 'key' => $key]]);
				*/
                return $this->redirect(['action' => 'customer_add_sneakers', '?' => ['code' => $code]]);
            }

            // ログイン
            $this->loadModel('CustomerLogin');
            $this->CustomerLogin->data['CustomerLogin']['email'] = $this->CustomerEntry->data[self::MODEL_NAME]['email'];
            $this->CustomerLogin->data['CustomerLogin']['password'] = $this->CustomerEntry->data[self::MODEL_NAME]['password'];
			/**
			*  sneakers用のlogin確認
			*  login()で統一したい。oem_keyのdefaultはminikura用, 例外でentry[POST]のsneakers用の時がある。
			*  sneakersユーザーで通常のminikuraトップページにログインする場合にoem_keyがdefaultのminikuraになってしまう
			*  UserAPI側で、token取得時のoem_keyを拾うため=>sneakersの時はsneakersを返す事が必要になる
			*  UserAPIの変更が可能になれば、ここはloginで済む事になる。
			*　=>UserAPIを変更したので、minikuraのoem_keyでログインしてもsneakersを認識できるようになっている
			*/
            $res = $this->CustomerLogin->login();
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->render('customer_add_sneakers');
            }

            // カスタマー情報を取得しセッションに保存
            $this->Customer->setTokenAndSave($res->results[0]);
            $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);

            // ご利用中サービスの集計
            $this->set('product_summary', []);
            if (!$this->Customer->isEntry()) {
                $summary = $this->InfoBox->getProductSummary(false);
                $this->set('product_summary', $summary);
                // 出庫済み含めた利用
                $summary_all = $this->InfoBox->getProductSummary(true, 'summary_all');
                $this->set('summary_all', $summary_all);
            }

/* Sneakers 要件変更 キー制限解除 2017/03/01 by osada@terrada
			//* keyを登録済みとして管理する
			$input_data = $key . "," .$data['email'] . "\n";
			$this->_postRegisteredSneakersKey($input_data);
*/

            // 完了画面
            $this->set('alliance_cd', $this->CustomerEntry->data[self::MODEL_NAME]['alliance_cd']);
            return $this->render('customer_complete_sneakers');

        } else {
            $this->Flash->set(__('empty_session_data'));
/* Sneakers 要件変更 キー制限解除 2017/03/01 by osada@terrada
            return $this->redirect(['action' => 'customer_add_sneakers', '?' => ['code' => $code, 'key' => $key]]);
*/
            return $this->redirect(['action' => 'customer_add_sneakers', '?' => ['code' => $code]]);
        }
    }

	/**
	* sneakers ユーザーのaccess_key check
	*/
	/* Sneakers 要件変更 キー制限解除 2017/03/01 by osada@terrada
	private function _checkSneakersKey($_key)
	{
		//* flg	
		$exist_flg = false;
		//* sneakers key list
		$file = TMP . Configure::read(self::SNEAKERS_DIR) . DS . Configure::read(self::SNEAKERS_FILE_KEY_LIST);
		if (file_exists($file) && ! empty($_key)) {
			$handle = fopen($file, 'r');
			if ($handle) { 
				while ($line = fgets($handle)) {
					$line_array[] = $line;
					//* strpos() && 桁数check 
					if (strpos($line, $_key) !== false && mb_strlen($_key) === 8) {
						//* exist	
						$exist_flg = true;
						break;
					}
				}
			}    
		}
		//* key error  
		if ($exist_flg === false) {
			$this->_postErrorSneakersKey($_key, self::SNEAKERS_REASON_NOT_EXIST);
		}

		return $exist_flg;
	}
	*/

	/**
	* sneakers ユーザーのaccess_key 登録済み処理
	*/
	/* Sneakers 要件変更 キー制限解除 2017/03/01 by osada@terrada
	private function _postErrorSneakersKey($_input_data, $_reason)
	{
		$post_info = false;
		$data = [];
		$data['datetime']   = date('Y-m-d H:i:s');
		$data['email']      = ! empty($this->request->data[self::MODEL_NAME]['email']) ?  $this->request->data[self::MODEL_NAME]['email'] : null;
		$data['key']        = ! empty($_input_data) ?  $_input_data : null;
		$data['ip_address'] = ! empty($_SERVER['REMOTE_ADDR']) ?  $_SERVER['REMOTE_ADDR'] : null;
		$data['reason']     = ! empty($_reason) ? $_reason : null;

		//* sneakers error key list
		$file = TMP . Configure::read(self::SNEAKERS_DIR) . DS . Configure::read(self::SNEAKERS_FILE_ERROR_LIST) . date('Ym') . '.txt';
		$post_info = file_put_contents($file, json_encode($data) . "\n", FILE_APPEND | LOCK_EX);
		return $post_info;
	}
	*/


	/**
	* sneakers ユーザーのaccess_key 登録済み処理
	*/
	/* Sneakers 要件変更 キー制限解除 2017/03/01 by osada@terrada
	private function _postRegisteredSneakersKey($_input_data)
	{
		$post_info = false;
		//* sneakers registered key list
		$file = TMP . Configure::read(self::SNEAKERS_DIR) . DS . Configure::read(self::SNEAKERS_FILE_REGISTERED_LIST);
		$post_info = file_put_contents($file, $_input_data, FILE_APPEND | LOCK_EX);
		return $post_info;
	}
	*/

	/**
	* sneakers ユーザーのaccess_keyが登録済みかcheck
	*/
	/* Sneakers 要件変更 キー制限解除 2017/03/01 by osada@terrada
	private function _checkRegisteredSneakersKey($_key)
	{
		//* flg	
		$registered_flg = false;
		//* sneakers key list
		$file = TMP . Configure::read(self::SNEAKERS_DIR) . DS . Configure::read(self::SNEAKERS_FILE_REGISTERED_LIST);
		if (file_exists($file)) {
			$handle = fopen($file, 'r');
			if ($handle) { 
				while ($line = fgets($handle)) {
					$line_array[] = $line;
					//* strpos() & 桁数check 
					if (strpos($line, $_key) !== false && mb_strlen($_key) === 8) {
						//* exist	
						$registered_flg = true;

						//* key error
						if ($registered_flg === true) {
							$this->_postErrorSneakersKey($_key, self::SNEAKERS_REASON_REGISTERED);
						}
						break;
					}
				}
			}    
		}
		return $registered_flg;
	}
	*/
	
    /**
     * 法人カスタマー登録（いきなり本登録）
     */
    public function corporate_add_info()
    {
        // 紹介コード
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);
        $this->request->data[self::MODEL_NAME_CORP_REGIST]['alliance_cd'] = $code;

        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = [self::MODEL_NAME_CORP_REGIST => CakeSession::read(self::MODEL_NAME_CORP_REGIST)];
            $this->request->data[self::MODEL_NAME_CORP_REGIST]['password'] = '';
            $this->request->data[self::MODEL_NAME_CORP_REGIST]['password_confirm'] = '';
        }
        CakeSession::delete(self::MODEL_NAME_CORP_REGIST);
    }

    /**
     * 法人カスタマー登録確認（いきなり本登録）
     */
    public function corporate_confirm_info()
    {
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);

        $this->loadModel(self::MODEL_NAME_CORP_REGIST);
        $this->CorporateRegistInfo->set($this->request->data);
        if ($this->CorporateRegistInfo->validates()) {
            CakeSession::write(self::MODEL_NAME_CORP_REGIST, $this->CorporateRegistInfo->toArray());
        } else {
            return $this->render('corporate_add_info');
        }
    }

    /**
     * 法人カスタマー登録完了（いきなり本登録）
     */
    public function corporate_complete_info()
    {
        $code = Hash::get($this->request->query, 'code');
        $this->set('code', $code);

        $data = CakeSession::read(self::MODEL_NAME_CORP_REGIST);
        CakeSession::delete(self::MODEL_NAME_CORP_REGIST);
        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'corporate_add_info', '?' => ['code' => $code]]);
        }

        $this->loadModel(self::MODEL_NAME_CORP_REGIST);
        $this->CorporateRegistInfo->set($data);
        if ($this->CorporateRegistInfo->validates()) {
            // 本登録
            $res = $this->CorporateRegistInfo->regist();
            if (!empty($res->error_message)) {
                $this->CorporateRegistInfo->data[self::MODEL_NAME_CORP_REGIST]['password'] = '';
                $this->CorporateRegistInfo->data[self::MODEL_NAME_CORP_REGIST]['password_confirm'] = '';
                $this->Flash->set($res->error_message);
                return $this->render('corporate_add_info');
            }

            // ログイン
            $this->loadModel('CustomerLogin');
            $this->CustomerLogin->data['CustomerLogin']['email'] = $this->CorporateRegistInfo->data[self::MODEL_NAME_CORP_REGIST]['email'];
            $this->CustomerLogin->data['CustomerLogin']['password'] = $this->CorporateRegistInfo->data[self::MODEL_NAME_CORP_REGIST]['password'];

            $res = $this->CustomerLogin->login();
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->render('corporate_add_info');
            }

            // カスタマー情報を取得しセッションに保存
            $this->Customer->setTokenAndSave($res->results[0]);
            $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);
            $this->Customer->getInfo();

            // ご利用中サービスの集計
            $this->set('product_summary', []);
            if (!$this->Customer->isEntry()) {
                $summary = $this->InfoBox->getProductSummary(false);
                $this->set('product_summary', $summary);
                // 出庫済み含めた利用
                $summary_all = $this->InfoBox->getProductSummary(true, 'summary_all');
                $this->set('summary_all', $summary_all);
            }

            // 完了画面
            $this->set('alliance_cd', Hash::get($this->CorporateRegistInfo->data[self::MODEL_NAME_CORP_REGIST], 'alliance_cd'));
            return $this->render('corporate_complete');

        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'corporate_add_info', '?' => ['code' => $code]]);
        }
    }
}
