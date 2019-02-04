<?php
App::uses('AppValid', 'Lib');
App::uses('MinikuraController', 'Controller');
App::uses('KitDeliveryDatetime', 'Model');
App::uses('EmailModel', 'Model');
App::uses('InboundDirect', 'Model');
App::uses('InboundDirectYamato', 'Model');
App::uses('AmazonPayModel', 'Model');
App::uses('AppCode', 'Lib');
App::uses('PickupDate', 'Model');
App::uses('PickupTime', 'Model');
App::uses('PickupController', 'Controller');
App::uses('PickupYamatoDateTime', 'Model');

class FirstOrderDirectInboundController extends MinikuraController
{
    // アクセス許可
    protected $checkLogined = false;
    const MODEL_NAME_REGIST = 'CustomerRegistInfo';
    const MODEL_NAME_REGIST_AMAZON_PAY = 'CustomerRegistInfoAmazonPay';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        // #20216 ダイレクト終了
        new AppTerminalError(AppE::NOT_FOUND, 404);

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

        // 初期化
        CakeSession::write('FirstOrderDirectInbound.display.standard', false);

        // 紹介コードで遷移してきた場合
        $code = filter_input(INPUT_GET, Configure::read('app.lp_code.param'));
        if (!is_null($code)) {
            // オプションコードが含まれるか?
            if (strpos($code,'?' . Configure::read('app.lp_option.param')) !== false) {
                list($code, $pram_option) = explode('?', $code);
                list($label, $option) = explode('=', $pram_option);
                CakeSession::write('order_option', $option);
            }
        }

