<?php

App::uses('MinikuraController', 'Controller');
App::uses('ApiCachedModel', 'Model');
App::uses('OutboundList', 'Model');
App::uses('CustomerEnvAuthed', 'Model');
App::uses('AppCode', 'Lib');

/**
 * @property CustomerLoginAmazonPay $CustomerLoginAmazonPay
 */
class LoginController extends MinikuraController
{
    // ログイン不要なページ
    protected $checkLogined = false;

    /**
     * ルートインデックス.
     */
    public function index()
    {
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

    /**
     * amazon pay ログイン
     */
    public function login_by_amazon_pay()
    {
        // アクセストークンを取得
        $access_token = filter_input(INPUT_GET, 'access_token');
        if($access_token === null) {
            CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . '_access_token_null');
            $this->Flash->validation('Amazonアカウントでお支払い ログインエラー', ['key' => 'amazon_pay_access_token']);
            $this->redirect(['controller' => 'login', 'action' => 'index']);
        }

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

        $amazon_user_id = $res['user_id'];
        CakeSession::write('login.amazon_pay.user_info', $res);

        $this->loadModel('CustomerLoginAmazonPay');
        $this->CustomerLoginAmazonPay->set([
            'amazon_user_id' => $res['user_id'],
            'access_token' => $access_token,
        ]);

        if ($this->CustomerLoginAmazonPay->validates()) {

            $res = $this->CustomerLoginAmazonPay->login();
            if (!empty($res->error_message)) {
                // パスワード不正など
                $this->request->data['CustomerLogin']['password'] = '';
                $this->Flash->validation('Amazonアカウントで会員登録された方のみご利用可能です。', ['key' => 'amazon_pay_access_token']);
                return $this->render('index');
            }

            // BAIDを保持
            CakeSession::write('login.amazon_pay.baid', $res->results[0]['amazon_billing_agreement_id']);

            // access_tokenを保持
            CakeSession::write('login.amazon_pay.access_token', $access_token);

            // amazon_user_idを保持
            CakeSession::write('login.amazon_pay.amazon_user_id', $amazon_user_id);

            // billing_statusを保持
            CakeSession::write('login.amazon_pay.billing_status', $res->results[0]['amazon_billing_status']);

            // ログイン処理
            $this->request->data['CustomerLogin']['password'] = '';
            $cookie_enable = false;
            $this->_execLogin($res, $cookie_enable);

            // ユーザー環境値登録
            $this->Customer->postEnvAuthed();

            // ログイン前のページに戻る
            $this->_endJunction();

        } else {
            $this->Flash->validation('Amazonアカウントでお支払い ログインエラー', ['key' => 'amazon_pay_access_token']);
            $this->redirect(['controller' => 'login', 'action' => 'index']);
        }

        //$this->redirect($set_url);
        $this->redirect(['controller' => 'login', 'action' => 'index']);

    }

    /**
     * facebook ログイン
     */
    public function login_by_facebook()
    {
        $this->loadModel('CustomerLoginFacebook');
        $this->CustomerLoginFacebook->set($this->request->data);

        if ($this->CustomerLoginFacebook->validates()) {

            $res = $this->CustomerLoginFacebook->login();
            if (!empty($res->error_message)) {
                // パスワード不正など
                $this->request->data['CustomerLogin']['password'] = '';
                $this->Flash->validation('Facebookアカウントで会員登録された方のみご利用可能です。', ['key' => 'facebook_access_token']);
                return $this->render('index');
            }

            CakeSession::write(CustomerLogin::SESSION_FACEBOOK_ACCESS_KEY, $this->request->data['CustomerLoginFacebook']['access_token']);

            // ログイン処理
            $this->request->data['CustomerLogin']['password'] = '';
            $cookie_enable = false;
            $this->_execLogin($res, $cookie_enable);

            // ユーザー環境値登録
            $this->Customer->postEnvAuthed();

            // ログイン前のページに戻る
            $this->_endJunction();

        } else {
            $this->Flash->validation('Facebookアカウント ログインエラー', ['key' => 'facebook_access_token']);
            $this->redirect(['controller' => 'login', 'action' => 'index']);
        }

        $this->redirect(['controller' => 'login', 'action' => 'index']);

    }

     /**
     * google ログイン
     */
    public function login_by_google()
    {
        // アクセストークンを取得
        $request_array = $this->request->data;

        $this->loadModel('GoogleModel');
        $this->request->data = $this->GoogleModel->getUserInfo_login($request_array);

        $this->loadModel('CustomerLoginGoogle');
        $this->CustomerLoginGoogle->set($this->request->data);

        if ($this->CustomerLoginGoogle->validates()) {

            $res = $this->CustomerLoginGoogle->login();

            if (!empty($res->error_message)) {
                // パスワード不正など
                $this->request->data['CustomerLogin']['password'] = '';
                $this->Flash->validation('googleアカウントで会員登録された方のみご利用可能です。', ['key' => 'google_access_token']);
                return $this->render('index');
            }

            CakeSession::write(CustomerLogin::SESSION_GOOGLE_ACCESS_KEY, $this->request->data['CustomerLoginGoogle']['access_token']);

            // ログイン処理
            $this->request->data['CustomerLogin']['password'] = '';
            $cookie_enable = false;
            $this->_execLogin($res, $cookie_enable);

            // ユーザー環境値登録
            $this->Customer->postEnvAuthed();

            // ログイン前のページに戻る
            $this->_endJunction();

        } else {
            $this->Flash->validation('googleアカウント ログインエラー', ['key' => 'google_access_token']);
            $this->redirect(['controller' => 'login', 'action' => 'index']);
        }

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

        // 全セッション削除
        CakeSession::delete('app');

        // クッキー削除
        setcookie('token', '', time() - 1800, '/', '.' . $_SERVER['HTTP_HOST']);

        return $this->redirect(['controller' => 'login', 'action' => 'index', '?' => array('logout' => 'true')]);
    }

    /**
     * ログイン時の共通処理
     */
    private function _execLogin($_res)
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
        $usr_info = $this->Customer->getInfo();

        // AmazonPayユーザ確認
        if (!$this->Customer->isAmazonPay()) {

            // AmazonPayログインしていないが、支払いがamazonpay の場合
            if (isset($usr_info['account_situation'])) {
                if ($usr_info['account_situation'] === ACCOUNT_SITUATION_AMAZON_PAY) {
                    $this->Flash->set('Amazon Loginボタンよりログインしてください。 Amazon アカウント利用を停止する場合はお問い合わせください');
                    $this->redirect(['controller' => 'login', 'action' => 'logout']);
                }
            }
        }
    }
}
