<?php

App::uses('MinikuraController', 'Controller');
App::uses('ApiCachedModel', 'Model');
App::uses('OutboundList', 'Model');
App::uses('CustomerEnvAuthed', 'Model');
App::uses('AppCode', 'Lib');

class LoginController extends MinikuraController
{
    // ログイン不要なページ
    protected $checkLogined = false;

    /**
     * ルートインデックス.
     */
    public function index()
    {
        $this->_checkLoginCookie();

        if ($this->request->is('post')) {
            $this->loadModel('CustomerLogin');
            $this->CustomerLogin->set($this->request->data);

            if ($this->CustomerLogin->validates()) {

                $res = $this->CustomerLogin->login();
                if (!empty($res->error_message)) {
                    // パスワード不正など
                    $this->request->data['CustomerLogin']['password'] = '';
                    $this->Flash->set($res->error_message);
                    return $this->render('index');
                }

                if (!empty($this->request->data['remember'])) {
                    $cookie_enable = true;
                } else {
                    $cookie_enable = false;
                }

                // ログイン処理
                $this->_execLogin($res, $cookie_enable);

                // ユーザー環境値登録
                $this->Customer->postEnvAuthed();

                // ログイン前のページに戻る
                $this->_endJunction();

                // ユーザの状態によってログイン先を変更
                $this->_switchRedirct();

            } else {
                $this->request->data['CustomerLogin']['password'] = '';
                return $this->render('index');
            }
        } else if ($this->Customer->isLogined()) {
            // リファラ確認スイッチフラグを立てて、リファラー遷移後の再リファラ処理を防ぐ
            CakeSession::write('referer_switch_redirct_flg', true);

            // ログイン済
            $this->_switchRedirct();
        } else {
            // ログイン前のページ設定
            $this->_startJunction();

            // 未ログイン add_sneakersから遷移時 logo切り替え
			$code = Hash::get($this->request->query, 'code');
			$key = Hash::get($this->request->query, 'key');
			$this->set('code', $code);
			$this->set('key', $key);
		}
    }

    public function index_amazon_profile()
    {
        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . '_through');

/*
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['Login/index'], true) === false) {
            CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . '_referer_check');
            //* NG redirect
            $this->redirect(['controller' => 'login', 'action' => 'index']);
        }
*/
        // アクセストークンを取得
        $access_token = filter_input(INPUT_GET, 'access_token');
        if($access_token === null) {
            CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . '_access_token_null');
            $this->Flash->validation('Amazonアカウントでお支払い ログインエラー', ['key' => 'amazon_pay_access_token']);
            $this->redirect(['controller' => 'login', 'action' => 'index']);
        }

        CakeSession::write('Login.amazon_pay.access_token', $access_token);

        $this->loadModel('AmazonPayModel');
        $res = $this->AmazonPayModel->getUserInfo($access_token);

        // 情報が取得できているか確認
        if(!isset($res['name']) || !isset($res['user_id']) || !isset($res['email'])) {
            CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . '_data_null');
            $this->Flash->validation('Amazonアカウントでお支払い アカウント情報エラー', ['key' => 'amazon_pay_access_token']);
            $this->redirect(['controller' => 'login', 'action' => 'index']);
        }

        if(($res['name'] === '') || ($res['user_id'] === '') || ($res['email'] === '')) {
            CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . '_data_blank');
            $this->Flash->validation('Amazonアカウントでお支払い アカウント情報エラー', ['key' => 'amazon_pay_access_token']);
            $this->redirect(['controller' => 'login', 'action' => 'index']);
        }

        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));

        CakeSession::write('login.amazon_pay.user_info', $res);

