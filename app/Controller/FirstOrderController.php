<?php
App::uses('AppValid', 'Lib');
App::uses('MinikuraController', 'Controller');
App::uses('KitDeliveryDatetime', 'Model');
App::uses('EmailModel', 'Model');
App::uses('CustomerKitPrice', 'Model');
App::uses('PaymentGMOKitCard', 'Model');
App::uses('FirstKitPrice', 'Model');
App::uses('AmazonPayModel', 'Model');
App::uses('PaymentAmazonKitAmazonPay', 'Model');
App::uses('AppCode', 'Lib');

/**
 * @property CustomerRegistInfoAmazonPay $CustomerRegistInfoAmazonPay
 * @property CustomerLoginAmazonPay $CustomerLoginAmazonPay
 */
class FirstOrderController extends MinikuraController
{
    // アクセス許可
    protected $checkLogined = false;
    const MODEL_NAME_REGIST = 'CustomerRegistInfo';
    const MODEL_NAME_REGIST_AMAZON_PAY = 'CustomerRegistInfoAmazonPay';
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
        // #14395 リダイレクトループの対策として以前に発行した「.minikura.com」ドメインのcookie()を削除します。
        // 該当のcookieの最長の有効期限は2018/09/14となるので、それ以降に下の処理の削除をお願いします。
        setcookie("WWWMINIKURACOM", "", time()-60, "", ".minikura.com");
        setcookie("MINIKURACOM", "", time()-60, "", ".minikura.com");

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        // Amazonpay 初期化
        CakeSession::delete('registered_user_login_url');
        CakeSession::delete('FirstOrder.amazon_pay.access_token');

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
        }

        // スニーカー判定 keyがあれば空白なければnull
        CakeSession::delete('order_sneaker');
        if (filter_input(INPUT_GET, Configure::read('app.sneaker_option.param')) === '') {
            // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' key sneaker ');
            CakeSession::write('order_sneaker', true);
            CakeSession::write('order_option', 'sneaker');
        }
        // 紹介コードが sneakers の場合
        if ($code === Configure::read('api.sneakers.alliance_cd')) {
            CakeSession::write('order_sneaker', true);
            CakeSession::write('order_option', 'sneaker');
        }

        /* 以下 初回購入フロー条件判定 */
        // オートログイン確認
        // tokenが存在する
        if (!empty($_COOKIE['token'])) {
            // エントリーユーザーの場合は以下のリダイレクト処理を実施しない
            if (!$this->Customer->isEntry()) {
                $cookie_login_param = AppCode::decodeLoginData($_COOKIE['token']);
                $login_params = explode(' ', $cookie_login_param);

                // 取得した配列のカウントが2である
                if (count($login_params) === 2) {
                    // セッション削除しログイン画面へ遷移
                    $this->_redirectLogin();
                }
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
                $this->_flowSwitch('add_order');
            }

            // スニーカでないエントリユーザの場合コードがあってもスニーカではない
            CakeSession::write('order_sneaker', false);

            // スニーカコードの場合 コードオプションを削除する
            if ($code === Configure::read('api.sneakers.alliance_cd')) {
                CakeSession::delete('order_option');
                CakeSession::delete('order_code');
            }

            // エントリユーザの紹介コードの確認
            $entry_user_alliance_cd = $this->Customer->getCustomerAllianceCd();

            // 紹介コードが空でない場合、紹介コードを上書き
            if (!empty($entry_user_alliance_cd)) {
                CakeSession::write('order_code', $entry_user_alliance_cd);
                CakeSession::write('order_option', 'code');
            }

            // ログイン済みエントリーユーザ 初回購入フローへ
            $this->_flowSwitch('add_order');
        }

        // 初回購入フロー
        $this->_flowSwitch('add_order');
    }

    /**
     * Boxの選択 静的ページからのオプション、ユーザ条件によって表示内容を変更
     */
    public function add_order()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/index', 'FirstOrder/add_order', 'FirstOrder/confirm_order', 'FirstOrder/add_address', 'FirstOrderDirectInbound/add_address'], true) === false) {
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
            // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' Session Order delete');
        }
        CakeSession::write('kit_select_type', $kit_select_type );
        // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' kit_select_type ' . $kit_select_type);

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

    }

    /**
     * スニーカーview変更
     * Boxの選択 静的ページからのオプション、ユーザ条件によって表示内容を変更
     */
    public function add_order_sneaker()
    {
        // refererは基本の初回購入フローを使用する
        $this->setAction('add_order');
        return $this->render('add_order_sneaker');
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
            $this->_flowSwitch('add_order');
            return;
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->_flowSwitch('add_address');

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
        $this->_checkLogin();

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
     * スニーカーview変更
     * ユーザ名 住所の登録
     */
    public function add_address_sneaker()
    {
        // refererは基本の初回購入フローを使用する
        $this->setAction('add_address');
        return $this->render('add_address_sneaker');
    }


    /**
     * アマゾンペイメント widgetで遷移先を指定
     * アマゾンペイメントでアカウント情報を取得
     */
    public function add_amazon_profile()
    {

        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/confirm_order', 'FirstOrder/add_order'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // アクセストークンを取得
        $access_token = filter_input(INPUT_GET, 'access_token');
        if($access_token === null) {
            $this->Flash->validation('Amazonアカウントでお支払い ログインエラー', ['key' => 'amazon_pay_access_token']);
            $this->redirect(['controller' => 'first_order', 'action' => 'add_order']);
        }

        CakeSession::write('FirstOrder.amazon_pay.access_token', $access_token);

        $this->loadModel('AmazonPayModel');
        $res = $this->AmazonPayModel->getUserInfo($access_token);

        // 情報が取得できているか確認
        if(!isset($res['name']) || !isset($res['user_id']) || !isset($res['email'])) {
            $this->Flash->validation('Amazonアカウントでお支払い アカウント情報エラー', ['key' => 'amazon_pay_access_token']);
            $this->redirect(['controller' => 'first_order', 'action' => 'add_order']);
        }

        if(($res['name'] === '') || ($res['user_id'] === '') || ($res['email'] === '')) {
            $this->Flash->validation('Amazonアカウントでお支払い アカウント情報エラー', ['key' => 'amazon_pay_access_token']);
            $this->redirect(['controller' => 'first_order', 'action' => 'add_order']);
        }

        CakeSession::write('FirstOrder.amazon_pay.user_info', $res);


        // オーダー処理
        $kit_select_type = CakeSession::read('kit_select_type');

        // order情報取得
        $Order = CakeSession::read('Order');
        $OrderTotal = CakeSession::read('OrderTotal');

        //* post parameter
        // 購入情報によって分岐
        $params = array();
        switch (true) {
            case $kit_select_type === 'all':
                $Order = $this->_setHakoOrderByGet($Order);
                $OrderTotal['hako_num'] = array_sum($Order['hako']);
                $Order = $this->_setMonoOrderByGet($Order);
                $OrderTotal['mono_num'] = array_sum($Order['mono']);
                $Order = $this->_setCleaningOrderByGet($Order);
                $Order = $this->_setHakoLimitedVer1OrderByGet($Order);

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
                $Order = $this->_setHakoOrderByGet($Order);
                $OrderTotal['hako_num'] = array_sum($Order['hako']);
                $Order = $this->_setMonoOrderByGet($Order);
                $OrderTotal['mono_num'] = array_sum($Order['mono']);
                $Order = $this->_setCleaningOrderByGet($Order);

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
                $Order = $this->_setMonoOrderByGet($Order);
                $OrderTotal['mono_num'] = array_sum($Order['mono']);
                $params = array('select_oreder_mono' => $OrderTotal['mono_num']);
                break;
            case $kit_select_type === 'hako':
                $Order = $this->_setHakoOrderByGet($Order);
                $OrderTotal['hako_num'] = array_sum($Order['hako']);
                $params = array('select_oreder_hako' => $OrderTotal['hako_num']);
                break;
            case $kit_select_type === 'cleaning':
                $Order = $this->_setCleaningOrderByGet($Order);
                $params = array('select_oreder_cleaning' => $Order['cleaning']['cleaning']);
                break;
            case $kit_select_type === 'starter_kit':
                $Order = $this->_setStarterOrderByGet($Order);
                $params = array('select_starter_kit' => $Order['starter']['starter']);
                break;
            case $kit_select_type === 'sneaker':
                $Order = $this->_setSneakerOrderByGet($Order);
                $params = array('select_oreder_sneaker' => $Order['sneaker']['sneaker']);
                break;
            case $kit_select_type === 'hako_limited_ver1':
                $Order = $this->_setHakoLimitedVer1OrderByGet($Order);
                $params = array('select_oreder_hako_limited_ver1' => $Order['hako_limited_ver1']['hako_limited_ver1']);
                break;
            //hako_limited_ver1
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
            $is_validation_error = true;
        }

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
                    $registered_user_login_url = '/login?c=order&a=add&p=' . Configure::read('app.lp_option.param') . '=' . CakeSession::read('order_option');
                    if (!is_null(CakeSession::read('order_code'))) {
                        $registered_user_login_url = '/login?c=order&a=add&p=' . Configure::read('app.lp_code.param') . '=' . CakeSession::read('order_code')
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

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        //$this->redirect($set_url);
        $this->redirect('/first_order/add_amazon_pay');
    }

    /**
     * アマゾンペイメント widgetで遷移先を指定
     * アマゾンペイメントで
     */
    public function add_amazon_pay()
    {   
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/add_amazon_profile', 'FirstOrder/add_amazon_pay', 'FirstOrder/confirm_amazon_pay'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

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
        }
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
        $this->_checkLogin();

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
            $this->_flowSwitch('add_address');
            return;
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
        
        $this->_flowSwitch('add_credit');
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
     * スニーカーview変更
     * クレジットカード 登録
     */
    public function add_credit_sneaker()
    {
        // refererは基本の初回購入フローを使用する
        $this->setAction('add_credit');
        return $this->render('add_credit_sneaker');
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
            $this->_flowSwitch('add_credit');
            return;
        }
        
        //* クレジットカードのチェック 未ログイン時にチェックできる v4/gmo_payment/card_check apiを使用する
        $this->loadModel('CardCheck');
        $res = $this->CardCheck->getCardCheck($params);

        if (!empty($res->error_message)) {
            $this->Flash->validation($res->error_message, ['key' => 'card_no']);
            $this->_flowSwitch('add_credit');
            return;
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        // 既存登録ユーザ リセット
        CakeSession::delete('registered_user_login_url', null);

        $this->_flowSwitch('add_email');
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

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
    }

    /**
     * スニーカーview変更
     * メールアドレス 登録
     */
    public function add_email_sneaker()
    {
        // refererは基本の初回購入フローを使用する
        $this->setAction('add_email');
        return $this->render('add_email_sneaker');
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
                        // エントリーユーザ
                        if ($this->Customer->isEntry()) {
                            $registered_user_login_url = '/login?c=first_order&a=index&p=' . Configure::read('app.lp_option.param') . '=' . CakeSession::read('order_option');
                            if (!is_null(CakeSession::read('order_code'))) {
                                $registered_user_login_url = '/login?c=first_order&a=index&p=' . Configure::read('app.lp_code.param') . '=' . CakeSession::read('order_code')
                                                                                               . '?' . Configure::read('app.lp_option.param') . '=' . CakeSession::read('order_option');
                            }
                        // 一般ユーザ
                        } else {
                            $registered_user_login_url = '/login?c=order&a=add&p=' . Configure::read('app.lp_option.param') . '=' . CakeSession::read('order_option');
                            if (!is_null(CakeSession::read('order_code'))) {
                                $registered_user_login_url = '/login?c=order&a=add&p=' . Configure::read('app.lp_code.param') . '=' . CakeSession::read('order_code')
                                                                                               . '?' . Configure::read('app.lp_option.param') . '=' . CakeSession::read('order_option');
                            }
                        }

                        CakeSession::write('registered_user_login_url', $registered_user_login_url);

                        // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' registered_user_login_url ' . print_r($registered_user_login_url, true));

                    }
                    $is_validation_error = true;
                }
            }
        }

        if ($is_validation_error === true) {
            $this->_flowSwitch('add_email');
            return;
        }
        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
        
        $this->_flowSwitch('confirm');
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
        $kit_code = KIT_CODE_DISP_NAME_ARRAY;

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

                    if($param === 'hako_limited_ver1') {
                        // 先頭のコードのみ料金が返ってくる
                        $code = KIT_CD_HAKO_LIMITED_VER1;
                        $FirstOrderList[$code]['number']    = $value;
                        $FirstOrderList[$code]['kit_name']  = 'HAKOお片付けパック';
                        $FirstOrderList[$code]['price'] = 0;
                        $hako_limited_ver1_num = $value * 5;
                        $kit_params[] = KIT_CD_HAKO_LIMITED_VER1.':' . $hako_limited_ver1_num;
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

        // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' FirstOrderList ' . print_r($FirstOrderList, true));

        CakeSession::write('FirstOrderList', $FirstOrderList);
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
    }

    /**
     * アマゾンペイメント widgetで遷移先を指定
     * アマゾンペイメントで
     */
    public function confirm_amazon_pay()
    {

        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/add_amazon_pay', 'FirstOrder/confirm_amazon_pay'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // バリデーションエラーフラグ
        $is_validation_error = false;

        // ログインしていない場合
        $get_email = [
            'password'         => filter_input(INPUT_POST, 'password'),
            'password_confirm' => filter_input(INPUT_POST, 'password_confirm'),
            'birth'            => sprintf("%04d-%02d-%02d", filter_input(INPUT_POST, 'birth_year'), filter_input(INPUT_POST, 'birth_month'), filter_input(INPUT_POST, 'birth_day')),
            'birth_year'       => filter_input(INPUT_POST, 'birth_year'),
            'birth_month'      => filter_input(INPUT_POST, 'birth_month'),
            'birth_day'        => filter_input(INPUT_POST, 'birth_day'),
            'gender'           => filter_input(INPUT_POST, 'gender'),
            'datetime_cd'      => filter_input(INPUT_POST, 'datetime_cd'),
            'newsletter'       => filter_input(INPUT_POST, 'newsletter'),
            'alliance_cd'      => filter_input(INPUT_POST, 'alliance_cd'),
            'remember'         => filter_input(INPUT_POST, 'remember'),
        ];

        //* Session write
        CakeSession::write('Email', $get_email);

        // 重複値を削除
        CakeSession::delete('Email.datetime_cd');

        // 確認用パスワード一致チェック
        if ($get_email['password'] !== $get_email['password_confirm']) {
            $this->Flash->validation('パスワードが一致していません。ご確認ください。', ['key' => 'password_confirm']);
            $is_validation_error = true;
        }

        // メールアドレスセット
        $amazon_pay_user_info = CakeSession::read('FirstOrder.amazon_pay.user_info');

        //* Session write
        CakeSession::write('Email.email', $amazon_pay_user_info['email']);
        //バリデーション確認用に変数へ格納
        $get_email['email'] = $amazon_pay_user_info['email'];

        //*  Amazon Payから取得した住所情報の確認
        $validation = AppValid::validate($get_email);

        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->Flash->validation(INPUT_ERROR, ['key' => 'customer_address_info']);
            $is_validation_error = true;
        }

        // amazon pay 情報取得
        // 定期購入ID取得
        $amazon_billing_agreement_id = filter_input(INPUT_POST, 'amazon_billing_agreement_id');
        if($amazon_billing_agreement_id === null) {
            // 初回かリターン確認
            if(CakeSession::read('FirstOrder.amazon_pay.amazon_billing_agreement_id') != null) {
                $amazon_billing_agreement_id = CakeSession::write('FirstOrder.amazon_pay.amazon_billing_agreement_id');
            }
        }

        // 住所情報等を取得
        $this->loadModel('AmazonPayModel');
        $set_param = array();
        $set_param['amazon_billing_agreement_id'] = $amazon_billing_agreement_id;
        $set_param['address_consent_token'] = CakeSession::read('FirstOrder.amazon_pay.access_token');
        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->getBillingAgreementDetails($set_param);
        // GetBillingAgreementDetails
        if($res['ResponseStatus'] != '200') {
            // ↓AmazonPayのエラーがどのような頻度で起きるか様子見するためのログ。消さないでー！
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));
            $this->Flash->validation('Amazon Pay からの情報取得に失敗しました。再度お試し下さい。', ['key' => 'customer_amazon_pay_info']);
            $this->redirect('/first_order/add_amazon_pay');
        }

        // 有効な定期購入IDを設定
        CakeSession::write('FirstOrder.amazon_pay.amazon_billing_agreement_id', $amazon_billing_agreement_id);
        $amazon_pay_current_remaining_balance_amount = intval($res['GetBillingAgreementDetailsResult']['BillingAgreementDetails']['BillingAgreementLimits']['CurrentRemainingBalance']['Amount']);
        // 住所に関する箇所を取得
        $physicaldestination = $res['GetBillingAgreementDetailsResult']['BillingAgreementDetails']['Destination']['PhysicalDestination'];
        $physicaldestination = $this->AmazonPayModel->wrapPhysicalDestination($physicaldestination);


        //Address情報を格納する配列
        $get_address = array();
        $get_address_form = array();
        $get_address_amazon_pay = array();

        $get_address = CakeSession::read('Address');

        $get_address_form = [
            'firstname'         => filter_input(INPUT_POST, 'firstname'),
            'firstname_kana'    => filter_input(INPUT_POST, 'firstname_kana'),
            'lastname'          => filter_input(INPUT_POST, 'lastname'),
            'lastname_kana'     => filter_input(INPUT_POST, 'lastname_kana'),
        ];

        // 住所情報セット
        $PostalCode = $this->_editPostalFormat($physicaldestination['PostalCode']);
        $get_address_amazon_pay['postal']      = $PostalCode;
        $get_address_amazon_pay['pref']        = $physicaldestination['StateOrRegion'];

        $get_address_amazon_pay['address1'] = $physicaldestination['AddressLine1'];
        $get_address_amazon_pay['address2'] = $physicaldestination['AddressLine2'];
        $get_address_amazon_pay['address3'] = $physicaldestination['AddressLine3'];
        $get_address_amazon_pay['tel1']        = $physicaldestination['Phone'];
        $get_address_form['datetime_cd'] = $get_email['datetime_cd'];
        $get_address_form['select_delivery_text'] = $this->_convDatetimeCode($get_email['datetime_cd']);


        $get_address_tmp = array_merge($get_address_form, $get_address_amazon_pay);
        if (!empty($get_address))
        {
            $get_address = array_merge($get_address, $get_address_tmp);
        } else {
            $get_address = $get_address_tmp;
        }

        // 住所情報更新
        CakeSession::write('Address', $get_address);

        //*  Amazon Payから取得した住所情報の確認
        $validation = AppValid::validate($get_address_amazon_pay);
        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->Flash->validation(AMAZON_PAY_ERROR_URGING_INPUT, ['key' => 'customer_amazon_pay_info']);
            $is_validation_error = true;
        }

        //*  formから取得した住所情報の確認
        $validation = AppValid::validate($get_address_form);
        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->Flash->validation(INPUT_ERROR, ['key' => 'customer_address_info']);
            $is_validation_error = true;
        }

        // 規約同意を確認する
        $validation = AppValid::validateTermsAgree($get_email['remember']);
        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation) ) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->Flash->validation(INPUT_ERROR, ['key' => 'customer_address_info']);
            $is_validation_error = true;
        }

        if ($is_validation_error === true) {
            $this->redirect('/first_order/add_amazon_pay');
            return;
        }

        // オーダー種類を集計
        // order情報
        $Order = CakeSession::read('Order');

        $FirstOrderList = array();

        // 添字に対応するコードを設定
        $kit_code = KIT_CODE_DISP_NAME_ARRAY;

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

                    if($param === 'hako_limited_ver1') {
                        // 先頭のコードのみ料金が返ってくる
                        $code = KIT_CD_HAKO_LIMITED_VER1;
                        $FirstOrderList[$code]['number']    = $value;
                        $FirstOrderList[$code]['kit_name']  = 'HAKOお片付けパック';
                        $FirstOrderList[$code]['price'] = 0;
                        $hako_limited_ver1_num = $value * 5;
                        $kit_params[] = KIT_CD_HAKO_LIMITED_VER1.':' . $hako_limited_ver1_num;
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
            $total_kit_price = 0;
            foreach ($res->results as $key => $value) {
                $code = $value['kit_cd'];
                $FirstOrderList[$code]['price'] = number_format($value['price'] * 1);
                $total_kit_price = $total_kit_price + $value['price'];
            }
            if ($amazon_pay_current_remaining_balance_amount < $total_kit_price) {
                $this->Flash->validation('Amazon Pay の当月限度額を超えています。', ['key' => 'customer_amazon_pay_info']);
                $this->redirect('/first_order/add_amazon_pay');
            }

        }

        CakeSession::write('FirstOrderList', $FirstOrderList);
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
    }

    /**
     * スニーカーview変更
     * オーダー 会員登録
     */
    public function confirm_sneaker()
    {
        // refererは基本の初回購入フローを使用する
        $this->setAction('confirm');
        return $this->render('confirm_sneaker');
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
        $is_logined = $this->_checkLogin();
        $this->set('is_logined', $is_logined);

        // セッションが古い場合があるので再チェック
        // 発送日一覧のエラーチェック
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

            return $this->_flowSwitch('add_address');
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
            $this->_flowSwitch('add_credit');
            return;
        }

        //* 会員登録
        $data = array_merge_recursive(CakeSession::read('Address'), CakeSession::read('Email'));
        unset($data['select_delivery']);
        unset($data['select_delivery_list']);
        unset($data['select_delivery_text']);
        unset($data['select_delivery_day']);
        unset($data['select_delivery_time']);
        unset($data['select_delivery_day_list']);
        unset($data['select_delivery_time_list']);
        unset($data['cargo']);

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
            return $this->_flowSwitch('confirm');
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
                return $this->_flowSwitch('add_email');
            }
            if (strpos($res->message, 'Allow Only Entry') !== false) {
                $this->Flash->validation('登録済ユーザのため購入完了できませんでした。', ['key' => 'customer_regist_info']);
            } else {
                $this->Flash->validation($res->error_message, ['key' => 'customer_regist_info']);
            }
            return $this->_flowSwitch('confirm');
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
            return $this->_flowSwitch('confirm');
        }

        // カスタマー情報を取得しセッションに保存
        $this->Customer->setTokenAndSave($res->results[0]);
        $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);

        $this->Customer->getInfo();

        //* クレジットカード登録
        $this->loadModel(self::MODEL_NAME_SECURITY);

        $Credit = CakeSession::read('Credit');
        $Credit['card_no'] = self::_wrapConvertKana($Credit['card_no']);
        $Credit['security_cd'] = mb_convert_kana($Credit['security_cd'], 'nhk', "utf-8");

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
            return $this->_flowSwitch('confirm');
        }

        $result_security_card = $this->PaymentGMOSecurityCard->apiPost($this->PaymentGMOSecurityCard->toArray());
        if (!empty($result_security_card->error_message)) {
            $this->Flash->validation($result_security_card->error_message, ['key' => 'customer_kit_card_info']);
            return $this->_flowSwitch('confirm');
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
        $gmo_kit_card['sneaker_num']   = CakeSession::read('Order.sneaker.sneaker');
        $gmo_kit_card['starter_mono_num']      = CakeSession::read('Order.starter.starter');
        $gmo_kit_card['starter_mono_appa_num'] = CakeSession::read('Order.starter.starter');
        $gmo_kit_card['starter_mono_book_num'] = CakeSession::read('Order.starter.starter');
        // HAKOお片付けキットは１パック 5箱
        $gmo_kit_card['hako_limited_ver1_num'] = CakeSession::read('Order.hako_limited_ver1.hako_limited_ver1') * 5;
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
                    KIT_CD_STARTER_MONO_BOOK,
                    KIT_CD_HAKO_LIMITED_VER1,
                ],
            ],
            PRODUCT_CD_HAKO => [
                'kitList' => [KIT_CD_HAKO, KIT_CD_HAKO_APPAREL, KIT_CD_HAKO_BOOK],
            ],
            PRODUCT_CD_CLEANING_PACK => [
                'kitList' => [KIT_CD_CLEANING_PACK],
            ],
            PRODUCT_CD_SHOES_PACK => [
                'kitList' => [KIT_CD_SNEAKERS],
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
            KIT_CD_SNEAKERS      => 'sneaker_num',
            KIT_CD_HAKO_LIMITED_VER1     => 'hako_limited_ver1_num',
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
            return $this->_flowSwitch('confirm');
        }

        // 完了したページ情報を保存
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->_cleanFirstOrderSession();

		// アフィリエイトタグ出力用
		$this->set('customer_id', $this->Customer->data->info['customer_id']);

		/* いったん無効化
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
		*/
    }

    /**
     * スニーカーview変更
     * オーダー 会員登録
     */
    public function complete_sneaker()
    {
        // refererは基本の初回購入フローを使用する
        $this->setAction('complete');

        // スニーカーセッション情報を削除
        CakeSession::delete('order_sneaker');
        return $this->render('complete_sneaker');
    }

    /**
     * オーダー 完了
     */
    public function complete_amazon_pay()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/confirm_amazon_pay'], true) === false) {

            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // 購入前にログインし、エントリユーザでない場合のチェック
        $is_logined = $this->_checkLogin();

        $this->set('is_logined', $is_logined);

        // セッションが古い場合があるので再チェック
        // 発送日一覧のエラーチェック
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
            $this->redirect('/first_order/add_amazon_pay');
        }

        //* 会員登録
        $data = array_merge(CakeSession::read('Address'), CakeSession::read('Email'));
        unset($data['select_delivery']);
        unset($data['select_delivery_list']);
        unset($data['select_delivery_day']);
        unset($data['select_delivery_time']);
        unset($data['select_delivery_text']);
        unset($data['select_delivery_day_list']);
        unset($data['select_delivery_time_list']);
        unset($data['cargo']);

        $amazon_pay_user_info = CakeSession::read('FirstOrder.amazon_pay.user_info');
        $data['amazon_user_id'] = $amazon_pay_user_info['user_id'];
        $data['amazon_billing_agreement_id'] = CakeSession::read('FirstOrder.amazon_pay.amazon_billing_agreement_id');

        $this->loadModel(self::MODEL_NAME_REGIST_AMAZON_PAY);

        if ($is_logined) {
            $data['token'] = CakeSession::read(ApiModel::SESSION_API_TOKEN);
            $data['password'] = $this->Customer->getPassword();

            // バリデーションルールを変更
            $this->CustomerRegistInfoAmazonPay->validator()->remove('password_confirm');
        }

        $data['tel1'] = self::_wrapConvertKana($data['tel1']);

        // post値をセット
        $this->CustomerRegistInfoAmazonPay->set($data);

        //*  validation
        if (!$this->CustomerRegistInfoAmazonPay->validates()) {

            // 事前バリデーションチェック済
            $this->Flash->validation('入力情報をご確認ください', ['key' => 'customer_regist_info']);
            $this->redirect('/first_order/add_amazon_pay');
        }

        if (empty($this->CustomerRegistInfoAmazonPay->data[self::MODEL_NAME_REGIST_AMAZON_PAY]['alliance_cd'])) {
            unset($this->CustomerRegistInfoAmazonPay->data[self::MODEL_NAME_REGIST_AMAZON_PAY]['alliance_cd']);
        }

        // 本登録
        if ($is_logined) {
            $res = $this->CustomerRegistInfoAmazonPay->regist_no_oemkey();
        } else {
            // スニーカーユーザかどうか
            if (!CakeSession::read('order_sneaker')) {
                //スニーカーでない
                $res = $this->CustomerRegistInfoAmazonPay->regist();
            } else {
                // スニーカーユーザかどうか
                $res = $this->CustomerRegistInfoAmazonPay->regist_sneakers();
            }
        }

        if (!empty($res->error_message)) {
            // 紹介コードエラーの場合 紹介コード入力に遷移
            if (strpos($res->message, 'alliance_cd') !== false) {
                $this->Flash->validation($res->error_message, ['key' => 'alliance_cd']);
                $this->redirect('/first_order/add_amazon_pay');
            }
            if (strpos($res->message, 'Allow Only Entry') !== false) {
                $this->Flash->validation('登録済ユーザのため購入完了できませんでした。', ['key' => 'customer_regist_info']);
            } else {
                $this->Flash->validation($res->error_message, ['key' => 'customer_regist_info']);
            }
            $this->redirect('/first_order/add_amazon_pay');
        }

        // ログイン
        $this->loadModel('CustomerLoginAmazonPay');

        $amazon_pay_user_info = CakeSession::read('FirstOrder.amazon_pay.user_info');
        $this->CustomerLoginAmazonPay->data['CustomerLoginAmazonPay']['amazon_user_id'] = $amazon_pay_user_info['user_id'];
        $this->CustomerLoginAmazonPay->data['CustomerLoginAmazonPay']['access_token'] = CakeSession::read('FirstOrder.amazon_pay.access_token');

        if ($is_logined) {
            // エントリユーザ切り替え再度ログイン
            $this->Customer->switchEntryToCustomer();
        }

        // ログイン処理
        $res = $this->CustomerLoginAmazonPay->login();

        if (!empty($res->error_message)) {
            $this->Flash->validation($res->error_message, ['key' => 'customer_regist_info']);
            $this->redirect('/first_order/add_amazon_pay');
        }

        // Amazon Pay ログイン情報を保持
        CakeSession::write('login.amazon_pay.user_info', CakeSession::read('FirstOrder.amazon_pay.user_info'));

        // カスタマー情報を取得しセッションに保存
        $this->Customer->setTokenAndSave($res->results[0]);
        $this->Customer->setPassword(CakeSession::read('Email.password'));

        $this->Customer->getInfo();

        // AmazonPay 定期購入確定処理 会員登録で確定時にBAIDを確定させる
        $this->loadModel('AmazonPayModel');
        $set_param = array();
        $set_param['merchant_id'] = Configure::read('app.amazon_pay.merchant_id');
        $set_param['amazon_billing_agreement_id'] = CakeSession::read('FirstOrder.amazon_pay.amazon_billing_agreement_id');
        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->setConfirmBillingAgreement($set_param);
        // GetBillingAgreementDetails
        if($res['ResponseStatus'] != '200') {
            // カードの問題エラー CODE BillingAgreementConstraintsExist constraints PaymentMethodNotAllowed and cannot be confirmed.
            // チェックがないエラー CODE BillingAgreementConstraintsExist constraints BuyerConsentNotSet and cannot be confirmed.
            // ↓AmazonPayのエラーがどのような頻度で起きるか様子見するためのログ。消さないでー！
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res setConfirmBillingAgreement ' . print_r($res, true));
            $this->Flash->validation(AMAZON_PAY_ERROR_PAYMENT_FAILURE, ['key' => 'customer_amazon_pay_info']);
            $this->redirect('/first_order/add_amazon_pay');
        }

        // 定期購入ID確定
        CakeSession::read('FirstOrder.amazon_pay.confirm_billing_agreement', true);

        // 購入
        $this->loadModel('PaymentAmazonKitAmazonPay');
        $amazon_kit_pay = array();
        $amazon_kit_pay['mono_num']      = CakeSession::read('Order.mono.mono');
        $amazon_kit_pay['mono_appa_num'] = CakeSession::read('Order.mono.mono_apparel');
        $amazon_kit_pay['mono_book_num'] = CakeSession::read('Order.mono.mono_book');
        $amazon_kit_pay['hako_num']      = CakeSession::read('Order.hako.hako');
        $amazon_kit_pay['hako_appa_num'] = CakeSession::read('Order.hako.hako_apparel');
        $amazon_kit_pay['hako_book_num'] = CakeSession::read('Order.hako.hako_book');
        $amazon_kit_pay['cleaning_num']  = CakeSession::read('Order.cleaning.cleaning');
        $amazon_kit_pay['sneaker_num']   = CakeSession::read('Order.sneaker.sneaker');
        $amazon_kit_pay['starter_mono_num']      = CakeSession::read('Order.starter.starter');
        $amazon_kit_pay['starter_mono_appa_num'] = CakeSession::read('Order.starter.starter');
        $amazon_kit_pay['starter_mono_book_num'] = CakeSession::read('Order.starter.starter');
        // HAKOお片付けキットは１パック 5箱
        $amazon_kit_pay['hako_limited_ver1_num'] = CakeSession::read('Order.hako_limited_ver1.hako_limited_ver1') * 5;
        $amazon_kit_pay['address']       = CakeSession::read('Address.pref') . CakeSession::read('Address.address1') . CakeSession::read('Address.address2') . '　' .  CakeSession::read('Address.address3');

        $amazon_kit_pay['access_token']     = CakeSession::read('FirstOrder.amazon_pay.access_token');
        $amazon_kit_pay['amazon_user_id']   = $amazon_pay_user_info['user_id'];
        $amazon_kit_pay['amazon_billing_agreement_id'] = CakeSession::read('FirstOrder.amazon_pay.amazon_billing_agreement_id');
        $amazon_kit_pay['name']             = CakeSession::read('Address.lastname') . '　' . CakeSession::read('Address.firstname');
        $amazon_kit_pay['tel1']             = self::_wrapConvertKana(CakeSession::read('Address.tel1'));
        $amazon_kit_pay['postal']           = CakeSession::read('Address.postal');
        $amazon_kit_pay['datetime_cd']      = CakeSession::read('Address.datetime_cd');

        $productKitList = [
            PRODUCT_CD_MONO => [
                'kitList' => [
                    KIT_CD_MONO,
                    KIT_CD_MONO_APPAREL,
                    KIT_CD_MONO_BOOK,
                    KIT_CD_STARTER_MONO,
                    KIT_CD_STARTER_MONO_APPAREL,
                    KIT_CD_STARTER_MONO_BOOK,
                    KIT_CD_HAKO_LIMITED_VER1,
                ],
            ],
            PRODUCT_CD_HAKO => [
                'kitList' => [KIT_CD_HAKO, KIT_CD_HAKO_APPAREL, KIT_CD_HAKO_BOOK],
            ],
            PRODUCT_CD_CLEANING_PACK => [
                'kitList' => [KIT_CD_CLEANING_PACK],
            ],
            PRODUCT_CD_SHOES_PACK => [
                'kitList' => [KIT_CD_SNEAKERS],
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
            KIT_CD_SNEAKERS      => 'sneaker_num',
            KIT_CD_HAKO_LIMITED_VER1     => 'hako_limited_ver1_num',
        ];
        $kit_params = [];
        foreach ($productKitList as $product) {
            // 個数集計
            foreach ($product['kitList'] as $kitCd) {
                $num = $amazon_kit_pay[$dataKeyNum[$kitCd]];
                if (!empty($num)) {
                    $kit_params[] = $kitCd . ':' . $num;
                }
            }
        }

        $amazon_kit_pay['kit'] = implode(',', $kit_params);

        $this->PaymentAmazonKitAmazonPay->set($amazon_kit_pay);

        $result_kit_amazon_pay = $this->PaymentAmazonKitAmazonPay->apiPost($this->PaymentAmazonKitAmazonPay->toArray());

        if ($result_kit_amazon_pay->status !== '1') {
            if ($result_kit_amazon_pay->http_code === 400) {
                $this->Flash->validation('キット購入エラー', ['key' => 'customer_kit_info']);
            } else {
                $this->Flash->validation($result_kit_amazon_pay->message, ['key' => 'customer_kit_info']);
            }
            $this->redirect(['controller' => 'first_order', 'action' => 'add_amazon_pay']);
        }

        // 完了したページ情報を保存
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->_cleanFirstOrderSession();

        // アフィリエイトタグ出力用
        $this->set('customer_id', $this->Customer->data->info['customer_id']);

        /* いったん無効化
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
        */
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
     * ajax 指定IDの配送日時情報取得 amazon pay
     */
    public function as_get_address_datetime_by_amazon()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        // 画面描画しない
        $this->autoRender = false;

        $postal = "";

        $amazon_billing_agreement_id  = filter_input(INPUT_POST, 'amazon_billing_agreement_id');
        if($amazon_billing_agreement_id === null) {
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' none amazon_billing_agreement_id ' . print_r($amazon_billing_agreement_id, true));
            return json_encode(['status' => false]);
        }

        // モデルロード
        $this->loadModel('AmazonPayModel');

        $set_param = array();
        $set_param['amazon_billing_agreement_id'] = $amazon_billing_agreement_id;
        $set_param['address_consent_token'] = CakeSession::read('FirstOrder.amazon_pay.access_token');
        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');
        $res = $this->AmazonPayModel->getBillingAgreementDetails($set_param);
        // GetBillingAgreementDetails
        if($res['ResponseStatus'] != '200') {
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' getBillingAgreementDetails ' . print_r($res, true));
            return json_encode(['status' => false]);
        }

        if(!isset($res['GetBillingAgreementDetailsResult']['BillingAgreementDetails']['Destination']['PhysicalDestination']['PostalCode'])) {
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' none PostalCode ' . print_r($res['GetBillingAgreementDetailsResult']['BillingAgreementDetails']['Destination']['PhysicalDestination']['PostalCode'], true));
            return json_encode(['status' => false]);
        }

        $postal = $res['GetBillingAgreementDetailsResult']['BillingAgreementDetails']['Destination']['PhysicalDestination']['PostalCode'];

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
     * kit box mono 箱数をset
     */
    private function _setMonoOrder($Order)
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
     * kit box mono 箱数をset
     */
    private function _setMonoOrderByGet($Order)
    {
        $params = array(
            'mono'          => filter_input(INPUT_GET, 'mono'),
            'mono_apparel'  => filter_input(INPUT_GET, 'mono_apparel'),
            'mono_book'     => filter_input(INPUT_GET, 'mono_book'),
        );
        $Order['mono'] = $params;
        return $Order;
    }

    /**
     * kit box hako 箱数をset
     */
    private function _setHakoOrder($Order)
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
     * kit box hako 箱数をset
     */
    private function _setHakoOrderByGet($Order)
    {
        $params = array(
            'hako'          => (int)filter_input(INPUT_GET, 'hako'),
            'hako_apparel'  => (int)filter_input(INPUT_GET, 'hako_apparel'),
            'hako_book'     => (int)filter_input(INPUT_GET, 'hako_book'),
        );
        $Order['hako'] = $params;
        return $Order;
    }

    /**
     * kit box cleaning 箱数をset
     */
    private function _setCleaningOrder($Order)
    {
        $Order['cleaning']['cleaning'] = (int)filter_input(INPUT_POST, 'cleaning');
        return $Order;
    }

    /**
     * kit box cleaning 箱数をset
     */
    private function _setCleaningOrderByGet($Order)
    {
        $Order['cleaning']['cleaning'] = (int)filter_input(INPUT_GET, 'cleaning');
        return $Order;
    }

    /**
     * kit box sneaker set
     */
    private function _setSneakerOrder($Order)
    {
        $Order['sneaker']['sneaker'] = (int)filter_input(INPUT_POST, 'sneaker');
        return $Order;
    }

    /**
     * kit box starter set
     */
    private function _setStarterOrder($Order)
    {
        $Order['starter']['starter'] = (int)filter_input(INPUT_POST, 'starter');
        return $Order;
    }

    /**
     * kit box starter set
     */
    private function _setStarterOrderByGet($Order)
    {
        $Order['starter']['starter'] = (int)filter_input(INPUT_POST, 'starter');
        return $Order;
    }

    /**
     * kit box starter set
     */
    private function _setHakoLimitedVer1Order($Order)
    {
        $Order['hako_limited_ver1']['hako_limited_ver1'] = (int)filter_input(INPUT_POST, 'hako_limited_ver1');
        return $Order;
    }

    /**
     * kit box starter set
     */
    private function _setHakoLimitedVer1OrderByGet($Order)
    {
        $Order['hako_limited_ver1']['hako_limited_ver1'] = (int)filter_input(INPUT_POST, 'hako_limited_ver1');
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
     * フローを変更スイッチ
     * 遷移先メッソドを指定し、スニーカの場合_sneakerメソッドへ遷移させる
     */
    private function _flowSwitch($base_method)
    {
        $set_method = $base_method;

        // スニーカー判定
        if (CakeSession::read('order_sneaker')) {
            // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' sneaker ');
            $set_method = $base_method . '_sneaker';
        }

        $this->redirect(['controller' => 'first_order', 'action' => $set_method]);

    }

    // 日付CD変換
    private function _convDatetimeCode ( $data_code ){

        // 時間CODE変換表
        $timeList = array( 2 => '午前中',
            //3 => '12～14時',
            4 => '14～16時',
            5 => '16～18時',
            6 => '18～20時',
            7 => '19～21時' );


        // 日付
        $date = substr( $data_code, 0, 10 );

        // 時間
        $time = substr( $data_code, 11, 1 );

        // 戻り値
        $datetime = date( "Y年m月d日", strtotime( $date ) );

        if( isset( $timeList[$time] )  ) $datetime .= ' '.$timeList[$time];
        return $datetime;
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
        CakeSession::delete('FirstOrder');

    }



}
