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
                
                if ( $this->request->data['remember'] ) {
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
                $this->_switchRedirctUrl();

            } else {
                $this->request->data['CustomerLogin']['password'] = '';
                return $this->render('index');
            }
        } else if ($this->Customer->isLogined()) {
            // ログイン済
            $this->_switchRedirctUrl();
        } else {
            // ログイン前のページ設定
            $this->_startJunction();

            // 遷移時にオプションが設定されている場合
            $option = filter_input(INPUT_GET,  Configure::read( 'app.switch_redirect.param' ));
            if(!is_null($option)){
                CakeLog::write(DEBUG_LOG, '_switchRedirctUrl set option ' . $option );
                CakeSession::write(Configure::read( 'app.switch_redirect.session_name'),$option);
            }

            // 未ログイン add_sneakersから遷移時 logo切り替え
			$code = Hash::get($this->request->query, 'code');
			$key = Hash::get($this->request->query, 'key');
			$this->set('code', $code);
			$this->set('key', $key);
		}
    }

    public function logout()
    {
        $this->loadModel('CustomerLogin');
        $this->CustomerLogin->logout();
        // セッション値をクリア
        ApiCachedModel::deleteAllCache();
        OutboundList::delete();
        CustomerData::delete();
        // todo: ここで（もしくは別の場所）クッキーを削除したい

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
          setcookie('token', $hash, $expired, '.' . $_SERVER['HTTP_HOST']);
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
            setcookie('token', '', time() - 1800);
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
            $this->_switchRedirctUrl();            
        } else {
            // バリデーションに引っかかる場合、処理終了
            return false;
        }

        return true;
    }

    protected function _switchRedirctUrl()
    {
        $default_redirect_param = array('controller' => 'order', 'action' => 'add');

        // 1 Sneaker
        if ($this->Customer->isSneaker()) {
            // Sneakerでエントリユーザかどうか
            $summary = $this->InfoBox->getProductSummary(false);

            // スニーカが収納されている場合
            if (!empty($summary)) {
                CakeLog::write(DEBUG_LOG, '_switchRedirctUrl isSneaker on item ');
                return $this->redirect(['controller' => 'item', 'action' => 'index']);
            }

            // ボックスを持っている場合
            $no_inbound_box = $this->InfoBox->getListForInbound();
            if (!empty($no_inbound_box)) {
                CakeLog::write(DEBUG_LOG, '_switchRedirctUrl isSneaker no_inbound_box');
                return $this->redirect(['controller' => 'inbound', 'action' => 'box/add']);
            }

            // アイテムなし、ボックス未購入
            CakeLog::write(DEBUG_LOG, '_switchRedirctUrl isSneaker order ');
            return $this->redirect(['controller' => 'order', 'action' => 'add']);

        }

        // エントリーユーザ
        if (!$this->Customer->isEntry()) {

            // ボックスの状態を取得
            $summary = $this->InfoBox->getProductSummary(false);
            CakeLog::write(DEBUG_LOG, '_switchRedirctUrl summary ' . print_r($summary, true) );

            // 2,3 各オプションから遷移
            $option = CakeSession::read(Configure::read( 'app.switch_redirect.session_name'));
            CakeSession::delete(Configure::read( 'app.switch_redirect.session_name'));
            CakeLog::write(DEBUG_LOG, '_switchRedirctUrl option ' . $option);
            if (!is_null($option)) {

                // 預けていない場合$summaryは空
                if (empty($summary)) {
                    CakeLog::write(DEBUG_LOG, '_switchRedirctUrl empty(summary) ');

                    // 2 各オプションから遷移 入庫なし
                    switch ($option) {
                        case 'cleaning':
                        case 'trade':
                        case 'mono_view':
                        case 'travel':
                            CakeLog::write(DEBUG_LOG, '_switchRedirctUrl option.cleaning ');
                            return $this->redirect(['controller' => 'order', 'action' => 'add']);
                            //?product=cleaning
                            break;
                        default:
                            break;
                    }
                }

                // 3 MONOを預けている オプション遷移
                CakeLog::write(DEBUG_LOG, '_switchRedirctUrl on summary ');
                if (array_key_exists(PRODUCT_CD_MONO, $summary)) {
                    switch ($option) {
                        case 'trade':
                            CakeLog::write(DEBUG_LOG, '_switchRedirctUrl on mono option.trade ');
                            return $this->redirect(['controller' => 'sale', 'action' => 'index']);
                            break;
                        case 'mono_view':
                            CakeLog::write(DEBUG_LOG, '_switchRedirctUrl on mono option.mono_view ');
                            return $this->redirect(['controller' => 'mini_action']);
                            break;
                        case 'travel':
                            CakeLog::write(DEBUG_LOG, '_switchRedirctUrl on mono option.travel ');
                            return $this->redirect(['controller' => 'travel', 'action' => 'mono']);
                            break;
                        // クリーニングは後日実装
                        case 'cleaning':
                        default:
                            CakeLog::write(DEBUG_LOG, '_switchRedirctUrl none switch ');
                            break;
                    }
                }
            }

            // 4 入庫中アイテムあり
            if (array_key_exists(PRODUCT_CD_MONO, $summary)) {
                //CakeLog::write(DEBUG_LOG, '_switchRedirctUrl on mono');
                return $this->redirect(['controller' => 'item', 'action' => 'index']);
            }

            // 5 入庫中ボックスあり
            if (!empty($summary)) {
                CakeLog::write(DEBUG_LOG, '_switchRedirctUrl on box');
                return $this->redirect(['controller' => 'box', 'action' => 'index']);
            }

            // 6 未入庫ボックスあり
            $no_inbound_box = $this->InfoBox->getListForInbound();
            if (!empty($no_inbound_box)) {
                CakeLog::write(DEBUG_LOG, '_switchRedirctUrl no_inbound_box');
                return $this->redirect(['controller' => 'inbound', 'action' => 'box/add']);
            }
        }

        CakeLog::write(DEBUG_LOG, '_switchRedirctUrl Non-aggressive user');
        return $this->redirect($default_redirect_param);
    }
}