        // スニーカー判定 keyがあれば空白なければnull
        if (filter_input(INPUT_GET, Configure::read('app.sneaker_option.param')) === '') {
            // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' key sneaker ');
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);

        }
        // 紹介コードが sneakers の場合
        if ($code === Configure::read('api.sneakers.alliance_cd')) {
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

                // ログイン済みスニーカーユーザ エントリーユーザ 初回購入フローへ
                $this->redirect(['controller' => 'first_order', 'action' => 'index']);
            }

            // スニーカコードの場合 コードオプションを削除する
            if ($code === Configure::read('api.sneakers.alliance_cd')) {
                CakeSession::delete('order_option');
                CakeSession::delete('order_code');
                $this->redirect(['controller' => 'first_order', 'action' => 'index']);
            }

            // ログイン済みエントリーユーザ 初回購入フローへ
            $this->redirect(['controller' => 'first_order_direct_inbound', 'action' => 'add_address']);
        }

        // 初回購入フロー
        $this->redirect(['controller' => 'first_order_direct_inbound', 'action' => 'add_address']);
    }

    /**
     * ユーザ名 住所の登録
     */
    public function add_address()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/add_order', 'FirstOrderDirectInbound/index', 'FirstOrderDirectInbound/add_address', 'FirstOrderDirectInbound/add_email', 'FirstOrderDirectInbound/confirm','FirstOrderDirectInbound/complete'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // ログインチェック
        $this->_checkLogin();

        $back  = filter_input(INPUT_GET, 'back');
        
        if (!$back) {
            // Addressリセット
            if (empty(CakeSession::read('Address.cargo'))) {

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
                    'date_cd'         => "",
                    'time_cd'        => "",
                    'cargo'       => "ヤマト運輸",
                );

                // お届け希望日のリスト
                CakeSession::write('Address', $Address);

                // 初期化
                CakeSession::write('FirstOrderDirectInbound.display.standard', 'false');

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
     * アマゾンペイメント widgetで遷移先を指定
     * アマゾンペイメントでアカウント情報を取得
     */
    public function add_amazon_profile()
    {

        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/confirm_order', 'FirstOrder/add_order', 'FirstOrderDirectInbound/add_address'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order_direct_inbound', 'action' => 'index']);
        }

        // 標準入力項目非表示
        CakeSession::write('FirstOrderDirectInbound.display.standard', 'false');

        // アクセストークンを取得
        $access_token = filter_input(INPUT_GET, 'access_token');
        if($access_token === null) {
            $this->Flash->validation('Amazonアカウントでお支払い ログインエラー', ['key' => 'amazon_pay_access_token']);
            $this->redirect(['controller' => 'first_order_direct_inbound', 'action' => 'add_order']);
        }

        //預け入れ個数取得
        $set_direct_inbound = array();
        $set_direct_inbound['direct_inbound']['direct_inbound'] = (int)filter_input(INPUT_GET, 'direct_inbound');
        $direct_inbound = $set_direct_inbound['direct_inbound'];

        // FirstOrderと階層を合わせる
        CakeSession::write('Order', $set_direct_inbound);


        CakeSession::write('FirstOrder.amazon_pay.access_token', $access_token);

        $this->loadModel('AmazonPayModel');
        $res = $this->AmazonPayModel->getUserInfo($access_token);

        // 情報が取得できているか確認
        if(!isset($res['name']) || !isset($res['user_id']) || !isset($res['email'])) {
            $this->Flash->validation('Amazonアカウントでお支払い アカウント情報エラー', ['key' => 'amazon_pay_access_token']);
            $this->redirect(['controller' => 'first_order_direct_inbound', 'action' => 'add_order']);
        }

        if(($res['name'] === '') || ($res['user_id'] === '') || ($res['email'] === '')) {
            $this->Flash->validation('Amazonアカウントでお支払い アカウント情報エラー', ['key' => 'amazon_pay_access_token']);
            $this->redirect(['controller' => 'first_order_direct_inbound', 'action' => 'add_order']);
        }

        CakeSession::write('FirstOrderDirectInbound.amazon_pay.user_info', $res);

        // access_tokenを保持
        CakeSession::write('login.amazon_pay.access_token', $access_token);

        // amazon_user_idを保持
        CakeSession::write('login.amazon_pay.amazon_user_id', $res['user_id']);

        $is_validation_error = false;

        //*  validation 基本は共通クラスのAppValidで行う
        $validation = AppValid::validate($direct_inbound);

                //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->redirect(['controller' => 'first_order_direct_inbound', 'action' => 'add_address']);
            return;
        }

        // email確認
        // アマゾンペイメントメールエラー処理
        CakeSession::delete('registered_user_login_url');

        if ($is_validation_error !== true) {
            // amazonユーザ情報取得
            $amazon_pay_user_info = CakeSession::read('FirstOrderDirectInbound.amazon_pay.user_info');

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

        // パラメータを引き継ぐ
        //$set_url = str_replace('add_amazon_profile', 'add_amazon_pay', $_SERVER["REQUEST_URI"]);

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        //$this->redirect($set_url);
        $this->redirect('/first_order_direct_inbound/add_amazon_pay');
    }

    /**
     * アマゾンペイメント widgetで遷移先を指定
     * アマゾンペイメントで
     */
    public function add_amazon_pay()
    {

        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrderDirectInbound/add_amazon_profile', 'FirstOrderDirectInbound/add_amazon_pay', 'FirstOrderDirectInbound/confirm_amazon_pay'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order_direct_inbound', 'action' => 'index']);
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
     * アマゾンペイメント widgetで遷移先を指定
     */
    public function confirm_amazon_pay()
    {
        if (CakeSession::read('FirstOrderDirectInbound.regist_user_complete') === true) {
            return $this->_confirm_amazon_pay_irregular();
        }
        $this->_confirm_amazon_pay();
    }


    /**
     * アマゾンペイメント widgetで遷移先を指定(正常系)
     */
    private function _confirm_amazon_pay()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrderDirectInbound/add_amazon_pay', 'FirstOrderDirectInbound/confirm_amazon_pay'], true) === false) {

            //* NG redirect
            $this->redirect(['controller' => 'first_order_direct_inbound', 'action' => 'index']);
        }

        // バリデーションエラーフラグ
        $is_validation_error = false;

        // パスワードをランダムで生成する
        $password = uniqid();
        $password_confirm = $password;

        $params_email = [
            'password'         => $password,
            'password_confirm' => $password_confirm,
            'birth'            => CUSTOMER_DEFAULT_BIRTH,
            'birth_year'       => CUSTOMER_DEFAULT_BIRTH_YEAR,
            'birth_month'      => CUSTOMER_DEFAULT_BIRTH_MONTH,
            'birth_day'        => CUSTOMER_DEFAULT_BIRTH_DAY,
            'gender'           => CUSTOMER_DEFAULT_GENDER,
            'newsletter'       => filter_input(INPUT_POST, 'newsletter'),
            'alliance_cd'      => filter_input(INPUT_POST, 'alliance_cd'),
            'remember'         => filter_input(INPUT_POST, 'remember'),
        ];

        //* Session write
        CakeSession::write('Email', $params_email);

        // メールアドレスセット
        $amazon_pay_user_info = CakeSession::read('FirstOrderDirectInbound.amazon_pay.user_info');
        //* Session write
        CakeSession::write('Email.email', $amazon_pay_user_info['email']);

        // 確認用パスワード一致チェック
        if ($password !== $password_confirm) {
            $this->Flash->validation('パスワードが一致していません。ご確認ください。', ['key' => 'password_confirm']);
            $is_validation_error = true;
        }

        //*  validation 基本は共通クラスのAppValidで行う
        $validation = AppValid::validate($params_email);

        //* バリデーションでエラーあったらメッセージセット
        // 入力内容確認
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->Flash->validation(INPUT_ERROR, ['key' => 'customer_address_info']);
            $is_validation_error = true;
        }

        // amazon pay 情報取得
        // 定期購入ID取得
        $amazon_billing_agreement_id = "";
        $amazon_billing_agreement_id = filter_input(INPUT_POST, 'amazon_billing_agreement_id');

        if($amazon_billing_agreement_id === null) {
            // 初回かリターン確認
            if(CakeSession::read('FirstOrderDirectInbound.amazon_pay.amazon_billing_agreement_id') != null) {
                CakeSession::write('FirstOrderDirectInbound.amazon_pay.amazon_billing_agreement_id', $amazon_billing_agreement_id);
            }
        }

        // アクセストークンを取得
        //$access_token = filter_input(INPUT_GET, 'access_token');
        $access_token = CakeSession::read('FirstOrder.amazon_pay.access_token');
        if($access_token === null) {

        }

        // 住所情報等を取得
        $this->loadModel('AmazonPayModel');
        $set_param = array();
        $set_param['amazon_billing_agreement_id'] = $amazon_billing_agreement_id;
        $set_param['address_consent_token'] = $access_token;
        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->getBillingAgreementDetails($set_param);

        // GetBillingAgreementDetails
        if($res['ResponseStatus'] != '200') {
            // ↓AmazonPayのエラーがどのような頻度で起きるか様子見するためのログ。消さないでー！
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));
            $this->Flash->validation('Amazon Pay からの情報取得に失敗しました。再度お試し下さい。', ['key' => 'customer_amazon_pay_info']);
            $this->redirect('/first_order_direct_inbound/add_amazon_pay');
        }

        // 有効な定期購入IDを設定
        CakeSession::write('FirstOrderDirectInbound.amazon_pay.amazon_billing_agreement_id', $amazon_billing_agreement_id);
        $amazon_pay_current_remaining_balance_amount = intval($res['GetBillingAgreementDetailsResult']['BillingAgreementDetails']['BillingAgreementLimits']['CurrentRemainingBalance']['Amount']);
        // 住所に関する箇所を取得
        $physicaldestination = $res['GetBillingAgreementDetailsResult']['BillingAgreementDetails']['Destination']['PhysicalDestination'];
        $physicaldestination = $this->AmazonPayModel->wrapPhysicalDestination($physicaldestination);


        $params_address = array();
        $params_address_form = array();
        $params_address_amazon_pay = array();

        $params_address = CakeSession::read('Address');

        $params_address_form = [
            'firstname'             => filter_input(INPUT_POST, 'firstname'),
            'firstname_kana'        => filter_input(INPUT_POST, 'firstname_kana'),
            'lastname'              => filter_input(INPUT_POST, 'lastname'),
            'lastname_kana'         => filter_input(INPUT_POST, 'lastname_kana'),
            'date_cd'               => filter_input(INPUT_POST, 'date_cd'),
            'time_cd'               => filter_input(INPUT_POST, 'time_cd'),
            'cargo'                 => filter_input(INPUT_POST, 'cargo'),
        ];

        // 住所情報セット
        $PostalCode = $this->_editPostalFormat($physicaldestination['PostalCode']);
        $params_address_amazon_pay['postal']      = $PostalCode;
        $params_address_amazon_pay['pref']        = $physicaldestination['StateOrRegion'];

        $params_address_amazon_pay['address1'] = $physicaldestination['AddressLine1'];
        $params_address_amazon_pay['address2'] = $physicaldestination['AddressLine2'];
        $params_address_amazon_pay['address3'] = $physicaldestination['AddressLine3'];
        $params_address_amazon_pay['tel1']     = $physicaldestination['Phone'];

        // 集荷日時
        $params_address_form['select_delivery_text'] = date('Y年m月d日', strtotime($params_address_form['date_cd'])).PickupController::_getWeek($params_address_form['date_cd']);
        $time_text = PickupController::getTimeText();
        $params_address_form['select_delivery_text'] .=  ' ' . $time_text[$params_address_form['time_cd']];

        $params_address_tmp = array_merge($params_address_form, $params_address_amazon_pay);
        if (!empty($params_address))
        {
            $params_address = array_merge($params_address, $params_address_tmp);
        } else {
            $params_address = $params_address_tmp;
        }

        CakeSession::write('Address', $params_address);

        //*  validation 基本は共通クラスのAppValidで行う
        //　入力情報の確認
        $validation = AppValid::validate($params_address_form);

        //* 共通バリデーションでエラーあったらメッセージセット
        //amazon pay から取ってきた情報の確認
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->Flash->validation(INPUT_ERROR, ['key' => 'customer_address_info']);
            $is_validation_error = true;
        }

        //*  validation 基本は共通クラスのAppValidで行う
        //　入力情報の確認
        $validation = AppValid::validate($params_address_amazon_pay);

        //* 共通バリデーションでエラーあったらメッセージセット
        //amazon pay から取ってきた情報の確認
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->Flash->validation(AMAZON_PAY_ERROR_URGING_INPUT, ['key' => 'customer_amazon_pay_info']);
            $is_validation_error = true;
        }

        // 規約同意を確認する
        $validation = AppValid::validateTermsAgree($params_email['remember']);

        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation) ) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->Flash->validation(INPUT_ERROR, ['key' => 'customer_address_info']);
            $is_validation_error = true;
        }

        if ($is_validation_error === true) {
            $this->redirect('/first_order_direct_inbound/add_amazon_pay');
            return;
        }

        // 前処理の不要なセッションを削除
        CakeSession::delete('registered_user_login_url');

        // オーダー種類を集計
        // order情報
        $Order = CakeSession::read('Order');

        $FirstOrderList = array();
        // 添字に対応するコードを設定
        //
        $FirstOrderList['direct_inbound']['number']    = $Order['direct_inbound']['direct_inbound'];
        $FirstOrderList['direct_inbound']['kit_name']  = 'minikuraダイレクト';
        $FirstOrderList['direct_inbound']['price'] = 0;
        $FirstOrderList['storage_fee']['number']    = $Order['direct_inbound']['direct_inbound'];
        $FirstOrderList['storage_fee']['kit_name']  = '月額保管料（250円）';
        $FirstOrderList['storage_fee']['price'] = $Order['direct_inbound']['direct_inbound'] * 250;
        $FirstOrderList['shipping_fee']['number']    = '';
        $FirstOrderList['shipping_fee']['kit_name']  = '預け入れ送料';
        $FirstOrderList['shipping_fee']['price'] = 0;

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        CakeSession::write('FirstOrderList', $FirstOrderList);
    }

    /**
     * アマゾンペイメント widgetで遷移先を指定(アマペイ決済失敗時)
     */
    private function _confirm_amazon_pay_irregular()
    {
        // バリデーションエラーフラグ
        $is_validation_error = false;

        // ログイン会員情報を取得
        $user_info = $this->Customer->getInfo();

        // メールアドレスセット
        $amazon_pay_user_info = CakeSession::read('FirstOrderDirectInbound.amazon_pay.user_info');
        //* Session write
        CakeSession::write('Email.email', $amazon_pay_user_info['email']);

        // amazon pay 情報取得
        // 定期購入ID取得
        $amazon_billing_agreement_id = "";
        $amazon_billing_agreement_id = filter_input(INPUT_POST, 'amazon_billing_agreement_id');

        if($amazon_billing_agreement_id === null) {
            // 初回かリターン確認
            if(CakeSession::read('FirstOrderDirectInbound.amazon_pay.amazon_billing_agreement_id') != null) {
                CakeSession::write('FirstOrderDirectInbound.amazon_pay.amazon_billing_agreement_id', $amazon_billing_agreement_id);
            }
        }

        // アクセストークンを取得
        $access_token = CakeSession::read('FirstOrder.amazon_pay.access_token');

        // 住所情報等を取得
        $this->loadModel('AmazonPayModel');
        $set_param = array();
        $set_param['amazon_billing_agreement_id'] = $amazon_billing_agreement_id;
        $set_param['address_consent_token'] = $access_token;
        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->getBillingAgreementDetails($set_param);

        // GetBillingAgreementDetails
        if($res['ResponseStatus'] != '200') {
            // ↓AmazonPayのエラーがどのような頻度で起きるか様子見するためのログ。消さないでー！
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));
            $this->Flash->validation('Amazon Pay からの情報取得に失敗しました。再度お試し下さい。', ['key' => 'customer_amazon_pay_info']);
            $this->redirect('/first_order_direct_inbound/add_amazon_pay');
        }

        // 有効な定期購入IDを設定
        CakeSession::write('FirstOrderDirectInbound.amazon_pay.amazon_billing_agreement_id', $amazon_billing_agreement_id);
        $amazon_pay_current_remaining_balance_amount = intval($res['GetBillingAgreementDetailsResult']['BillingAgreementDetails']['BillingAgreementLimits']['CurrentRemainingBalance']['Amount']);
        // 住所に関する箇所を取得
        $physicaldestination = $res['GetBillingAgreementDetailsResult']['BillingAgreementDetails']['Destination']['PhysicalDestination'];
        $physicaldestination = $this->AmazonPayModel->wrapPhysicalDestination($physicaldestination);


        $params_address = array();
        $params_address_form = array();
        $params_address_amazon_pay = array();

        $params_address = CakeSession::read('Address');

        $params_address_form = [
            'firstname'             => $user_info['firstname'],
            'firstname_kana'        => $user_info['firstname_kana'],
            'lastname'              => $user_info['lastname'],
            'lastname_kana'         => $user_info['lastname_kana'],
            'date_cd'               => filter_input(INPUT_POST, 'date_cd'),
            'time_cd'               => filter_input(INPUT_POST, 'time_cd'),
            'cargo'                 => filter_input(INPUT_POST, 'cargo'),
        ];

        // 住所情報セット
        $PostalCode = $this->_editPostalFormat($physicaldestination['PostalCode']);
        $params_address_amazon_pay['postal']      = $PostalCode;
        $params_address_amazon_pay['pref']        = $physicaldestination['StateOrRegion'];

        $params_address_amazon_pay['address1'] = $physicaldestination['AddressLine1'];
        $params_address_amazon_pay['address2'] = $physicaldestination['AddressLine2'];
        $params_address_amazon_pay['address3'] = $physicaldestination['AddressLine3'];
        $params_address_amazon_pay['tel1']     = $physicaldestination['Phone'];

        // 集荷日時
        $params_address_form['select_delivery_text'] = date('Y年m月d日', strtotime($params_address_form['date_cd'])).PickupController::_getWeek($params_address_form['date_cd']);
        $time_text = PickupController::getTimeText();
        $params_address_form['select_delivery_text'] .=  ' ' . $time_text[$params_address_form['time_cd']];

        $params_address_tmp = array_merge($params_address_form, $params_address_amazon_pay);
        if (!empty($params_address))
        {
            $params_address = array_merge($params_address, $params_address_tmp);
        } else {
            $params_address = $params_address_tmp;
        }

        CakeSession::write('Address', $params_address);

        //*  validation 基本は共通クラスのAppValidで行う
        //　入力情報の確認
        $validation = AppValid::validate($params_address_form);

        //* 共通バリデーションでエラーあったらメッセージセット
        //amazon pay から取ってきた情報の確認
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->Flash->validation(INPUT_ERROR, ['key' => 'customer_address_info']);
            $is_validation_error = true;
        }

        //*  validation 基本は共通クラスのAppValidで行う
        //　入力情報の確認
        $validation = AppValid::validate($params_address_amazon_pay);

        //* 共通バリデーションでエラーあったらメッセージセット
        //amazon pay から取ってきた情報の確認
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->Flash->validation(AMAZON_PAY_ERROR_URGING_INPUT, ['key' => 'customer_amazon_pay_info']);
            $is_validation_error = true;
        }

        if ($is_validation_error === true) {
            $this->redirect('/first_order_direct_inbound/add_amazon_pay');
            return;
        }

        // 前処理の不要なセッションを削除
        CakeSession::delete('registered_user_login_url');

        // オーダー種類を集計
        // order情報
        $Order = CakeSession::read('Order');

        $FirstOrderList = array();
        // 添字に対応するコードを設定
        //
        $FirstOrderList['direct_inbound']['number']    = $Order['direct_inbound']['direct_inbound'];
        $FirstOrderList['direct_inbound']['kit_name']  = 'minikuraダイレクト';
        $FirstOrderList['direct_inbound']['price'] = 0;
        $FirstOrderList['storage_fee']['number']    = $Order['direct_inbound']['direct_inbound'];
        $FirstOrderList['storage_fee']['kit_name']  = '月額保管料（250円）';
        $FirstOrderList['storage_fee']['price'] = $Order['direct_inbound']['direct_inbound'] * 250;
        $FirstOrderList['shipping_fee']['number']    = '';
        $FirstOrderList['shipping_fee']['kit_name']  = '預け入れ送料';
        $FirstOrderList['shipping_fee']['price'] = 0;

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        CakeSession::write('FirstOrderList', $FirstOrderList);
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

        // 標準入力項目表示
        CakeSession::write('FirstOrderDirectInbound.display.standard', 'true');

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
            'date_cd'            => filter_input(INPUT_POST, 'date_cd'),
            'time_cd'           => filter_input(INPUT_POST, 'time_cd'),
            'cargo'             => filter_input(INPUT_POST, 'cargo'),
        ];

        // 集荷日時
        $params['select_delivery_text'] = date('Y年m月d日', strtotime($params['date_cd'])).PickupController::_getWeek($params['date_cd']);
        $time_text = PickupController::getTimeText();
        $params['select_delivery_text'] .=  ' ' . $time_text[$params['time_cd']];

        CakeSession::write('Address', $params);

        $params['tel1'] = self::_wrapConvertKana($params['tel1']);

        if ($params['cargo'] === "着払い") {
            unset($params['date_cd']);
            unset($params['time_cd']);
        }

        $validation_params = array_merge($order_params,$params);

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
        
        $this->redirect('add_email');
    }

    /**
     * メールアドレス 登録
     */
    public function add_email()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrderDirectInbound/confirm_address', 'FirstOrderDirectInbound/add_email', 'FirstOrderDirectInbound/add_credit','FirstOrderDirectInbound/confirm'], true) === false) {
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

        $display_alliance_cd = true;
