<?php
App::uses('AppValid', 'Lib');
App::uses('MinikuraController', 'Controller');
App::uses('KitDeliveryDatetime', 'Model');
App::uses('EmailModel', 'Model');
App::uses('CustomerKitPrice', 'Model');
App::uses('PaymentGMOKitCard', 'Model');
App::uses('FirstKitPrice', 'Model');
App::uses('AppCode', 'Lib');

class FirstOrderController extends MinikuraController
{
    // アクセス許可
    protected $checkLogined = false;
    const MODEL_NAME_REGIST = 'CustomerRegistInfo';
    const MODEL_NAME_CARD = 'PaymentGMOCard';
    const MODEL_NAME_SECURITY = 'PaymentGMOSecurityCard';
    
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
        CakeSession::delete(Configure::read('app.lp_option.param'));
        $option = filter_input(INPUT_GET, Configure::read('app.lp_option.param'));
        if (!is_null($option)) {
            CakeSession::write(Configure::read('app.lp_option.param'), $option);
            // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . 'set option ' . $option);
        }

        // 紹介コードで遷移してきた場合
        CakeSession::delete(Configure::read('app.lp_code.param'));
        CakeSession::delete('Email.alliance_cd');
        $code = filter_input(INPUT_GET, Configure::read('app.lp_code.param'));
        if (!is_null($code)) {
            // オプションコードが含まれるか?
            if (strpos($code,'?' . Configure::read('app.lp_option.param')) !== false) {
                list($code, $pram_option) = explode('?', $code);
                list($label, $option) = explode('=', $pram_option);
                CakeSession::write(Configure::read('app.lp_option.param'), $option);
                // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' set option ' . $option);
            }
            CakeSession::write(Configure::read('app.lp_code.param'), $code);
            // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' is code set_code[ ' . $code . ' ]');
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
                // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' is auto login user' . $option);

                // セッションクリーン
                $this->_clean_first_order_session();

                // オートログイン
                $this->redirect($none_first_redirect_param);
            }
        }

        // set action ログインしている
        if ($this->Customer->isLogined()) {

            // エントリーユーザでない
            if (!$this->Customer->isEntry()) {
                // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' is none entry user' . $option);

                // セッションクリーン
                $this->_clean_first_order_session();

                $this->redirect($none_first_redirect_param);
            }

            // スニーカーユーザでない
            if ($this->Customer->isSneaker()) {
                // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' is isSneaker' . $option);

                // セッションクリーン
                $this->_clean_first_order_session();

                $this->redirect($none_first_redirect_param);
            }

            // ログイン済みエントリーユーザ
            // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' is login entry user' . $option);
            $this->redirect(['controller' => 'first_order', 'action' => 'add_order']);
        }

        // スターターキット購入フロー
        $this->redirect(['controller' => 'first_order', 'action' => 'add_order']);
    }

    /**
     * Boxの選択 静的ページからのオプション、ユーザ条件によって表示内容を変更
     */
    public function add_order()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/index', 'FirstOrder/add_order', 'FirstOrder/confirm_order', 'FirstOrder/add_address'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // ログインしているか
        $is_logined = $this->_check_login();
        $this->set('is_logined', $is_logined);

        $lp_option = CakeSession::read(Configure::read('app.lp_option.param'));
        $kit_select_type = 'all';
        switch (true) {
            case $lp_option === 'mono':
                // 紹介コードが有る場合 mono のみ表示 そうでない場合、スターターキット
                if (!is_null(CakeSession::read(Configure::read('app.lp_code.param')))) {
                    $kit_select_type = 'mono';
                } else {
                    $kit_select_type = 'starter_kit';
                }
                break;
            case $lp_option === 'hako':
                $kit_select_type = 'hako';
                break;
            case $lp_option === 'cleaning':
                $kit_select_type = 'cleaning';
                break;
            case $lp_option === 'all':
                $kit_select_type = 'all';
                break;
            default:
                $kit_select_type = 'all';
                break;
        }

        //* キットタイプが変わった場合、オーダーを削除
        $before_kit_select_type = CakeSession::read('kit_select_type', $kit_select_type );
        if($before_kit_select_type !== $kit_select_type) {
            CakeSession::delete('Order');
            CakeSession::delete('OrderTotal');
            CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' Session Order delete');
        }
        CakeSession::write('kit_select_type', $kit_select_type );
        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' kit_select_type ' . $kit_select_type);

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
                $Order = $this->_set_hako_order($Order);
                $OrderTotal['hako_num'] = array_sum($Order['hako']);
                $Order = $this->_set_mono_order($Order);
                $OrderTotal['mono_num'] = array_sum($Order['mono']);
                $Order = $this->_set_cleaning_order($Order);

                // 箱選択されているか
                if (array_sum(array($OrderTotal['mono_num'], $OrderTotal['hako_num'], $Order['cleaning']['cleaning'])) === 0) {
                    $params = array(
                        'select_oreder_mono' => $OrderTotal['mono_num'],
                        'select_oreder_hako' => $OrderTotal['hako_num'],
                        'select_oreder_cleaning' => $Order['cleaning']['cleaning']
                    );
                }
                break;
            case $kit_select_type === 'mono':
                $Order = $this->_set_mono_order($Order);
                $OrderTotal['mono_num'] = array_sum($Order['mono']);
                $params = array('select_oreder_mono' => $OrderTotal['mono_num']);
                break;
            case $kit_select_type === 'hako':
                $Order = $this->_set_hako_order($Order);
                $OrderTotal['hako_num'] = array_sum($Order['hako']);
                $params = array('select_oreder_hako' => $OrderTotal['hako_num']);
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

        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' set Order ' . print_r($Order, true));
        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' set OrderTotal ' . print_r($OrderTotal, true));

        //*  validation 基本は共通クラスのAppValidで行う
        $is_validation_error = false;
        $validation = AppValid::validate($params);

        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->redirect('add_order');
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
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/confirm_order', 'FirstOrder/add_address', 'FirstOrder/add_credit', 'FirstOrder/confirm'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // ログインチェック
        $this->_check_login();

        $back  = filter_input(INPUT_GET, 'back');
        
        if (!$back) {
            // Addressリセット
            if (empty(CakeSession::read('Address'))) {

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
                    'select_delivery_text' => "",
                    'select_delivery_list' => array(),
                );

                // お届け希望日のリスト
                CakeSession::write('Address', $Address);
            }
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
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/add_address', 'FirstOrder/add_address', 'FirstOrder/add_credit'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // ログインチェック
        $this->_check_login();

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

        //* Session write select_delivery_text
        $params['select_delivery_list'] = json_decode($params['select_delivery']);

        foreach ($params['select_delivery_list'] as  $key => $value) {
            if ($value->datetime_cd === $params['datetime_cd']) {
                $params['select_delivery_text'] = $value->text;
            }
        }

        CakeSession::write('Address', $params);

        $params['tel1'] = self::_wrapConvertKana($params['tel1']);

        //*  validation 基本は共通クラスのAppValidで行う
        $validation = AppValid::validate($params);

        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->redirect('add_address');
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
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/confirm_address', 'FirstOrder/add_credit', 'FirstOrder/add_email', 'FirstOrder/confirm'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // ログインチェック
        $this->_check_login();

        $back  = filter_input(INPUT_GET, 'back');
        
        if (!$back) {
            if (empty(CakeSession::read('Credit'))) {
                $Credit = array(
                    'card_no'       => "",
                    'security_cd'   => "",
                    'expire'        => "",
                    'holder_name'   => "",
                );
                CakeSession::write('Credit', $Credit);
            }
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
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/add_credit', 'FirstOrder/add_email'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        $params = [
            'card_no'       => filter_input(INPUT_POST, 'card_no'),
            'security_cd'   => filter_input(INPUT_POST, 'security_cd'),
            'expire_month'  => filter_input(INPUT_POST, 'expire_month'),
            'expire_year'   => filter_input(INPUT_POST, 'expire_year'),
            'expire'        => filter_input(INPUT_POST, 'expire_month').filter_input(INPUT_POST, 'expire_year'),
            'holder_name'   => strtoupper(filter_input(INPUT_POST, 'holder_name')),
        ];

        //* Session write
        CakeSession::write('Credit', $params);

        // ハイフン削除はバリデーション前に実施
        $params['card_no'] = self::_wrapConvertKana($params['card_no']);
        $params['security_cd'] = mb_convert_kana($params['security_cd'], 'nhk', "utf-8");;

        //*  validation 基本は共通クラスのAppValidで行う
        $validation = AppValid::validate($params);

        //* 共通バリデーションでエラーあったらメッセージセット
        //* バリデーションエラーでAPIを実行しないためここでre
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->redirect('add_credit');
            return;
        }
        
        //* クレジットカードのチェック 未ログイン時にチェックできる v4/gmo_payment/card_check apiを使用する
        $this->loadModel('CardCheck');
        $res = $this->CardCheck->getCardCheck($params);

        if (!empty($res->error_message)) {
            $this->Flash->validation($res->error_message, ['key' => 'card_no']);
            $this->redirect('add_credit');
            return;
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        // スターターキットの場合、紹介コードリセット
        CakeSession::delete('code_and_starter_kit');

        // 既存登録ユーザ リセット
        CakeSession::delete('registered_user_login_url', null);

        $this->redirect(['controller' => 'first_order', 'action' => 'add_email']);
    }

    /**
     * メールアドレス 登録
     */
    public function add_email()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/confirm_credit', 'FirstOrder/add_email', 'FirstOrder/confirm'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        $is_logined = $this->_check_login();
        $this->set('is_logined', $is_logined);

        // 誕生日に関するコンフィグ
        $birthyear_configure = Configure::read('app.register.birthyear');
        $this->set('birthyear_configure', $birthyear_configure);

        $back  = filter_input(INPUT_GET, 'back');

        if (!$back) {
            if (empty(CakeSession::read('Email'))) {
                $Email = array(
                    'email' => "",
                    'password' => "",
                    'password_confirm' => "",
                    'birth_year' => "1980",
                    'birth_month' => "",
                    'birth_day' => "",
                    'gender' => "",
                    'newsletter' => "",
                    'alliance_cd' => "",
                    'remember' => "",
                );
                CakeSession::write('Email', $Email);
            }

            // 紹介コードを挿入
            $code = CakeSession::read(Configure::read('app.lp_code.param'));
            if (!is_null($code)) {
                $Email = CakeSession::read('Email');
                $Email['alliance_cd'] = $code;
                CakeSession::write('Email', $Email);
            }
        }

        // ログインしている場合メールアドレスを挿入
        if ($is_logined) {
            $Email = CakeSession::read('Email');
            $Email['email'] = $this->Customer->getInfo()['email'];
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
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/add_email', 'FirstOrder/confirm'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        $is_logined = $this->_check_login();

        // バリデーションエラーフラグ
        $is_validation_error = false;

        // ログイン状態により処理を変更
        if ($is_logined) {
            // ログインしている場合
            $params = [
                'email'            => $this->Customer->getInfo()['email'],
                'birth'            => sprintf("%04d-%02d-%02d", filter_input(INPUT_POST, 'birth_year'), filter_input(INPUT_POST, 'birth_month'), filter_input(INPUT_POST, 'birth_day')),
                'birth_year'       => filter_input(INPUT_POST, 'birth_year'),
                'birth_month'      => filter_input(INPUT_POST, 'birth_month'),
                'birth_day'        => filter_input(INPUT_POST, 'birth_day'),
                'gender'           => filter_input(INPUT_POST, 'gender'),
                'remember'         => filter_input(INPUT_POST, 'remember'),
            ];

        } else {
            // ログインしていない場合
            $password = filter_input(INPUT_POST, 'password');
            $password_confirm = filter_input(INPUT_POST, 'password_confirm');

            $params = [
                'email'            => filter_input(INPUT_POST, 'email'),
                'password'         => $password,
                'password_confirm' => $password_confirm,
                'birth'            => sprintf("%04d-%02d-%02d", filter_input(INPUT_POST, 'birth_year'), filter_input(INPUT_POST, 'birth_month'), filter_input(INPUT_POST, 'birth_day')),
                'birth_year'       => filter_input(INPUT_POST, 'birth_year'),
                'birth_month'      => filter_input(INPUT_POST, 'birth_month'),
                'birth_day'        => filter_input(INPUT_POST, 'birth_day'),
                'gender'           => filter_input(INPUT_POST, 'gender'),
                'newsletter'       => filter_input(INPUT_POST, 'newsletter'),
                'alliance_cd'      => filter_input(INPUT_POST, 'alliance_cd'),
                'remember'         => filter_input(INPUT_POST, 'remember'),
            ];

            // 確認用パスワード一致チェック
            if ($password !== $password_confirm) {
                $this->Flash->validation('パスワードが一致していません。ご確認ください。', ['key' => 'password_confirm']);
                $is_validation_error = true;
            }
        }

        //* Session write
        CakeSession::write('Email', $params);

        //*  validation 基本は共通クラスのAppValidで行う
        $validation = AppValid::validate($params);

        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
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

        // ログインしていないくて、ここまでバリデーションエラーがない場合apiでメール既存チェック
        CakeSession::delete('registered_user_login_url');
        if (!$is_logined) {
            if ($is_validation_error !== true) {
                // 既存ユーザか確認する
                $this->loadModel('Email');
                $result = $this->Email->getEmail(array('email' => $params['email']));

                if ($result->status === "0") {
                    // エラーか既存アドレスか判定
                    if ($result->http_code !== "400") {
                        $this->Flash->validation('登録済メールアドレス', ['key' => 'check_email']);
                        $registered_user_login_url = '/login?c=first_order&a=index&p=' . Configure::read('app.lp_option.param') . '=' . CakeSession::read(Configure::read('app.lp_option.param'));
                        if (!is_null(CakeSession::read(Configure::read('app.lp_code.param')))) {
                            $registered_user_login_url = '/login?c=first_order&a=index&p=' . Configure::read('app.lp_code.param') . '=' . CakeSession::read(Configure::read('app.lp_code.param'))
                                                                                           . '?' . Configure::read('app.lp_option.param') . '=' . CakeSession::read(Configure::read('app.lp_option.param'));
                        }
                        CakeSession::write('registered_user_login_url', $registered_user_login_url);

                        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' registered_user_login_url ' . print_r($registered_user_login_url, true));

                    }
                    $is_validation_error = true;
                }
            }
        }

        if ($is_validation_error === true) {
            $this->redirect('add_email');
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
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/confirm_email', 'FirstOrder/confirm'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // 前処理の不要なセッションを削除
        CakeSession::delete('registered_user_login_url');
        CakeSession::delete('code_and_starter_kit');

        // 確認画面でリダイレクトさせると本登録後にNGの場合リダイレクトしてしまう
        $is_logined = false;
        if ($this->Customer->isLogined()) {
            $is_logined = true;
        }

        $this->set('is_logined', $is_logined);

        // オーダー種類を集計
        // order情報
        $Order = CakeSession::read('Order');

        $FirstOrderList = array();

        // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' Order ' . print_r($Order, true));

        // 添字に対応するコードを設定
        $kit_code = array(
            'mono'          => array('code' => KIT_CD_MONO,             'name' => 'MONO レギュラーボックス'),
            'mono_apparel'  => array('code' => KIT_CD_MONO_APPAREL,     'name' => 'MONO アパレルボックス'),
            'mono_book'     => array('code' => KIT_CD_MONO_BOOK,        'name' => 'MONO ブックボックス'),
            'hako'          => array('code' => KIT_CD_HAKO,             'name' => 'HAKO レギュラーボックス'),
            'hako_apparel'  => array('code' => KIT_CD_HAKO_APPAREL,     'name' => 'HAKO アパレルボックス'),
            'hako_book'     => array('code' => KIT_CD_HAKO_BOOK,        'name' => 'HAKO ブックボックス'),
            'cleaning'      => array('code' => KIT_CD_CLEANING_PACK,    'name' => 'クリーニングパック'),
        );

        $kit_params = array();

        // 表示名とAPI パラメータの生成コードごとに格納
        foreach ($Order as $orders => $kit_order) {
            foreach ($kit_order as $param => $value) {
                if ($value > 0) {
                    // スタータキットは構成が異なるため個別に記述
                    if($param === 'starter') {
                        // 先頭のコードのみ料金が返ってくる
                        $code = KIT_CD_STARTER_MONO;
                        $FirstOrderList[$code]['number']    = 1;
                        $FirstOrderList[$code]['kit_name']  = 'mono スターターパック';
                        $FirstOrderList[$code]['price'] = 0;
                        $kit_params[] = KIT_CD_STARTER_MONO.':1';
                    }

                    // スタータキット以外まとめて処理
                    if (array_key_exists ($param, $kit_code)) {
                        //
                        // $FirstOrderList[$param]['price']     = number_format($kit_code[$param]['price'] * $value * 1);
                        $code = $kit_code[$param]['code'];
                        $FirstOrderList[$code]['number']    = $value;
                        $FirstOrderList[$code]['kit_name']  = $kit_code[$param]['name'];
                        $FirstOrderList[$code]['price'] = 0;
                        $kit_params[] = $code . ':' .$value;
                    }
                }
            }
        }

        $set_kit_params = array();

        // 紹介コードがある場合セット
        $alliance_cd = CakeSession::read('Email.alliance_cd');
        if (!is_null($alliance_cd)) {
            $set_kit_params['alliance_cd'] = $alliance_cd;
        }

        // 文字列にしてカンマ区切りでリクエスト
        $set_kit_params['kit'] = implode(',', $kit_params);

        // キットコードと合計金額を返すAPI
        $this->loadModel('KitPrice');

        $res = $this->KitPrice->getKitPrice($set_kit_params);
        if (!empty($res->error_message)) {
            $this->Flash->validation('料金取得エラー', ['key' => 'kit_price']);
        }

        // コードから対象の配列に挿入
        if (empty($res->error_message)) {
            foreach ($res->results as $key => $value) {
                $code = $value['kit_cd'];
                $FirstOrderList[$code]['price'] = number_format($value['price'] * 1);
            }
        }

        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' FirstOrderList ' . print_r($FirstOrderList, true));

        CakeSession::write('FirstOrderList', $FirstOrderList);
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
    }

    /**
     * オーダー 完了
     */
    public function complete()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/confirm'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // 購入前にログインし、エントリユーザでない場合のチェック
        $is_logined = $this->_check_login();
        $this->set('is_logined', $is_logined);

        // セッションが古い場合があるので再チェック
        // 発送日一覧のエラーチェック
        $result = $this->_get_address_datetime(CakeSession::read('Address.postal'));

        $check_address_datetime_cd = false;
        $address_datetime = CakeSession::read('Address.datetime_cd');
        foreach ($result->results as $key => $value) {
            if ($value['datetime_cd'] === $address_datetime) {
                $check_address_datetime_cd = true;
            }
        }

        if (!$check_address_datetime_cd) {
            $this->Flash->validation('お届け希望日時を選択してください。',
                ['key' => 'datetime_cd']);
            CakeLog::write(DEBUG_LOG,
                $this->name . '::' . $this->action . ' check_address_datetime_cd error');

            return $this->redirect('add_address');
        }

        // カードの有効性をチェック
        //* クレジットカードのチェック 未ログイン時にチェックできる v4/gmo_payment/card_check apiを使用する
        $this->loadModel('CardCheck');
        $Credit = CakeSession::read('Credit');
        $Credit['card_no'] = self::_wrapConvertKana($Credit['card_no']);
        $Credit['security_cd'] = mb_convert_kana($Credit['security_cd'], 'nhk', "utf-8");;

        $res = $this->CardCheck->getCardCheck($Credit);

        if (!empty($res->error_message)) {
            $this->Flash->validation($res->error_message, ['key' => 'card_no']);
            $this->redirect('add_credit');
            return;
        }

        //* 会員登録
        $data = array_merge_recursive(CakeSession::read('Address'), CakeSession::read('Email'));
        unset($data['select_delivery']);
        unset($data['select_delivery_list']);

        $this->loadModel(self::MODEL_NAME_REGIST);

        if ($is_logined) {
            $data['token'] = CakeSession::read(ApiModel::SESSION_API_TOKEN);
            $data['password'] = $this->Customer->getPassword();

            // バリデーションルールを変更
            $this->CustomerRegistInfo->validator()->remove('password_confirm');

        }

        $data['tel1'] = self::_wrapConvertKana($data['tel1']);

        // post値をセット
        $this->CustomerRegistInfo->set($data);

        //*  validation
        if (!$this->CustomerRegistInfo->validates()) {
            // 事前バリデーションチェック済
            $this->Flash->validation('入力情報をご確認ください',
                ['key' => 'customer_regist_info']);
            CakeLog::write(DEBUG_LOG,
                $this->name . '::' . $this->action . ' CustomerRegistInfo validationErrors ' .
                print_r($this->CustomerRegistInfo->validationErrors, true));
            return $this->redirect('confirm');
        }

        if (empty($this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['alliance_cd'])) {
            unset($this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['alliance_cd']);
        }

        // 本登録
        if ($is_logined) {
            $res = $this->CustomerRegistInfo->regist_no_oemkey();
        } else {
            $res = $this->CustomerRegistInfo->regist();
        }

        if (!empty($res->error_message)) {
            // 紹介コードエラーの場合 紹介コード入力に遷移
            if (strpos($res->message, 'alliance_cd') !== false) {
                $this->Flash->validation($res->error_message, ['key' => 'alliance_cd']);
                return $this->redirect('add_email');
            }
            if (strpos($res->message, 'Allow Only Entry') !== false) {
                $this->Flash->validation('登録済ユーザのため購入完了できませんでした。', ['key' => 'customer_regist_info']);
            } else {
                $this->Flash->validation($res->error_message, ['key' => 'customer_regist_info']);
            }
            return $this->redirect('confirm');
        }

        // ログイン
        $this->loadModel('CustomerLogin');

        $this->CustomerLogin->data['CustomerLogin']['email'] = $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['email'];

        $this->CustomerLogin->data['CustomerLogin']['password'] = $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['password'];

        if ($is_logined) {
            // エントリユーザ切り替え再度ログイン
            $this->Customer->switchEntryToCustomer();
        }

        $res = $this->CustomerLogin->login();

        if (!empty($res->error_message)) {
            $this->Flash->validation($res->error_message, ['key' => 'customer_regist_info']);
            return $this->redirect('confirm');
        }

        // カスタマー情報を取得しセッションに保存
        $this->Customer->setTokenAndSave($res->results[0]);
        $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);

        $this->Customer->getInfo();

        //* クレジットカード登録
        $this->loadModel(self::MODEL_NAME_SECURITY);

        $Credit = CakeSession::read('Credit');
        $Credit['card_no'] = self::_wrapConvertKana($Credit['card_no']);
        $Credit['security_cd'] = mb_convert_kana($Credit['security_cd'], 'nhk', "utf-8");;

        $credit_data[self::MODEL_NAME_SECURITY] = $Credit;

        $this->PaymentGMOSecurityCard->set($credit_data);

        // Expire
        $this->PaymentGMOSecurityCard->setExpire($credit_data);

        // ハイフン削除
        $this->PaymentGMOSecurityCard->trimHyphenCardNo($credit_data);

        // validates
        // card_seq 除外
        $this->PaymentGMOSecurityCard->validator()->remove('card_seq');

        if (!$this->PaymentGMOSecurityCard->validates()) {
            $this->Flash->validation($this->PaymentGMOSecurityCard->validationErrors, ['key' => 'customer_card_info']);
            return $this->redirect('confirm');
        }

        $result_security_card = $this->PaymentGMOSecurityCard->apiPost($this->PaymentGMOSecurityCard->toArray());
        if (!empty($result_security_card->error_message)) {
            $this->Flash->validation($result_security_card->error_message, ['key' => 'customer_kit_card_info']);
            return $this->redirect('confirm');
        }

        // 購入
        $this->loadModel('PaymentGMOKitCard');
        $gmo_kit_card['mono_num']      = CakeSession::read('Order.mono.mono');
        $gmo_kit_card['mono_appa_num'] = CakeSession::read('Order.mono.mono_apparel');
        $gmo_kit_card['mono_book_num'] = CakeSession::read('Order.mono.mono_book');
        $gmo_kit_card['hako_num']      = CakeSession::read('Order.hako.hako');
        $gmo_kit_card['hako_appa_num'] = CakeSession::read('Order.hako.hako_apparel');
        $gmo_kit_card['hako_book_num'] = CakeSession::read('Order.hako.hako_book');
        $gmo_kit_card['cleaning_num']  = CakeSession::read('Order.cleaning.cleaning');
        $gmo_kit_card['starter_mono_num']      = CakeSession::read('Order.starter.starter');
        $gmo_kit_card['starter_mono_appa_num'] = CakeSession::read('Order.starter.starter');
        $gmo_kit_card['starter_mono_book_num'] = CakeSession::read('Order.starter.starter');
        $gmo_kit_card['card_seq']      = $result_security_card->results['card_seq'];
        $gmo_kit_card['security_cd']   = self::_wrapConvertKana(CakeSession::read('Credit.security_cd'));
        $gmo_kit_card['address_id']    = '';
        $gmo_kit_card['datetime_cd']   = CakeSession::read('Address.datetime_cd');
        $gmo_kit_card['name']          = CakeSession::read('Address.lastname') . '　' . CakeSession::read('Address.firstname');
        $gmo_kit_card['tel1']          = self::_wrapConvertKana(CakeSession::read('Address.tel1'));
        $gmo_kit_card['postal']        = CakeSession::read('Address.postal');
        $gmo_kit_card['address']       = CakeSession::read('Address.pref') . CakeSession::read('Address.address1') . CakeSession::read('Address.address2') . '　' .  CakeSession::read('Address.address3');

        $productKitList = [
            PRODUCT_CD_MONO => [
                'kitList' => [
                    KIT_CD_MONO,
                    KIT_CD_MONO_APPAREL,
                    KIT_CD_MONO_BOOK,
                    KIT_CD_STARTER_MONO,
                    KIT_CD_STARTER_MONO_APPAREL,
                    KIT_CD_STARTER_MONO_BOOK
                ],
            ],
            PRODUCT_CD_HAKO => [
                'kitList' => [KIT_CD_HAKO, KIT_CD_HAKO_APPAREL, KIT_CD_HAKO_BOOK],
            ],
            PRODUCT_CD_CLEANING_PACK => [
                'kitList' => [KIT_CD_CLEANING_PACK],
            ],
        ];

        $dataKeyNum = [
            KIT_CD_MONO          => 'mono_num',
            KIT_CD_MONO_APPAREL  => 'mono_appa_num',
            KIT_CD_MONO_BOOK     => 'mono_book_num',
            KIT_CD_HAKO          => 'hako_num',
            KIT_CD_HAKO_APPAREL  => 'hako_appa_num',
            KIT_CD_HAKO_BOOK     => 'hako_book_num',
            KIT_CD_CLEANING_PACK => 'cleaning_num',
            KIT_CD_STARTER_MONO          => 'starter_mono_num',
            KIT_CD_STARTER_MONO_APPAREL  => 'starter_mono_appa_num',
            KIT_CD_STARTER_MONO_BOOK     => 'starter_mono_book_num',
        ];
        $kit_params = [];
        foreach ($productKitList as $product) {
            // 個数集計
            foreach ($product['kitList'] as $kitCd) {
                $num = $gmo_kit_card[$dataKeyNum[$kitCd]];
                if (!empty($num)) {
                    $kit_params[] = $kitCd . ':' . $num;
                }
            }
        }
        $gmo_kit_card['kit'] = implode(',', $kit_params);

        $this->PaymentGMOKitCard->set($gmo_kit_card);
        $result_kit_card = $this->PaymentGMOKitCard->apiPost($this->PaymentGMOKitCard->toArray());
        if ($result_kit_card->status !== '1') {
            if ($result_kit_card->http_code === 400) {
                $this->Flash->validation('キット購入エラー', ['key' => 'customer_kit_card_info']);
            } else {
                $this->Flash->validation($result_kit_card->message, ['key' => 'customer_kit_card_info']);
            }
            return $this->redirect('confirm');
        }

        // 完了したページ情報を保存
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->_clean_first_order_session();

        // 既にセッションスタートしてる事が条件
        // マイページ側セッションクローズ
        $before_session_id = session_id();
        session_write_close();

        // コンテンツ側のセッション名に変更
        $SESS_ID = '';
        if(isset($_COOKIE['WWWMINIKURACOM'])) {
            $SESS_ID = $_COOKIE['WWWMINIKURACOM'];
        }

        if( !empty($SESS_ID)) {
            // セッション再開
            session_id($SESS_ID);
            session_start();

            // 紹介コード削除処理
            if (!empty($_SESSION['ref_code'])) {
                unset($_SESSION['ref_code']);
                CakeLog::write(DEBUG_LOG, 'ref_code is unset SESS_ID ' . print_r($SESS_ID, true));
            }

            // コンテンツ側セッションクローズ
            session_write_close();
        }

        // マイページ側セッション名に変更
        session_name('MINIKURACOM');

        if (!empty($before_session_id)) {
            session_id($before_session_id);
        }

        // マイページ側セッション再開
        session_start();

    }

    /**
     * ajax 指定IDの配送日時情報取得
     */
    public function as_get_address_datetime()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        // 画面描画しない
        $this->autoRender = false;

        $postal = filter_input(INPUT_POST, 'postal');

        $result = $this->_get_address_datetime($postal);

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

    /**
     * 指定IDの配送日時情報取得
     */
    private function _get_address_datetime($postal)
    {
        // ハイフンチェック
        if (mb_strlen($postal) > 7) {
            // ハイフン部分を削除 macの場合全角ハイフンの文字コードが異なるため単純な全角半角変換ができない
            $postal = mb_substr($postal,0, 3) . mb_substr($postal, 4, 4);
        }
        $postal = mb_convert_kana($postal, 'nhk', "utf-8");

        // 配送日時情報取得
        $this->loadModel('KitDeliveryDatetime');

        $result = $this->KitDeliveryDatetime->getKitDeliveryDatetime(array('postal' => $postal));

        return $result;
    }

    /**
     * kit box mono 箱数をset
     */
    private function _set_mono_order($Order)
    {
        $params = array(
            'mono'          => filter_input(INPUT_POST, 'mono'),
            'mono_apparel'  => filter_input(INPUT_POST, 'mono_apparel'),
            'mono_book'     => filter_input(INPUT_POST, 'mono_book'),
        );
        $Order['mono'] = $params;
        return $Order;
    }

    /**
     * kit box hako 箱数をset
     */
    private function _set_hako_order($Order)
    {
        $params = array(
            'hako'          => (int)filter_input(INPUT_POST, 'hako'),
            'hako_apparel'  => (int)filter_input(INPUT_POST, 'hako_apparel'),
            'hako_book'     => (int)filter_input(INPUT_POST, 'hako_book'),
        );
        $Order['hako'] = $params;
        return $Order;
    }

    /**
     * kit box cleaning 箱数をset
     */
    private function _set_cleaning_order($Order)
    {
        $Order['cleaning']['cleaning'] = (int)filter_input(INPUT_POST, 'cleaning');
        return $Order;
    }

    /**
     * kit box starter set
     */
    private function _set_starter_order($Order)
    {
        $Order['starter']['starter'] = (int)filter_input(INPUT_POST, 'starter');
        return $Order;
    }

    /**
     * 全角半角変換　Mac全角ハイフン対応版
     *
     * @access    private
     * @param
     * @return    ログイン済 true ,未ログイン false
     */
    private function _check_login()
    {
        $is_logined = false;
        if ($this->Customer->isLogined()) {
            if (!$this->Customer->isEntry()) {

                // セッションクリーン
                $this->_clean_first_order_session();

                $this->redirect(array('controller' => 'login', 'action' => 'index'));
            }

            $is_logined = true;
        }

        return $is_logined;
    }

    /**
     * first orderで使用しているセッション類を削除
     */
    private function _clean_first_order_session()
    {
        CakeSession::delete('kit_select_type');
        CakeSession::delete('Order');
        CakeSession::delete('OrderTotal');
        CakeSession::delete('Address');
        CakeSession::delete('Credit');
        CakeSession::delete('Email');
        CakeSession::delete('FirstOrderList');
    }



}