/*
        // email確認
        // アマゾンペイメントメールエラー処理
        CakeSession::delete('registered_user_login_url');

        if ($is_validation_error !== true) {
            // amazonユーザ情報取得
            $amazon_pay_user_info = CakeSession::read('FirstOrder.amazon_pay.user_info');

            // 既存ユーザか確認する
            $this->loadModel('Email');
            $result = $this->Email->getEmail(array('email' => $amazon_pay_user_info['email']));

            if ($result->status === "0") {
                // エラーか既存アドレスか判定
                if ($result->http_code !== "400") {
                    $this->Flash->validation('登録済メールアドレス', ['key' => 'check_email']);
                    $registered_user_login_url = '/login?c=first_order&a=index&p=' . Configure::read('app.lp_option.param') . '=' . CakeSession::read('order_option');
                    if (!is_null(CakeSession::read('order_code'))) {
                        $registered_user_login_url = '/login?c=first_order&a=index&p=' . Configure::read('app.lp_code.param') . '=' . CakeSession::read('order_code')
                            . '?' . Configure::read('app.lp_option.param') . '=' . CakeSession::read('order_option');
                    }

                    CakeSession::write('registered_user_login_url', $registered_user_login_url);

                    // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' registered_user_login_url ' . print_r($registered_user_login_url, true));

                }
                $is_validation_error = true;
            }
        }

        if ($is_validation_error === true) {
            $this->redirect(['controller' => 'first_order', 'action' => 'add_order']);
            return;
        }
*/
        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . '_finish');

        //$this->redirect($set_url);
        $this->redirect(['controller' => 'login', 'action' => 'index']);

    }

    public function logout()
    {
        $this->loadModel('CustomerLogin');
        $this->CustomerLogin->logout();
        // セッション値をクリア
        ApiCachedModel::deleteAllCache();
        OutboundList::delete();
        CustomerData::delete();

        // クッキー削除
        setcookie('token', '', time() - 1800, '/', '.' . $_SERVER['HTTP_HOST']);

        return $this->redirect(['controller' => 'login', 'action' => 'index', '?' => array('logout' => 'true')]);
    }

    /**
     * ログイン時の共通処理
     */
    private function _execLogin($_res, $_usecookie = false)
    {
        // セッション値をクリア
        ApiCachedModel::deleteAllCache();
        OutboundList::delete();
        CustomerData::delete();
        // セッションID変更
        CakeSession::renew();

        // カスタマー情報を取得しセッションに保存
        $this->Customer->setTokenAndSave($_res->results[0]);
        $this->Customer->setPassword($this->request->data['CustomerLogin']['password']);
        $this->Customer->getInfo();

        // ログイン情報を暗号化してクッキーへ保存
        if ( $_usecookie !== false ) {
          $cookie_login_data = $this->request->data['CustomerLogin']['email'] . ' ' . $this->request->data['CustomerLogin']['password'];
          $hash = AppCode::encodeLoginData($cookie_login_data);
          $cookie_period = Configure::read( 'app.login_cookie.cookie_period' );

          // 有効時間 (60秒 * 60分 * 24時 * 設定)
          //   設定：AppConfig.php->app.login_cookie.cookie_period
          $expired = time() + $cookie_period;
          setcookie('token', $hash, $expired, '/', '.' . $_SERVER['HTTP_HOST']);
        }
    }

    /**
     * クッキー上のログイン情報のチェック
     */
    private function _checkLoginCookie()
    {
        // tokenが空の場合、処理終了
        if (empty($_COOKIE['token'])) {
            return false;
        }
        
        $cookie_login_param = AppCode::decodeLoginData($_COOKIE['token']);
        $login_params = explode(' ', $cookie_login_param);

        // 取得した配列のカウントが2ではない場合、処理終了
        if (count($login_params) !== 2) {
            return false;
        }

        $logout = filter_input(INPUT_GET,  Configure::read( 'app.login_cookie.param' ));
        if($logout){
            setcookie('token', '', time() - 1800, '/', '.' . $_SERVER['HTTP_HOST']);
            return false;
        }

        // ログインパラメータセット
        $this->request->data['CustomerLogin']['email'] = $login_params[0];
        $this->request->data['CustomerLogin']['password'] = $login_params[1];

        // ログイン処理
        $this->loadModel('CustomerLogin');
        $this->CustomerLogin->set($this->request->data);

        if ($this->CustomerLogin->validates()) {
            $res = $this->CustomerLogin->login();
            if (!empty($res->error_message)) {
                // パスワード不正などの場合、処理終了
                return false;
            }
            // ログイン処理
            $this->_execLogin($res, true);

            // ユーザー環境値登録
            $this->Customer->postEnvAuthed();

            // ログイン前のページに戻る
            $this->_endJunction();

            // ユーザの状態によってログイン先を変更
            $this->_switchRedirct();
        } else {
            // バリデーションに引っかかる場合、処理終了
            return false;
        }

        return true;
    }
}