/*        // スターターキットの場合 非表示
        if (CakeSession::read('kit_select_type') === 'starter_kit') {
            $display_alliance_cd = false;
            // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' display_alliance_cd is starter_kit');
        }
        // hako5個パックが1個以上選択されている場合 非表示
        if (CakeSession::read('Order.hako_limited_ver1.hako_limited_ver1') > 0) {
            $display_alliance_cd = false;
        }
*/
        $this->set('display_alliance_cd', $display_alliance_cd);

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
    }


    /**
     * メールアドレス 確認
     */
    public function confirm_email()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrderDirectInbound/add_email', 'FirstOrderDirectInbound/add_credit'], true) === false) {
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
                'birth'            => CUSTOMER_DEFAULT_BIRTH,
                'birth_year'       => CUSTOMER_DEFAULT_BIRTH_YEAR,
                'birth_month'      => CUSTOMER_DEFAULT_BIRTH_MONTH,
                'birth_day'        => CUSTOMER_DEFAULT_BIRTH_DAY,
                'gender'           => CUSTOMER_DEFAULT_GENDER,
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
                'birth'            => CUSTOMER_DEFAULT_BIRTH,
                'birth_year'       => CUSTOMER_DEFAULT_BIRTH_YEAR,
                'birth_month'      => CUSTOMER_DEFAULT_BIRTH_MONTH,
                'birth_day'        => CUSTOMER_DEFAULT_BIRTH_DAY,
                'gender'           => CUSTOMER_DEFAULT_GENDER,
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
                        $registered_user_login_url = '/login?c=FirstOrderDirectInbound&a=index&p=' . Configure::read('app.lp_option.param') . '=' . CakeSession::read('order_option');
                        if (!is_null(CakeSession::read('order_code'))) {
                            $registered_user_login_url = '/login?c=FirstOrderDirectInbound&a=index&p=' . Configure::read('app.lp_code.param') . '=' . CakeSession::read('order_code')
                                                                                           . '?' . Configure::read('app.lp_option.param') . '=' . CakeSession::read('order_option');
                        }

                        CakeSession::write('registered_user_login_url', $registered_user_login_url);
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
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrderDirectInbound/confirm_email', 'FirstOrderDirectInbound/confirm', 'FirstOrderDirectInbound/add_credit'], true) === false) {
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
        $FirstOrderList['direct_inbound']['kit_name']  = 'minikuraダイレクト';
        $FirstOrderList['direct_inbound']['price'] = 0;
        $FirstOrderList['storage_fee']['number']    = $Order['direct_inbound']['direct_inbound'];
        $FirstOrderList['storage_fee']['kit_name']  = '月額保管料（250円）';
        $FirstOrderList['storage_fee']['price'] = $Order['direct_inbound']['direct_inbound'] * 250;
        $FirstOrderList['shipping_fee']['number']    = '';
        $FirstOrderList['shipping_fee']['kit_name']  = '預け入れ送料';
        $FirstOrderList['shipping_fee']['price'] = 0;

        CakeSession::write('FirstOrderList', $FirstOrderList);
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
    }


    /**
     * クレジットカード 登録
     */
    public function add_credit()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrderDirectInbound/add_credit', 'FirstOrderDirectInbound/add_email', 'FirstOrderDirectInbound/confirm'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        // ログインチェック
        $this->_checkLogin();

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
    }

    /**
     * オーダー 完了
     */
    public function complete()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrderDirectInbound/add_credit', 'FirstOrderDirectInbound/complete'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'first_order', 'action' => 'index']);
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        // 購入前にログインし、エントリユーザでない場合のチェック
        $is_logined = $this->_checkLogin();
        $this->set('is_logined', $is_logined);

        // セッションが古い場合があるので再チェック
        // 発送日一覧のエラーチェック
        // 着払いでない場合
        if (CakeSession::read('Address.cargo') !== "着払い") {

            $date_cd = CakeSession::read('Address.date_cd');
            $time_cd = CakeSession::read('Address.time_cd');

            // 集荷日の確認
            $pickup_yamato_datetime = new PickupYamatoDateTime();
            $datetime = $pickup_yamato_datetime->getPickupYamatoDateTime();

            if (!array_key_exists($date_cd, $datetime->results)) {
                $this->Flash->validation('集荷希望日をご確認ください。',
                    ['key' => 'date_cd']);
                CakeSession::delete('Address.date_cd');
                $this->redirect(['controller' => 'first_order_direct_inbound', 'action' => 'add_address']);
                return;
            }
            if (!array_key_exists($time_cd, $datetime->results[$date_cd])) {
                $this->Flash->validation('集荷希望時間をご確認ください。',
                    ['key' => 'time_cd']);
                CakeSession::delete('Address.time_cd');
                $this->redirect(['controller' => 'first_order_direct_inbound', 'action' => 'add_address']);
                return;
            }
        }

        // 入力値チェック
        $params = [
            'gmo_token' => filter_input(INPUT_POST, 'gmo_token')
        ];

        if (empty($params['gmo_token'])) {
            $this->Flash->validation('クレジットカード情報を再度入力してください。', ['key' => 'gmo_token']);
            return  $this->redirect('add_credit');
        }

        //* Session write
        CakeSession::write('Credit', $params);

        $for_check_param = [
            'gmo_token' => filter_input(INPUT_POST, 'gmo_token_for_check'),
        ];

        if (empty($for_check_param['gmo_token'])) {
            $this->Flash->validation('クレジットカード情報を再度入力してください。', ['key' => 'gmo_token']);
            return $this->redirect('add_credit');
        }

        $this->loadModel('PaymentGMOCreditCardCheck');
        $res = $this->PaymentGMOCreditCardCheck->getCreditCardCheck($for_check_param);

        if (!empty($res->error_message)) {
            $this->Flash->validation($res->error_message, ['key' => 'gmo_token']);
            return $this->redirect('add_credit');
        }

        //* 会員登録
        $data = array_merge_recursive(CakeSession::read('Address'), CakeSession::read('Email'));
        unset($data['select_delivery']);
        unset($data['select_delivery_list']);
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
        $this->loadModel('PaymentGMOCreditCard');

        $Credit = CakeSession::read('Credit');
        $credit_data['PaymentGMOCreditCard'] = $Credit;
        $this->PaymentGMOCreditCard->set($credit_data);
        if (!$this->PaymentGMOCreditCard->validates()) {
            $this->Flash->validation($this->PaymentGMOCreditCard->validationErrors, ['key' => 'gmo_token']);
            return $this->redirect('add_credit');
        }

        $result_credit_card = $this->PaymentGMOCreditCard->apiPost($this->PaymentGMOCreditCard->toArray());
        if (!empty($result_credit_card->error_message)) {
            $this->Flash->validation($result_credit_card->error_message, ['key' => 'gmo_token']);
            return $this->redirect('add_credit');
        }

        // ボックス情報の生成
        $box = "";
        for($i = 0;$i < CakeSession::read('Order.direct_inbound.direct_inbound'); $i++) {
            $number = $i + 1;
            if(empty($box)) {
                $box .= PRODUCT_CD_DIRECT_INBOUND.':'.'minikuraダイレクト' . ':';
            } else {
                $box .= ',' . PRODUCT_CD_DIRECT_INBOUND.':'.'minikuraダイレクト' . ':';
            }
        }

        // 入庫
        $this->InboundDirect = new InboundDirect();
        $this->InboundDirectYamato = new InboundDirectYamato();

        $inbound_direct = array();
        $inbound_direct['box'] = $box;

        if (CakeSession::read('Address.cargo') !== "着払い") {
            // 集荷
            $inbound_direct['lastname'] = CakeSession::read('Address.lastname');
            $inbound_direct['firstname'] = CakeSession::read('Address.firstname');
            $inbound_direct['tel1'] = self::_wrapConvertKana(CakeSession::read('Address.tel1'));
            $inbound_direct['postal'] = CakeSession::read('Address.postal');
            $inbound_direct['pref'] = CakeSession::read('Address.pref');
            $inbound_direct['address1'] = CakeSession::read('Address.address1');
            $inbound_direct['address2'] = CakeSession::read('Address.address2');
            $inbound_direct['address3'] = CakeSession::read('Address.address3');
            $inbound_direct['day_cd'] = CakeSession::read('Address.date_cd');
            $inbound_direct['time_cd'] = CakeSession::read('Address.time_cd');
            // YAMATO集荷情報登録
            $res = $this->InboundDirectYamato->postInboundDirectYamato($inbound_direct);
        } else {
            // 着払い
            $inbound_direct['direct_type']          = "1";
            $res = $this->InboundDirect->postInboundDirect($inbound_direct);
        }

        if (!empty($res->message)) {
            $this->Flash->validation('直接入庫処理エラー', ['key' => 'inbound_direct']);
            return $this->redirect('confirm');
        }

        // 完了したページ情報を保存
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        // アフィリエイトタグ出力用
        $this->set('customer_id', $this->Customer->data->info['customer_id']);

        $this->_cleanFirstOrderSession();

    }

    /**
     * オーダー 完了
     */
    public function complete_amazon_pay()
    {
        if (CakeSession::read('FirstOrderDirectInbound.regist_user_complete') === true) {
            return $this->_complete_amazon_pay_irregular();
        }
        $this->_complete_amazon_pay();
    }

    /**
     * オーダー 完了(正常系)
     */
    private function _complete_amazon_pay()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrderDirectInbound/confirm_amazon_pay'], true) === false) {
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

            $date_cd = CakeSession::read('Address.date_cd');
            $time_cd = CakeSession::read('Address.time_cd');

            // 集荷日の確認
            $pickup_yamato_datetime = new PickupYamatoDateTime();
            $datetime = $pickup_yamato_datetime->getPickupYamatoDateTime();

            if (!array_key_exists($date_cd, $datetime->results)) {
                $this->Flash->validation('集荷希望日をご確認ください。',
                    ['key' => 'date_cd']);
                CakeSession::delete('Address.date_cd');
                $this->redirect(['controller' => 'first_order_direct_inbound', 'action' => 'add_amazon_pay']);
                return;
            }
            if (!array_key_exists($time_cd, $datetime->results[$date_cd])) {
                $this->Flash->validation('集荷希望時間をご確認ください。',
                    ['key' => 'time_cd']);
                CakeSession::delete('Address.time_cd');
                $this->redirect(['controller' => 'first_order_direct_inbound', 'action' => 'add_amazon_pay']);
                return;
            }
        }

        //* 会員登録
        $data = array_merge_recursive(CakeSession::read('Address'), CakeSession::read('Email'));
        unset($data['select_delivery']);
        unset($data['select_delivery_list']);
        unset($data['cargo']);
        $amazon_pay_user_info = CakeSession::read('FirstOrderDirectInbound.amazon_pay.user_info');
        $data['amazon_user_id'] = $amazon_pay_user_info['user_id'];
        $data['amazon_billing_agreement_id'] = CakeSession::read('FirstOrderDirectInbound.amazon_pay.amazon_billing_agreement_id');


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
            return $this->redirect('/first_order_direct_inbound/add_amazon_pay');
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
                return $this->redirect('/first_order_direct_inbound/add_amazon_pay');
            }
            if (strpos($res->message, 'Allow Only Entry') !== false) {
                $this->Flash->validation('登録済ユーザのため購入完了できませんでした。', ['key' => 'customer_regist_info']);
            } else {
                $this->Flash->validation($res->error_message, ['key' => 'customer_regist_info']);
            }
            return $this->redirect('/first_order_direct_inbound/add_amazon_pay');
        }

        // 会員登録が完了した旨のフラグをセッション内に格納
        // 当該セッションフラグがたっている場合は、すでに会員登録済みと判定
        // 会員登録処理をスルーする
        CakeSession::write('FirstOrderDirectInbound.regist_user_complete', true);

        // ログイン
        $this->loadModel('CustomerLoginAmazonPay');

        $amazon_pay_user_info = CakeSession::read('FirstOrderDirectInbound.amazon_pay.user_info');
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
            return $this->redirect('/first_order_direct_inbound/add_amazon_pay');
        }

        // Amazon Pay ログイン情報を保持
        CakeSession::write('login.amazon_pay.user_info', CakeSession::read('FirstOrderDirectInbound.amazon_pay.user_info'));

        // カスタマー情報を取得しセッションに保存
        $this->Customer->setTokenAndSave($res->results[0]);
        $this->Customer->setPassword(CakeSession::read('Email.password'));

        $this->Customer->getInfo();

        // AmazonPay 定期購入確定処理 会員登録で確定時にBAIDを確定させる
        $this->loadModel('AmazonPayModel');
        $set_param = array();
        $set_param['merchant_id'] = Configure::read('app.amazon_pay.merchant_id');
        $set_param['amazon_billing_agreement_id'] = CakeSession::read('FirstOrderDirectInbound.amazon_pay.amazon_billing_agreement_id');
        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->setConfirmBillingAgreement($set_param);
        // GetBillingAgreementDetails
        if($res['ResponseStatus'] != '200') {
            // カードの問題エラー CODE BillingAgreementConstraintsExist constraints PaymentMethodNotAllowed and cannot be confirmed.
            // チェックがないエラー CODE BillingAgreementConstraintsExist constraints BuyerConsentNotSet and cannot be confirmed.
            // ↓AmazonPayのエラーがどのような頻度で起きるか様子見するためのログ。消さないでー！
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res setConfirmBillingAgreement ' . print_r($res, true));
            $this->Flash->validation(AMAZON_PAY_ERROR_PAYMENT_FAILURE_RETRY, ['key' => 'customer_amazon_pay_info']);
            $this->redirect('/first_order_direct_inbound/add_amazon_pay');
        }

        // 定期購入ID確定
        CakeSession::read('FirstOrderDirectInbound.amazon_pay.confirm_billing_agreement', true);

        // ボックス情報の生成
        $box = "";
        for($i = 0;$i < CakeSession::read('Order.direct_inbound.direct_inbound'); $i++) {
            $number = $i + 1;
            if(empty($box)) {
                $box .= PRODUCT_CD_DIRECT_INBOUND.':'.'minikuraダイレクト' . ':';
            } else {
                $box .= ',' . PRODUCT_CD_DIRECT_INBOUND.':'.'minikuraダイレクト' . ':';
            }
        }

        // 入庫
        $this->InboundDirect = new InboundDirect();
        $this->InboundDirectYamato = new InboundDirectYamato();

        $inbound_direct = array();
        $inbound_direct['box']          = $box;

        if (CakeSession::read('Address.cargo') !== "着払い") {
            // 集荷
            $inbound_direct['lastname'] = CakeSession::read('Address.lastname');
            $inbound_direct['firstname'] = CakeSession::read('Address.firstname');
            $inbound_direct['tel1'] = self::_wrapConvertKana(CakeSession::read('Address.tel1'));
            $inbound_direct['postal'] = CakeSession::read('Address.postal');
            $inbound_direct['pref'] = CakeSession::read('Address.pref');
            $inbound_direct['address1'] = CakeSession::read('Address.address1');
            $inbound_direct['address2'] = CakeSession::read('Address.address2');
            $inbound_direct['address3'] = CakeSession::read('Address.address3');
            $inbound_direct['day_cd'] = CakeSession::read('Address.date_cd');
            $inbound_direct['time_cd'] = CakeSession::read('Address.time_cd');
            // YAMATO集荷情報登録
            $res = $this->InboundDirectYamato->postInboundDirectYamato($inbound_direct);
        } else {
            // 着払い
            $inbound_direct['direct_type']          = "1";
            $res = $this->InboundDirect->postInboundDirect($inbound_direct);
        }

        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' inbound_direct ' . print_r($inbound_direct, true));
        if (!empty($res->message)) {
            $this->Flash->validation('直接入庫処理エラー', ['key' => 'inbound_direct']);
            return $this->redirect('/first_order_direct_inbound/add_amazon_pay');
        }

        // 完了したページ情報を保存
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        // アフィリエイトタグ出力用
        $this->set('customer_id', $this->Customer->data->info['customer_id']);

        $this->_cleanFirstOrderSession();

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
     * オーダー 完了(アマペイ決済失敗時)
     */
    private function _complete_amazon_pay_irregular()
    {
        // セッションが古い場合があるので再チェック
        // 発送日一覧のエラーチェック
        // 着払いでない場合
        if (CakeSession::read('Address.cargo') !== "着払い") {
            $date_cd = CakeSession::read('Address.date_cd');
            $time_cd = CakeSession::read('Address.time_cd');

            // 集荷日の確認
            $pickup_yamato_datetime = new PickupYamatoDateTime();
            $datetime = $pickup_yamato_datetime->getPickupYamatoDateTime();
            if (!array_key_exists($date_cd, $datetime->results)) {
                $this->Flash->validation('集荷希望日をご確認ください。',
                    ['key' => 'date_cd']);
                CakeSession::delete('Address.date_cd');
                CakeLog::write(DEBUG_LOG,
                    $this->name . '::' . $this->action . ' check_address_datetime_cd error ' . $date_cd);
                $this->redirect(['controller' => 'first_order_direct_inbound', 'action' => 'add_amazon_pay']);
                return;
            }
            if (!array_key_exists($time_cd, $datetime->results[$date_cd])) {
                $this->Flash->validation('集荷希望時間をご確認ください。',
                    ['key' => 'time_cd']);
                CakeSession::delete('Address.time_cd');
                CakeLog::write(DEBUG_LOG,
                    $this->name . '::' . $this->action . ' check_address_datetime_cd error');
                $this->redirect(['controller' => 'first_order_direct_inbound', 'action' => 'add_amazon_pay']);
                return;
            }
        }

        // ログイン
        $this->loadModel('CustomerLoginAmazonPay');

        $amazon_pay_user_info = CakeSession::read('FirstOrderDirectInbound.amazon_pay.user_info');
        $this->CustomerLoginAmazonPay->data['CustomerLoginAmazonPay']['amazon_user_id'] = $amazon_pay_user_info['user_id'];
        $this->CustomerLoginAmazonPay->data['CustomerLoginAmazonPay']['access_token'] = CakeSession::read('FirstOrder.amazon_pay.access_token');

        // ログイン処理
        $res = $this->CustomerLoginAmazonPay->login();

        if (!empty($res->error_message)) {
            $this->Flash->validation($res->error_message, ['key' => 'customer_regist_info']);
            return $this->redirect('/first_order_direct_inbound/add_amazon_pay');
        }

        // Amazon Pay ログイン情報を保持
        CakeSession::write('login.amazon_pay.user_info', CakeSession::read('FirstOrderDirectInbound.amazon_pay.user_info'));

        // カスタマー情報を取得しセッションに保存
        $this->Customer->setTokenAndSave($res->results[0]);
        $this->Customer->setPassword(CakeSession::read('Email.password'));

        $this->Customer->getInfo();

        // AmazonPay 定期購入確定処理 会員登録で確定時にBAIDを確定させる
        $this->loadModel('AmazonPayModel');
        $set_param = array();
        $set_param['merchant_id'] = Configure::read('app.amazon_pay.merchant_id');
        $set_param['amazon_billing_agreement_id'] = CakeSession::read('FirstOrderDirectInbound.amazon_pay.amazon_billing_agreement_id');
        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->setConfirmBillingAgreement($set_param);
        // GetBillingAgreementDetails
        if($res['ResponseStatus'] != '200') {
            // カードの問題エラー CODE BillingAgreementConstraintsExist constraints PaymentMethodNotAllowed and cannot be confirmed.
            // チェックがないエラー CODE BillingAgreementConstraintsExist constraints BuyerConsentNotSet and cannot be confirmed.
            // ↓AmazonPayのエラーがどのような頻度で起きるか様子見するためのログ。消さないでー！
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res setConfirmBillingAgreement ' . print_r($res, true));
            $this->Flash->validation(AMAZON_PAY_ERROR_PAYMENT_FAILURE_RETRY, ['key' => 'customer_amazon_pay_info']);
            $this->redirect('/first_order_direct_inbound/add_amazon_pay');
        }

        // 定期購入ID確定
        CakeSession::read('FirstOrderDirectInbound.amazon_pay.confirm_billing_agreement', true);

        // ボックス情報の生成
        $box = "";
        for($i = 0;$i < CakeSession::read('Order.direct_inbound.direct_inbound'); $i++) {
            $number = $i + 1;
            if(empty($box)) {
                $box .= PRODUCT_CD_DIRECT_INBOUND.':'.'minikuraダイレクト' . ':';
            } else {
                $box .= ',' . PRODUCT_CD_DIRECT_INBOUND.':'.'minikuraダイレクト' . ':';
            }
        }

        // 入庫
        $this->InboundDirect = new InboundDirect();
        $this->InboundDirectYamato = new InboundDirectYamato();

        $inbound_direct = array();
        $inbound_direct['box']          = $box;

        if (CakeSession::read('Address.cargo') !== "着払い") {
            // 集荷
            $inbound_direct['lastname'] = CakeSession::read('Address.lastname');
            $inbound_direct['firstname'] = CakeSession::read('Address.firstname');
            $inbound_direct['tel1'] = self::_wrapConvertKana(CakeSession::read('Address.tel1'));
            $inbound_direct['postal'] = CakeSession::read('Address.postal');
            $inbound_direct['pref'] = CakeSession::read('Address.pref');
            $inbound_direct['address1'] = CakeSession::read('Address.address1');
            $inbound_direct['address2'] = CakeSession::read('Address.address2');
            $inbound_direct['address3'] = CakeSession::read('Address.address3');
            $inbound_direct['day_cd'] = CakeSession::read('Address.date_cd');
            $inbound_direct['time_cd'] = CakeSession::read('Address.time_cd');
            // YAMATO集荷情報登録
            $res = $this->InboundDirectYamato->postInboundDirectYamato($inbound_direct);
        } else {
            // 着払い
            $inbound_direct['direct_type']          = "1";
            $res = $this->InboundDirect->postInboundDirect($inbound_direct);
        }

        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' inbound_direct ' . print_r($inbound_direct, true));
        if (!empty($res->message)) {
            $this->Flash->validation('直接入庫処理エラー', ['key' => 'inbound_direct']);
            return $this->redirect('/first_order_direct_inbound/add_amazon_pay');
        }

        // 完了したページ情報を保存
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        // アフィリエイトタグ出力用
        $this->set('customer_id', $this->Customer->data->info['customer_id']);

        $this->_cleanFirstOrderSession();

    }

    /**
     *
     */
    public function as_getInboundDatetime()
    {
        $this->autoRender = false;
        if (!$this->request->is('ajax')) {
            return false;
        }

        $ret_status = true;
        $result = array();

        // 集荷日未ログイン取得 text項目がないため生成
        $result_date = $this->_getInboundDate();
        if ($result_date->status === "1") {
            $week = array("日", "月", "火", "水", "木", "金", "土");
            foreach($result_date->results as $key => $value) {
                $datetime = new DateTime($value['date_cd']);
                $w = (int)$datetime->format('w');

                $result_date->results[$key]['text'] = $datetime->format('Y年m月d日 (' . $week[$w] .')');;

            }
            $result['date'] = $result_date->results;

        } else {
            $ret_status = false;
        }

        // 集荷時間未ログイン取得 text項目がないため生成
        $result_time = $this->_getInboundTime();
        if ($result_time->status === "1") {

            $time_text = array('','希望なし','午前中','12～14時','14～16時','16～18時','18～21時');

            foreach ($result_time->results as $key => $value) {

                $result_time->results[$key]['text'] = $time_text[$value['time_cd']];
            }

            $result['time'] = $result_time->results;

        } else {
            $ret_status = false;
        }


        $status = $ret_status;
        return json_encode(compact('status', 'result'));
    }


    /**
     * ヤマト運輸の配送日情報取得
     */
    private function _getInboundDate()
    {
        $result = array();

        $this->PickupDateModel = new PickupDate();

        $result = $this->PickupDateModel->getPickupDate();

        return $result;
    }

    /**
     * ヤマト運輸の配送時間情報取得
     */
    private function _getInboundTime()
    {
        $result = array();

        $PickupTimeModel = new PickupTime();

        $result = $PickupTimeModel->getPickupTime();

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
     * Direct Inbound set
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
        CakeSession::delete('FirstOrderDirectInbound');
        CakeSession::delete('FirstOrder.amazon_pay.access_token');

    }



}
