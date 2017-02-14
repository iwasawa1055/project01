<?php
App::uses('AppValid', 'Lib');
App::uses('MinikuraController', 'Controller');
App::uses('OutboundLimit', 'Model');


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
        $this->layout = 'first_order';

    }

    /**
     *
     */
    public function index()
    {

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
                // オートログイン
                $this->redirect($none_first_redirect_param);
            }
        }

        // set action ログインしている
        if (!empty($customer) && $customer->isLogined()) {

            // エントリーユーザでない
            if (!$this->Customer->isEntry()) {
                $this->redirect($none_first_redirect_param);
            }

            // スニーカーユーザでない
            if ($this->Customer->isSneaker()) {
                $this->redirect($none_first_redirect_param);
            }

            // ログイン済みエントリーユーザ
            $this->redirect(['controller' => 'FirstOrder', 'action' => 'add_order']);
        }

        // スターターキット購入フロー
        $this->redirect(['controller' => 'FirstOrder', 'action' => 'add_order']);
    }

    /**
     *
     */
    public function add_order()
    {
        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        // ログインしているか
        $is_logined = false;
        if (!empty($customer) && $customer->isLogined()) {
            $is_logined = true;
        }

        $lp_option = CakeSession::read('lp_option');
        $kit_select_type = 'all';
        switch ($lp_option) {
            case 'mono':
                // ログインしている場合はmonoを表示
                if($is_logined) {
                    $kit_select_type = 'mono';
                } else {
                    $kit_select_type = 'starter_kit';
                }
                break;
            case 'hako':
                if($is_logined) {
                    $kit_select_type = 'hako';
                }
                break;
            case 'cleaning':
                if($is_logined) {
                    $kit_select_type = 'cleaning';
                }
                break;
            case 'is_code':
            default:
                if($is_logined) {
                    $kit_select_type = 'all';
                } else {
                    $kit_select_type = 'starter_kit';
                }
                break;
        }

        $this->set('kit_select_type', $kit_select_type);

        // boxの選択のタイプによって処理を変更する。
        $back  = filter_input(INPUT_GET, 'back');
        if ($back) {
            switch ($kit_select_type) {
                case 'all':
                    break;
                case 'mono':
                    break;
                case 'hako':
                    break;
                case 'cleaning':
                    break;
                case 'starter_kit':
                    $select_starter_kit = CakeSession::read('select_starter_kit');
                    $this->set('select_starter_kit', 0);
                    break;
                default:
                    break;
            }
        } else {
            switch ($kit_select_type) {
                case 'all':
                    break;
                case 'mono':
                    break;
                case 'hako':
                    break;
                case 'cleaning':
                    break;
                case 'starter_kit':
                    $this->set('select_starter_kit', 0);
                    break;
                default:
                    break;
            }
        }

        //* Session write
        CakeSession::write('kit_select_type', $kit_select_type );

    }

    public function confirm_order()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/add_order', 'FirstOrder/add_order'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'FirstOrder', 'action' => 'add_order']);
        }

        //* post parameter
        $select_starter_kit = filter_input(INPUT_POST, 'select_starter_kit');

        print_r('select_starter_kit');
        print_r($select_starter_kit);
        $params = ['select_starter_kit' => $select_starter_kit];

        //* Session write
        CakeSession::write('select_starter_kit', $params );

        //*  validation 基本は共通クラスのAppValidで行う
        $validation = AppValid::validate($params);

        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $kit_select_type = CakeSession::read('kit_select_type');
            $this->set('kit_select_type', $kit_select_type);

            switch ($kit_select_type) {
                case 'all':
                    break;
                case 'mono':
                    break;
                case 'hako':
                    break;
                case 'cleaning':
                    break;
                case 'starter_kit':
                    $select_starter_kit = CakeSession::read('select_starter_kit');
                    $this->set('select_starter_kit', 0);
                    break;
                default:
                    break;
            }
            $this->render('add_order');
            return;
        }

        // 購入情報によって分岐
        CakeSession::write('order',
            array(KIT_CD_STARTER_MONO => 1, KIT_CD_STARTER_MONO_APPAREL => 1, KIT_CD_STARTER_MONO_BOOK => 1));

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->redirect(['controller' => 'FirstOrder', 'action' => 'add_address']);

    }


    public function add_address()
    {
        $back  = filter_input(INPUT_GET, 'back');
        if ($back) {


        }
    }
    
    public function confirm_address()
    {
        $params = [
            'firstname'              => filter_input(INPUT_POST, 'firstname'),
            'firstname_kana'    => filter_input(INPUT_POST, 'firstname_kana'),
            'lastname'              => filter_input(INPUT_POST, 'lastname'),
            'lastname_kana'    => filter_input(INPUT_POST, 'lastname_kana'),
            'tel1'                      => filter_input(INPUT_POST, 'tel1'),
            'postal'                  => filter_input(INPUT_POST, 'postal'),
            'address1'              => filter_input(INPUT_POST, 'address1'),
            'address2'              => filter_input(INPUT_POST, 'address2'),
            'address3'              => filter_input(INPUT_POST, 'address3'),
        ];

        
        
        
        
        $this->redirect(['controller' => 'FirstOrder', 'action' => 'add_credit']);
    }

    public function add_credit()
    {

        $back  = filter_input(INPUT_GET, 'back');
        if ($back) {


        }
    }

    public function confirm_credit()
    {
        $this->loadModel("PaymentGMOSecurityCard");
        $this->loadModel('PaymentGMOCard');

        $params = [
            'card_no'              => str_replace("-","",filter_input(INPUT_POST, 'card_no')),
            'security_cd'         => filter_input(INPUT_POST, 'security_cd'),
            'expire'                  => filter_input(INPUT_POST, 'expire_month').filter_input(INPUT_POST, 'expire_year'),
            'holder_name'       => filter_input(INPUT_POST, 'holder_name'),
        ];
        
        //*  validation 基本は共通クラスのAppValidで行う
        $validation = AppValid::validate($params);

        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->render('add_credit');
            return;
        }

        $this->redirect(['controller' => 'FirstOrder', 'action' => 'add_email']);
    }

    public function add_email()
    {
        $loginconfigure = Configure::read('app.register');

        // 入力カード情報セット
        $this->set('login_config', $loginconfigure);
    }

    public function confirm_email()
    {
        $password = filter_input(INPUT_POST, 'password');
        $password_confirm = filter_input(INPUT_POST, 'password_confirm');
        
        $params = [
            'email'                 => filter_input(INPUT_POST, 'email'),
            'password'         => $password,
            'password_confirm' => $password_confirm,
            'birth'                       => sprintf("%04d-%02d-%02d",filter_input(INPUT_POST, 'birth_year'),filter_input(INPUT_POST, 'birth_month'),filter_input(INPUT_POST, 'birth_day')),
            'gender'              => filter_input(INPUT_POST, 'gender'),
            'newsletter'        => filter_input(INPUT_POST, 'newsletter'),
            'alliance_cd'       => filter_input(INPUT_POST, 'alliance_cd'),
        ];
        
        //*  validation 基本は共通クラスのAppValidで行う
        $validation = AppValid::validate($params);
        $is_validation_error = false;
        
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
        $validation = AppValid::validateTermsAgree(filter_input(INPUT_POST, 'remember'));

        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $is_validation_error = true;
        }

        if ($is_validation_error === true) {
            $this->render('add_email');
            return;
        }
        
        $this->redirect(['controller' => 'FirstOrder', 'action' => 'confirm']);
    }

    public function confirm()
    {

        $back  = filter_input(INPUT_GET, 'back');
        if ($back) {


        }
    }

    public function complete()
    {

        $back  = filter_input(INPUT_GET, 'back');
        if ($back) {


        }
    }

}