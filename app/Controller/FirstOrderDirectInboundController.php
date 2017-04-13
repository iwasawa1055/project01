<?php
App::uses('AppValid', 'Lib');
App::uses('MinikuraController', 'Controller');
App::uses('KitDeliveryDatetime', 'Model');
App::uses('EmailModel', 'Model');
App::uses('PaymentGMOKitCard', 'Model');
App::uses('InboundDirect', 'Model');
App::uses('InboundDirectArrival', 'Model');
App::uses('AppCode', 'Lib');

class FirstOrderDirectInboundController extends MinikuraController
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
        CakeSession::delete('order_option');
        $option = filter_input(INPUT_GET, Configure::read('app.lp_option.param'));
        if (!is_null($option)) {
            CakeSession::write('order_option', $option);
        }

        // 紹介コードで遷移してきた場合
        CakeSession::delete('order_code');
        CakeSession::delete('Email.alliance_cd');
        $code = filter_input(INPUT_GET, Configure::read('app.lp_code.param'));
        if (!is_null($code)) {
            // オプションコードが含まれるか?
            if (strpos($code,'?' . Configure::read('app.lp_option.param')) !== false) {
                list($code, $pram_option) = explode('?', $code);
                list($label, $option) = explode('=', $pram_option);
                CakeSession::write('order_option', $option);
            }
            CakeSession::write('order_code', $code);
            CakeSession::write('order_option', 'code');

            CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' code ' . $code);

            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // スニーカー判定 keyがあれば空白なければnull
        CakeSession::delete('order_sneaker');
        if (filter_input(INPUT_GET, Configure::read('app.sneaker_option.param')) === '') {
            // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' key sneaker ');
            CakeSession::write('order_sneaker', true);
            CakeSession::write('order_option', 'sneaker');
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);

        }
        // 紹介コードが sneakers の場合
        if ($code === Configure::read('api.sneakers.alliance_cd')) {
            CakeSession::write('order_sneaker', true);
            CakeSession::write('order_option', 'sneaker');
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        /* 以下 初回購入フロー条件判定 */
        // オートログイン確認
        // tokenが存在する
        if (!empty($_COOKIE['token'])) {
            $cookie_login_param = AppCode::decodeLoginData($_COOKIE['token']);
            $login_params = explode(' ', $cookie_login_param);

            // 取得した配列のカウントが2である
            if (count($login_params) === 2) {
                // セッション削除しログイン画面へ遷移
                $this->_redirectLogin();
            }
        }

        // ログインしている場合
        if ($this->Customer->isLogined()) {

            // 本登録ユーザの場合エントリーユーザでない
            if (!$this->Customer->isEntry()) {
                // セッション削除しログイン画面へ遷移
                $this->_redirectLogin();
            }

            // ログインしており、本登録でない、スニーカーユーザの場合
            if ($this->Customer->isSneaker()) {
                // スニーカー購入動線遷移
                CakeSession::write('order_sneaker', true);
                CakeSession::write('order_option', 'sneaker');

                // ログイン済みスニーカーユーザ エントリーユーザ 初回購入フローへ
                $this->redirect(['controller' => 'first_order', 'action' => 'index']);
            }

            // スニーカでないエントリユーザの場合コードがあってもスニーカではない
            CakeSession::write('order_sneaker', false);

            // スニーカコードの場合 コードオプションを削除する
            if ($code === Configure::read('api.sneakers.alliance_cd')) {
                CakeSession::delete('order_option');
                CakeSession::delete('order_code');
                $this->redirect(['controller' => 'first_order', 'action' => 'index']);
            }

            // エントリユーザの紹介コードの確認
            $entry_user_alliance_cd = $this->Customer->getCustomerAllianceCd();

            // 紹介コードが空でない場合、紹介コードを上書き
            if (!empty($entry_user_alliance_cd)) {
                CakeSession::write('order_code', $entry_user_alliance_cd);
                CakeSession::write('order_option', 'code');
                $this->redirect(['controller' => 'first_order', 'action' => 'index']);
            }

            // ログイン済みエントリーユーザ 初回購入フローへ
            $this->redirect(['controller' => 'first_order_direct_inbound', 'action' => 'add_address']);
        }

        // 初回購入フロー
        $this->redirect(['controller' => 'first_order_direct_inbound', 'action' => 'add_address']);
    }

    /**
     * Boxの選択 静的ページからのオプション、ユーザ条件によって表示内容を変更
     */
    public function add_order()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrderDirectInbound/index', 'FirstOrderDirectInbound/add_order', 'FirstOrderDirectInbound/confirm_order', 'FirstOrderDirectInbound/add_address'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // ログインしているか
        $is_logined = $this->_checkLogin();
        $this->set('is_logined', $is_logined);

        $order_option = CakeSession::read('order_option');
        $kit_select_type = 'all';
        switch (true) {
            case $order_option === 'all':
                $kit_select_type = 'all';
                break;
            case $order_option === 'code':
                $kit_select_type = 'code';
                break;
            case $order_option === 'sneaker':
                $kit_select_type = 'sneaker';
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
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrderDirectInbound/add_order', 'FirstOrderDirectInbound/add_address'], true) === false) {
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
                $Order = $this->_setHakoOrder($Order);
                $OrderTotal['hako_num'] = array_sum($Order['hako']);
                $Order = $this->_setMonoOrder($Order);
                $OrderTotal['mono_num'] = array_sum($Order['mono']);
                $Order = $this->_setCleaningOrder($Order);
                $Order = $this->_setHakoLimitedVer1Order($Order);

                // 箱選択されているか
                if (array_sum(array($OrderTotal['mono_num'], $OrderTotal['hako_num'], $Order['cleaning']['cleaning'], $Order['hako_limited_ver1']['hako_limited_ver1'])) === 0) {
                    $params = array(
                        'select_oreder_mono' => $OrderTotal['mono_num'],
                        'select_oreder_hako' => $OrderTotal['hako_num'],
                        'select_oreder_cleaning' => $Order['cleaning']['cleaning'],
                        'select_oreder_hako_limited_ver1' => $Order['hako_limited_ver1']['hako_limited_ver1']
                    );
                }
                break;
            case $kit_select_type === 'code':
                $Order = $this->_setHakoOrder($Order);
                $OrderTotal['hako_num'] = array_sum($Order['hako']);
                $Order = $this->_setMonoOrder($Order);
                $OrderTotal['mono_num'] = array_sum($Order['mono']);
                $Order = $this->_setCleaningOrder($Order);

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
                $Order = $this->_setMonoOrder($Order);
                $OrderTotal['mono_num'] = array_sum($Order['mono']);
                $params = array('select_oreder_mono' => $OrderTotal['mono_num']);
                break;
            case $kit_select_type === 'hako':
                $Order = $this->_setHakoOrder($Order);
                $OrderTotal['hako_num'] = array_sum($Order['hako']);
                $params = array('select_oreder_hako' => $OrderTotal['hako_num']);
                break;
            case $kit_select_type === 'cleaning':
                $Order = $this->_setCleaningOrder($Order);
                $params = array('select_oreder_cleaning' => $Order['cleaning']['cleaning']);
                break;
            case $kit_select_type === 'starter_kit':
                $Order = $this->_setStarterOrder($Order);
                $params = array('select_starter_kit' => $Order['starter']['starter']);
                break;
            case $kit_select_type === 'sneaker':
                $Order = $this->_setSneakerOrder($Order);
                $params = array('select_oreder_sneaker' => $Order['sneaker']['sneaker']);
                break;
            case $kit_select_type === 'hako_limited_ver1':
                $Order = $this->_setHakoLimitedVer1Order($Order);
                $params = array('select_oreder_hako_limited_ver1' => $Order['hako_limited_ver1']['hako_limited_ver1']);
                break;
            //hako_limited_ver1
            default:
                break;
        }

        //* Session write
        CakeSession::write('Order', $Order);
        CakeSession::write('OrderTotal', $OrderTotal);

        // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' set Order ' . print_r($Order, true));
        // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' set OrderTotal ' . print_r($OrderTotal, true));

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

        $this->redirect('add_address');

    }

    /**
     * ユーザ名 住所の登録
     */
    public function add_address()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/add_order', 'FirstOrderDirectInbound/index', 'FirstOrderDirectInbound/add_address', 'FirstOrderDirectInbound/add_credit', 'FirstOrderDirectInbound/confirm'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // ログインチェック
        $this->_checkLogin();

        $back  = filter_input(INPUT_GET, 'back');
        
        if (!$back) {
            // Addressリセット
            if (is_null(CakeSession::read('Address'))) {

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
                    'cargo'       => "ヤマト運輸",
                );

                // お届け希望日のリスト
                CakeSession::write('Address', $Address);
            }

            if(is_null(CakeSession::read('Order'))) {
                $Order = array(
                    "direct_inbound" => array(
                        "direct_inbound" => 0,
                    ),
                );
                CakeSession::write('Order', $Order);
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
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrderDirectInbound/add_address', 'FirstOrderDirectInbound/add_address', 'FirstOrderDirectInbound/add_credit'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // ログインチェック
        $this->_checkLogin();

        $set_order_params = array();
        $set_order_params = $this->_setDirectInbound($set_order_params);
        $order_params = $set_order_params['direct_inbound'];

        // FirstOrderと階層を合わせる
        CakeSession::write('Order', $set_order_params);

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
            'cargo'             => filter_input(INPUT_POST, 'cargo'),
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

        if ($params['cargo'] === "着払い") {
            unset($params['datetime_cd']);
        }

        $validation_params = array_merge($order_params,$params);

//        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' validation_params ' . print_r($validation_params, true));

        //*  validation 基本は共通クラスのAppValidで行う
        $validation = AppValid::validate($validation_params);

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
        
        $this->redirect('add_credit');
    }

    /**
     * クレジットカード 登録
     */
    public function add_credit()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrderDirectInbound/confirm_address', 'FirstOrderDirectInbound/add_credit', 'FirstOrderDirectInbound/add_email', 'FirstOrderDirectInbound/confirm'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // ログインチェック
        $this->_checkLogin();

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
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrderDirectInbound/add_credit', 'FirstOrderDirectInbound/add_email'], true) === false) {
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

        // 既存登録ユーザ リセット
        CakeSession::delete('registered_user_login_url', null);

        $this->redirect('add_email');
    }

    /**
     * メールアドレス 登録
     */
    public function add_email()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrderDirectInbound/confirm_credit', 'FirstOrderDirectInbound/add_email', 'FirstOrderDirectInbound/confirm'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        $is_logined = $this->_checkLogin();
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
            $code = CakeSession::read('order_code');
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

        // 紹介コードを表示しないパターン
        // 直接預入の場合紹介コードを表示しない
