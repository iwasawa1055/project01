<?php
App::uses('AppValid', 'Lib');
App::uses('MinikuraController', 'Controller');
App::uses('KitDeliveryDatetime', 'Model');
App::uses('EmailModel', 'Model');
App::uses('CustomerKitPrice', 'Model');


class FirstOrderController extends MinikuraController
{
    // アクセス許可
    protected $checkLogined = false;

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        //* mypageとは違うlayoutにする
        $this->layout = 'element_set';

    }

    /**
     * 静的ページからの遷移 セットアクション
     */
    public function index()
    {

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        // 遷移時にオプションが設定されている場合
        $option = filter_input(INPUT_GET, Configure::read('app.lp_option.param'));
        if (!is_null($option)) {
            CakeLog::write(DEBUG_LOG, 'FirstOrder set option ' . $option);
            CakeSession::write('lp_option', $option);
        }

        // 初回購入フローに入らない場合の遷移先
        $none_first_redirect_param = array(
            'controller' => 'login',
            'action' => 'index',
            '?' => array(Configure::read('app.switch_redirect.param'), $option));

        // オートログイン確認
        // tokenが存在する
        if (!empty($_COOKIE['token'])) {
            $cookie_login_param = AppCode::decodeLoginData($_COOKIE['token']);
            $login_params = explode(' ', $cookie_login_param);

            // 取得した配列のカウントが2である
            if (count($login_params) === 2) {
                CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' is auto login user' . $option);
                // オートログイン
                $this->redirect($none_first_redirect_param);
            }
        }

        // set action ログインしている
        if ($this->Customer->isLogined()) {

            // エントリーユーザでない
            if (!$this->Customer->isEntry()) {
                CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' is none entry user' . $option);
                $this->redirect($none_first_redirect_param);
            }

            // スニーカーユーザでない
            if ($this->Customer->isSneaker()) {
                CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' is isSneaker' . $option);
                $this->redirect($none_first_redirect_param);
            }

            // ログイン済みエントリーユーザ
            CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' is login entry user' . $option);
            $this->redirect(['controller' => 'first_order', 'action' => 'add_order']);
        }

        // スターターキット購入フロー
        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' starter user ' . $option);
        $this->redirect(['controller' => 'first_order', 'action' => 'add_order']);
    }

    /**
     * Boxの選択 静的ページからのオプション、ユーザ条件によって表示内容を変更
     */
    public function add_order()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/index', 'FirstOrder/add_order', 'FirstOrder/confirm_order', 'FirstOrder/add_address'], true) === false) {
            CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' NG redirect ' . CakeSession::read('app.data.session_referer'));
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // ログインしているか
        $is_logined = false;
        if ($this->Customer->isLogined()) {
            CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' is login '); //
            $is_logined = true;
        }
        $this->set('is_logined', $is_logined);

        $lp_option = CakeSession::read('lp_option');
        $kit_select_type = 'all';
        switch (true) {
            case $lp_option === 'mono':
                // ログインしている場合はmonoを表示
                if($is_logined) {
                    $kit_select_type = 'mono';
                } else {
                    $kit_select_type = 'starter_kit';
                }
                break;
            case $lp_option === 'hako':
                if($is_logined) {
                    $kit_select_type = 'hako';
                }
                break;
            case $lp_option === 'cleaning':
                if($is_logined) {
                    $kit_select_type = 'cleaning';
                }
                break;
            case $lp_option === 'is_code':
            default:
                if($is_logined) {
                    $kit_select_type = 'all';
                } else {
                    $kit_select_type = 'starter_kit';
                }
                break;
        }

        //* Session write
        CakeSession::write('kit_select_type', $kit_select_type );
        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' kit_select_type ' . $kit_select_type);

        // boxの選択のタイプによって処理を変更する。
        $back  = filter_input(INPUT_GET, 'back');
        if (!$back) {

            // Orderセッションデータリセット
            //CakeSession::delete('Order');

            $Order = array( 'mono' => array('mono' => 0, 'mono_apparel' => 0, 'mono_book' => 0),
                            'hako' => array('hako' => 0, 'hako_apparel' => 0, 'hako_book' => 0),
                            'cleaning' => array('cleaning' => 0),
                            'starter' => array('starter' => 0));
            $OrderTotal = array('mono_num' => 0,
                                'hako_num' => 0);
            CakeSession::write('Order', $Order);
            CakeSession::write('OrderTotal', $OrderTotal);
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

    }

    /**
     * Boxの選択 確認
     */
    public function confirm_order()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/add_order', 'FirstOrder/add_address'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        $kit_select_type = CakeSession::read('kit_select_type');

        // order情報取得
        $Order = CakeSession::read('Order');
        $OrderTotal = CakeSession::read('OrderTotal');

        //* post parameter
        // 購入情報によって分岐
        $params = array();
        switch (true) {
            case $kit_select_type === 'all':
                $Order = $this->_set_mono_order($Order);
                $OrderTotal['mono_num'] = array_sum($Order['mono']);
                $Order = $this->_set_hako_order($Order);
                $OrderTotal['hako_num'] = array_sum($Order['hako']);
                $Order = $this->_set_cleaning_order($Order);
                CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' set Order ' . print_r($Order, true) );

                // 箱選択されているか
                if (array_sum($Order) === 0) {
                    $params = array(
                        'select_oreder_mono' => $OrderTotal['mono_num'],
                        'select_oreder_hako' => $OrderTotal['hako_num'],
                        'select_oreder_cleaning' => $Order['cleaning']['cleaning']
                    );
                }                break;
            case $kit_select_type === 'mono':
                $Order = $this->_set_mono_order($Order);
                $OrderTotal['mono_num'] = array_sum($Order['mono']);
                $params = array('select_oreder_mono' => $Order['mono_total_num']);
                break;
            case $kit_select_type === 'hako':
                $Order = $this->_set_hako_order($Order);
                $OrderTotal['hako_num'] = array_sum($Order['hako']);
                $params = array('select_oreder_hako' => $Order['hako_total_num']);
                break;
            case $kit_select_type === 'cleaning':
                $Order = $this->_set_cleaning_order($Order);
                $params = array('select_oreder_cleaning' => $Order['cleaning']['cleaning']);
                break;
            case $kit_select_type === 'starter_kit':
                $Order = $this->_set_starter_order($Order);
                $params = array('select_starter_kit' => $Order['starter']['starter']);
                break;
            default:
                break;
        }

        //* Session write
        CakeSession::write('Order', $Order);
        CakeSession::write('OrderTotal', $OrderTotal);

        //*  validation 基本は共通クラスのAppValidで行う
        $is_validation_error = false;
        $validation = AppValid::validate($params);

        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->render('add_order');
            return;
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->redirect(['controller' => 'first_order', 'action' => 'add_address']);

    }

    /**
     * ユーザ名 住所の登録
     */
    public function add_address()
    {
        //* session referer check
        /*
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/confirm_order', 'FirstOrder/add_credit'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }
        */

        $back  = filter_input(INPUT_GET, 'back');
        
        if ($back) {
            // お届け希望日のリスト
            $select_delivery_list =  json_decode(CakeSession::read('Address.select_delivery'));
            CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' select_delivery_list ' . print_r($select_delivery_list, true));
            if(is_null($select_delivery_list)) {
                $select_delivery_list = "";
            }
            $this->set('select_delivery_list', $select_delivery_list);
        } else {
            // Addressリセット
            //CakeSession::delete('Address');

            $Address = array(
                'firstname'      => "",
                'firstname_kana' => "",
                'lastname'       => "",
                'lastname_kana'  => "",
                'tel1'           => "",
                'postal'         => "",
                'pref'           => "",
                'address1'       => "",
                'address2'       => "",
                'address3'       => "",
                'select_delivery' => "",
            );

            // お届け希望日のリスト
            CakeSession::write('select_delivery_list', "");
            CakeSession::write('Address', $Address);
            $this->set('select_delivery_list', "");
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
    }

    /**
     * ユーザ名 住所 確認
     */
    public function confirm_address()
    {
        //* session referer check
        /*
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/add_address', 'FirstOrder/add_credit'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }
        */
        
        $params = [
            'firstname'         => filter_input(INPUT_POST, 'firstname'),
            'firstname_kana'    => filter_input(INPUT_POST, 'firstname_kana'),
            'lastname'          => filter_input(INPUT_POST, 'lastname'),
            'lastname_kana'     => filter_input(INPUT_POST, 'lastname_kana'),
            'tel1'              => filter_input(INPUT_POST, 'tel1'),
            'postal'            => filter_input(INPUT_POST, 'postal'),
            'pref'              => filter_input(INPUT_POST, 'pref'),
            'address1'          => filter_input(INPUT_POST, 'address1'),
            'address2'          => filter_input(INPUT_POST, 'address2'),
            'address3'          => filter_input(INPUT_POST, 'address3'),
            'datetime_cd'       => filter_input(INPUT_POST, 'datetime_cd'),
            'select_delivery'   => filter_input(INPUT_POST, 'select_delivery'),
        ];

        //* Session write
        CakeSession::write('Address', $params);

        //*  validation 基本は共通クラスのAppValidで行う
        $validation = AppValid::validate($params);

        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $select_delivery_list =  json_decode($params['select_delivery']);
            CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' select_delivery_list ' . print_r($select_delivery_list, true));
            if(is_null($select_delivery_list)) {
                $select_delivery_list = "";
            }
            $this->set('select_delivery_list', $select_delivery_list);
            $this->render('add_address');
            return;
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
        
        $this->redirect(['controller' => 'first_order', 'action' => 'add_credit']);
    }

    /**
     * クレジットカード 登録
     */
    public function add_credit()
    {
        //* session referer check
        /*
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/confirm_address', 'FirstOrder/add_email'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }
        */

        $back  = filter_input(INPUT_GET, 'back');
        
        if ($back) {

        } else {
            // Creditセッションデータリセット
//            CakeSession::delete('Credit');

            $Credit = array(
                'card_no'      => "",
                'security_cd'  => "",
                'expire'       => "",
                'holder_name'  => "",
            );

            CakeSession::write('Credit', $Credit);
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
    }

    /**
     * クレジットカード 確認
     */
    public function confirm_credit()
    {
        //* session referer check
        /*
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/add_credit', 'FirstOrder/add_email'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }
        */
        
        $this->loadModel("PaymentGMOSecurityCard");
        $this->loadModel('PaymentGMOCard');

        $params = [
            'card_no'       => filter_input(INPUT_POST, 'card_no'),
            'security_cd'   => filter_input(INPUT_POST, 'security_cd'),
            'expire_month'  => filter_input(INPUT_POST, 'expire_month'),
            'expire_year'   => filter_input(INPUT_POST, 'expire_year'),
            'expire'        => filter_input(INPUT_POST, 'expire_month').filter_input(INPUT_POST, 'expire_year'),
            'holder_name'   => filter_input(INPUT_POST, 'holder_name'),
        ];

        //* Session write
        CakeSession::write('Credit', $params);

        // ハイフン削除はバリデーション前に実施
        $params['card_no'] = str_replace("-", "", $params['card_no']);

        //*  validation 基本は共通クラスのAppValidで行う
        $is_validation_error = false;
        $validation = AppValid::validate($params);

        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $is_validation_error = true;
        }
        
        //* クレジットカードのチェック 未ログイン時にチェックできる v4/gmo_payment/card_check apiを使用する
/*        $requestdata['PaymentGMOSecurityCard'] = $params;

        $this->PaymentGMOSecurityCard->set($requestdata);
        // Expire
        $this->PaymentGMOSecurityCard->setExpire($requestdata);

        // ハイフン削除
        $this->PaymentGMOSecurityCard->trimHyphenCardNo($requestdata);

        // card_seq 除外
        $this->PaymentGMOSecurityCard->validator()->remove('card_seq');
            
        // update
        $res = $this->PaymentGMOSecurityCard->apiPut($this->PaymentGMOSecurityCard->toArray());
        
        if (!empty($res->error_message)) {
            $this->Flash->validation($res->error_message, ['key' => 'card_no']);
            $is_validation_error = true;
        }
*/
        if ($is_validation_error === true) {
            $this->render('add_credit');
            return;
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->redirect(['controller' => 'first_order', 'action' => 'add_email']);
    }

    /**
     * メールアドレス 登録
     */
    public function add_email()
    {
        //* session referer check
        /*
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/confirm_credit', 'FirstOrder/confirm'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }
        */
        
        $loginconfigure = Configure::read('app.register');

        // 入力カード情報セット
        $this->set('login_config', $loginconfigure);

        $back  = filter_input(INPUT_GET, 'back');
        
        if ($back) {

        } else {
            // Emailセッションデータリセット
            // CakeSession::delete('Email');

            $Email = array(
                'email'             => "",
                'password'          => "",
                'password_confirm'  => "",
                'birth_year'        => "",
                'birth_month'       => "",
                'birth_day'         => "",
                'gender'            => "",
                'newsletter'        => "",
                'alliance_cd'       => "",
                'remember'          => "",
            );
            
            CakeSession::write('Email', $Email);
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
    }

    /**
     * メールアドレス 確認
     */
    public function confirm_email()
    {
        //* session referer check
        /*
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/add_email', 'FirstOrder/confirm'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }
        */
        
        $password = filter_input(INPUT_POST, 'password');
        $password_confirm = filter_input(INPUT_POST, 'password_confirm');
        
        $params = [
            'email'            => filter_input(INPUT_POST, 'email'),
            'password'         => $password,
            'password_confirm' => $password_confirm,
            'birth'            => sprintf("%04d-%02d-%02d",filter_input(INPUT_POST, 'birth_year'),filter_input(INPUT_POST, 'birth_month'),filter_input(INPUT_POST, 'birth_day')),
            'birth_year'       => filter_input(INPUT_POST, 'birth_year'),
            'birth_month'      => filter_input(INPUT_POST, 'birth_month'),
            'birth_day'        => filter_input(INPUT_POST, 'birth_day'),
            'gender'           => filter_input(INPUT_POST, 'gender'),
            'newsletter'       => filter_input(INPUT_POST, 'newsletter'),
            'alliance_cd'      => filter_input(INPUT_POST, 'alliance_cd'),
            'remember'         => filter_input(INPUT_POST, 'remember'),
        ];

        //* Session write
        CakeSession::write('Email', $params);

        //*  validation 基本は共通クラスのAppValidで行う
        $is_validation_error = false;
        $validation = AppValid::validate($params);

        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $is_validation_error = true;
        }

        // 確認用パスワード一致チェック
        if ($password !== $password_confirm) {
            $this->Flash->validation('パスワードが一致していません。ご確認ください。', ['key' => 'password_confirm']);
            $is_validation_error = true;
        }
        
        // 規約同意を確認する
        $validation = AppValid::validateTermsAgree($params['remember']);

        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation) ) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $is_validation_error = true;
        }

        // 既存ユーザか確認する
        $this->loadModel('Email');
        $result = $this->Email->getEmail(array('email' => $params['email']));

        CakeSession::write('registered', false);
        if ($result->status === "0") {
            CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . 'emial error_message ' . print_r($result->error_message, true));
            $this->Flash->validation('登録済メールアドレス', ['key' => 'check_email']);
            CakeSession::write('registered', true);
            $is_validation_error = true;
        }
        
        if ($is_validation_error === true) {
            $loginconfigure = Configure::read('app.register');
      
            // 入力カード情報セット
            $this->set('login_config', $loginconfigure);
            $this->render('add_email');
            return;
        }
        
        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
        
        $this->redirect(['controller' => 'first_order', 'action' => 'confirm']);
    }

    /**
     * オーダー 会員登録
     */
    public function confirm()
    {
        //* session referer check
        /*
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/confirm_email'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }
        */

        // オーダー種類を集計
        // order情報
        $Order = CakeSession::read('Order');

        $DisplyOrder = array();
        // mono 集計
        foreach ($Order as $key => $value) {
/*            if() {

            }
 */       }

        //
        $kitPrice = new CustomerKitPrice();
        $r = $kitPrice->apiGet([
            'kit' => implode(',', array())
        ]);
        if ($r->isSuccess()) {

            $price = $r->results[0]['total_price'] * 1;
            $product['subtotal']['price'] = $price;
            $total['price'] += $price;
        }

        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
    }

    /**
     * オーダー 完了
     */
    public function complete()
    {
        //* session referer check
        /*
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/confirm','FirstOrder/confirm'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }
        */

    }

    /**
     * 指定IDの配送日時情報取得
     */
    public function as_get_address_datetime()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        // 画面描画しない
        $this->autoRender = false;

        $postal = filter_input(INPUT_POST, 'postal');

        // ハイフンチェック
        if (mb_strlen($postal) > 7) {
            // ハイフン部分を削除 macの場合全角ハイフンの文字コードが異なるため単純な全角半角変換ができない
            $postal = mb_substr($postal,0, 3) . mb_substr($postal, 4, 4);
        }
        $postal = mb_convert_kana($postal, 'nhk', "utf-8");

        // 配送日時情報取得
        $this->loadModel('KitDeliveryDatetime');

        $result = $this->KitDeliveryDatetime->getKitDeliveryDatetime(array('postal' => $postal));
        $status = !empty($result);

        // コードを表示用文字列に変換
        App::uses('AppHelper', 'View/Helper');
        $appHelper = new AppHelper(new View());

        $results = [];
        $i = 0;
        foreach ($result->results as $datetime) {
            $datetime_cd = $datetime['datetime_cd'];
            $results[$i]["datetime_cd"] = $datetime_cd;
            $results[$i]["text"] = $appHelper->convDatetimeCode($datetime_cd);
            $i++;
        }

        return json_encode(compact('status', 'results'));
    }

    private function _set_mono_order($Order)
    {
        $params = null;

        $params = array(
            'mono'          => filter_input(INPUT_POST, 'mono'),
            'mono_apparel'  => filter_input(INPUT_POST, 'mono_apparel'),
            'mono_book'     => filter_input(INPUT_POST, 'mono_book'),
        );

        $Order['mono'] = $params;

        return $Order;
    }

    private function _set_hako_order($Order)
    {
        $params = null;

        $params = array(
            'hako'          => (int)filter_input(INPUT_POST, 'hako'),
            'hako_apparel'  => (int)filter_input(INPUT_POST, 'hako_apparel'),
            'hako_book'     => (int)filter_input(INPUT_POST, 'hako_book'),
        );

        $Order['hako'] = $params;

        return $Order;
    }

    private function _set_cleaning_order($Order)
    {
        $Order['cleaning']['cleaning'] = (int)filter_input(INPUT_POST, 'cleaning');

        return $Order;
    }

    private function _set_starter_order($Order)
    {
        $Order['starter']['starter'] = (int)filter_input(INPUT_POST, 'starter');

        return $Order;
    }

}