/*
        $display_alliance_cd = true;
        // スターターキットの場合 非表示
        if (CakeSession::read('kit_select_type') === 'starter_kit') {
            $display_alliance_cd = false;
            // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' display_alliance_cd is starter_kit');
        }
        // hako5個パックが1個以上選択されている場合 非表示
        if (CakeSession::read('Order.hako_limited_ver1.hako_limited_ver1') > 0) {
            $display_alliance_cd = false;
        }

        $this->set('display_alliance_cd', $display_alliance_cd);
*/
        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
    }


    /**
     * メールアドレス 確認
     */
    public function confirm_email()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrderDirectInbound/add_email', 'FirstOrderDirectInbound/confirm'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        $is_logined = $this->_checkLogin();

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
                'alliance_cd'      => filter_input(INPUT_POST, 'alliance_cd'),
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
        }

        if ($is_validation_error === true) {
            $this->redirect('add_email');
            return;
        }
        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
        
        $this->redirect('confirm');
    }

    /**
     * オーダー 会員登録
     */
    public function confirm()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrderDirectInbound/confirm_email', 'FirstOrderDirectInbound/confirm'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // 前処理の不要なセッションを削除
        CakeSession::delete('registered_user_login_url');

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
        // 添字に対応するコードを設定
        //
        $FirstOrderList['direct_inbound']['number']    = $Order['direct_inbound']['direct_inbound'];
        $FirstOrderList['direct_inbound']['kit_name']  = 'minikuraマイボックス';
        $FirstOrderList['direct_inbound']['price'] = 0;
        $FirstOrderList['storage_fee']['number']    = $Order['direct_inbound']['direct_inbound'];
        $FirstOrderList['storage_fee']['kit_name']  = '月額保管料（250円）';
        $FirstOrderList['storage_fee']['price'] = $Order['direct_inbound']['direct_inbound'] * 250;
        $FirstOrderList['shipping_fee']['number']    = '';
        $FirstOrderList['shipping_fee']['kit_name']  = '預け入れ送料';
        $FirstOrderList['shipping_fee']['price'] = 0;


        // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' FirstOrderList ' . print_r($FirstOrderList, true));

        CakeSession::write('FirstOrderList', $FirstOrderList);
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
    }


    /**
     * オーダー 完了
     */
    public function complete()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrderDirectInbound/confirm'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // 購入前にログインし、エントリユーザでない場合のチェック
        $is_logined = $this->_checkLogin();
        $this->set('is_logined', $is_logined);

        // セッションが古い場合があるので再チェック
        // 発送日一覧のエラーチェック
        // 着払いでない場合
        if (CakeSession::read('Address.cargo') !== "着払い") {
            $result = $this->_getAddressDatetime(CakeSession::read('Address.postal'));

            $check_address_datetime_cd = false;
            $address_datetime = CakeSession::read('Address.datetime_cd');
            foreach ($result->results as $key => $value) {
                if ($value['datetime_cd'] === $address_datetime) {
                    $check_address_datetime_cd = true;
                }
            }

            if (!$check_address_datetime_cd) {
                $this->Flash->validation('お届け希望日時をご確認ください。',
                    ['key' => 'datetime_cd']);
                CakeLog::write(DEBUG_LOG,
                    $this->name . '::' . $this->action . ' check_address_datetime_cd error');

                return $this->redirect('add_address');
            }
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
            $this->Flash->validation('入力情報をご確認ください', ['key' => 'customer_regist_info']);
            return $this->redirect('confirm');
        }

        if (empty($this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['alliance_cd'])) {
            unset($this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['alliance_cd']);
        }

        // 本登録
        if ($is_logined) {
            $res = $this->CustomerRegistInfo->regist_no_oemkey();
        } else {
            // スニーカーユーザかどうか
            if (!CakeSession::read('order_sneaker')) {
                //スニーカーでない
                $res = $this->CustomerRegistInfo->regist();
            } else {
                // スニーカーユーザかどうか
                $res = $this->CustomerRegistInfo->regist_sneakers();
            }
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

        // ボックス情報の生成
        $box = "";
        for($i = 0;$i < CakeSession::read('Order.direct_inbound.direct_inbound'); $i++) {
            $number = $i + 1;
            if(empty($box)) {
                //$box .= PRODUCT_CD_DIRECT_INBOUND.':'.'ボックス' . $number . ':';
                $box .= PRODUCT_CD_MONO . ':' . 'ボックス' . $number . ':';
            } else {
                //$box .= ',' . PRODUCT_CD_DIRECT_INBOUND.':'.'ボックス' . $number . ':';
                $box .= ',' . PRODUCT_CD_MONO . ':' . 'ボックス' . $number . ':';
            }
        }

        // 入庫
        if (CakeSession::read('Address.cargo') !== "着払い") {

            $this->InboundDirect = new InboundDirect();

            $inbound_direct = array();
            $inbound_direct['box']          = $box;
            $inbound_direct['lastname']     = CakeSession::read('Address.lastname');
            $inbound_direct['firstname']    = CakeSession::read('Address.firstname');
            $inbound_direct['tel1']         = self::_wrapConvertKana(CakeSession::read('Address.tel1'));
            $inbound_direct['postal']       = CakeSession::read('Address.postal');
            $inbound_direct['pref']         = CakeSession::read('Address.pref');
            $inbound_direct['address1']     = CakeSession::read('Address.address1');
            $inbound_direct['address2']     = CakeSession::read('Address.address2');
            $inbound_direct['address3']     = CakeSession::read('Address.address3');
            $datetime_cd                    = array();
            if(!empty(CakeSession::read('Address.datetime_cd'))) {
                $datetime_cd                = explode('-',CakeSession::read('Address.datetime_cd'));
                if(count($datetime_cd) > 1) {
                    $inbound_direct['day_cd'] = $datetime_cd[0] . '-' . $datetime_cd[1] . '-' . $datetime_cd[2];

                    // 時間帯がない場合
                    if(count($datetime_cd) > 2) {
                        $inbound_direct['time_cd'] = $datetime_cd[3];
                    }
                }
            }

            $res = $this->InboundDirect->postInboundDirect($inbound_direct);
            if (!empty($res->message)) {
                $this->Flash->validation('直接入庫処理エラー', ['key' => 'inbound_direct']);
                return $this->redirect('confirm');
            }
        } else {
            $this->InboundDirectArrival = new InboundDirectArrival();

            $inbound_direct = array();
            $inbound_direct['box']          = $box;

            $res = $this->InboundDirectArrival->postInboundDirectArrival($inbound_direct);
            if (!empty($res->message)) {
                $this->Flash->validation('直接入庫処理エラー', ['key' => 'inbound_direct']);
                return $this->redirect('confirm');
            }
        }

        // 完了したページ情報を保存
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->_cleanFirstOrderSession();

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

        $result = $this->_getAddressDatetime($postal);

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
    private function _getAddressDatetime($postal)
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
     * オプションパラメータをセットし、ログイン画面にリダイレクト
     */
    private function _redirectLogin()
    {
        // セッションクリーン
        $this->_cleanFirstOrderSession();

        $set_param = array();
        $code = CakeSession::read('order_code');
        if (!empty($code)) {
            $set_param[] = array(Configure::read('app.lp_code.param') =>  $code);
        }

        $option = CakeSession::read('order_option');
        if (!empty($option)) {
            $set_param[] = array(Configure::read('app.switch_redirect.param') =>  $option);
        }

        // 初回購入フローに入らない場合の遷移先 オプション指定をそのまま引き継ぐ
        $redirect_param = array(
            'controller' => 'login',
            'action' => 'index',
            '?' => $set_param
        );

        $this->redirect($redirect_param);
        return ;
    }


    /**
     * kit box starter set
     */
    private function _setDirectInbound($Order)
    {
        $Order['direct_inbound']['direct_inbound'] = (int)filter_input(INPUT_POST, 'direct_inbound');
        return $Order;
    }

    /**
     * ログインチェック
     *
     * @access    private
     * @param
     * @return    ログイン済 true ,未ログイン false
     */
    private function _checkLogin()
    {
        $is_logined = false;
        if ($this->Customer->isLogined()) {
            if (!$this->Customer->isEntry()) {

                // セッションクリーン
                $this->_cleanFirstOrderSession();

                $this->redirect(array('controller' => 'login', 'action' => 'index'));
            }

            $is_logined = true;
        }

        return $is_logined;
    }

    /**
     * first orderで使用しているセッション類を削除
     */
    private function _cleanFirstOrderSession()
    {
        CakeSession::delete('kit_select_type');
        CakeSession::delete('Order');
        CakeSession::delete('OrderTotal');
        CakeSession::delete('Address');
        CakeSession::delete('Credit');
        CakeSession::delete('Email');
        CakeSession::delete('FirstOrderList');
        CakeSession::delete('order_sneaker');

    }



}
