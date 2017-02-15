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
        $this->layout = 'element_set';

    }

    /**
     *
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
            $this->redirect(['controller' => 'FirstOrder', 'action' => 'add_order']);
        }

        // スターターキット購入フロー
        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' starter user ' . $option);
        $this->redirect(['controller' => 'FirstOrder', 'action' => 'add_order']);
    }

    /**
     *
     */
    public function add_order()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/index', 'FirstOrder/confirm_order'], true) === false) {
            CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' NG redirect ' . CakeSession::read('app.data.session_referer'));
            $this->redirect(['controller' => 'FirstOrder', 'action' => 'index']);
        }

        // ログインしているか
        $is_logined = false;
        if ($this->Customer->isLogined()) {
            CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' is login ');
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

        $this->set('kit_select_type', $kit_select_type);

        //* Session write
        CakeSession::write('kit_select_type', $kit_select_type );

        // boxの選択のタイプによって処理を変更する。
        $back  = filter_input(INPUT_GET, 'back');
        if ($back) {

            $Order = CakeSession::read('Order');

            $this->set('Order', $Order);

        } else {
            // orderリセット
            CakeSession::delete('order');

            $Order = array( 'mono' => array('mono' => 0, 'mono_apparel' => 0, 'mono_book' => 0),
                            'mono_total_num' => 0,
                            'hako' => array('hako' => 0, 'hako_apparel' => 0, 'hako_book' => 0),
                            'hako_total_num' => 0,
                            'cleaning' => 0,
                            'starter' => 0);
            $this->set('Order', $Order);
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

    }

    public function confirm_order()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/add_order', 'FirstOrder/add_address'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'FirstOrder', 'action' => 'index']);
        }

        $kit_select_type = CakeSession::read('kit_select_type');

        // order情報
        $Order = CakeSession::read('Order');
        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' Order ' . print_r($Order, true) );

        //* post parameter
        // 購入情報によって分岐
        $params = array();
        switch (true) {
            case $kit_select_type === 'all':
                $Order = $this->set_mono_order($Order);
                $Order = $this->set_hako_order($Order);
                $Order = $this->set_cleaning_order($Order);
                CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' set Order ' . print_r($Order, true) );

                // 箱選択されているか
                if (array_sum(array($Order['mono_total_num'], $Order['hako_total_num'], $Order['cleaning'])) === 0) {
                    $params = array(
                        'select_oreder_mono' => $Order['mono_total_num'],
                        'select_oreder_hako' => $Order['hako_total_num'],
                        'select_oreder_cleaning' => $Order['cleaning']
                    );
                }
                break;
            case $kit_select_type === 'mono':
                $Order = $this->set_mono_order($Order);
                $params = array('select_oreder_mono' => $Order['mono_total_num']);
                break;
            case $kit_select_type === 'hako':
                $Order = $this->set_hako_order($Order);
                $params = array('select_oreder_hako' => $Order['hako_total_num']);
                break;
            case $kit_select_type === 'cleaning':
                $Order = $this->set_cleaning_order($Order);
                $params = array('select_oreder_cleaning' => $Order['cleaning']);
                break;
            case $kit_select_type === 'starter_kit':
                $select_starter_kit = filter_input(INPUT_POST, 'select_starter_kit');
                $params = array('select_starter_kit' => $select_starter_kit);
                $Order['starter'] = $select_starter_kit;
                break;
            default:
                break;
        }

        //* Session write
        CakeSession::write('Order', $Order);

        //*  validation 基本は共通クラスのAppValidで行う
        $validation = AppValid::validate($params);

        //* 共通バリデーションでエラーあったらメッセージセット
        if ( !empty($validation)) {
            foreach ($validation as $key => $message) {
                $this->Flash->validation($message, ['key' => $key]);
            }
            $this->set('kit_select_type', $kit_select_type);
            $this->set('Order', $Order);
            $this->render('add_order');
            return;
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->redirect(['controller' => 'FirstOrder', 'action' => 'add_address']);

    }


    public function add_address()
    {
        // DEBUG

        $back  = filter_input(INPUT_GET, 'back');
        if ($back) {


        }
    }
    
    public function confirm_address()
    {
        $params = [
            'firstname'         => filter_input(INPUT_POST, 'firstname'),
            'firstname_kana'    => filter_input(INPUT_POST, 'firstname_kana'),
            'lastname'          => filter_input(INPUT_POST, 'lastname'),
            'lastname_kana'     => filter_input(INPUT_POST, 'lastname_kana'),
            'tel1'              => filter_input(INPUT_POST, 'tel1'),
            'postal'            => filter_input(INPUT_POST, 'postal'),
            'address1'          => filter_input(INPUT_POST, 'address1'),
            'address2'          => filter_input(INPUT_POST, 'address2'),
            'address3'          => filter_input(INPUT_POST, 'address3'),
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

    private function set_mono_order($Order)
    {
        $params = null;

        $params = [
            'mono'          => (int)filter_input(INPUT_POST, 'mono'),
            'mono_apparel'  => (int)filter_input(INPUT_POST, 'mono_apparel'),
            'mono_book'     => (int)filter_input(INPUT_POST, 'mono_book'),
        ];

        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' set mono ' . filter_input(INPUT_POST, 'mono') );


        $Order['mono'] = $params;
        $Order['mono_total_num'] = array_sum($params);

        return $Order;
    }

    private function set_hako_order($Order)
    {
        $params = null;

        $params = [
            'hako'          => (int)filter_input(INPUT_POST, 'hako'),
            'hako_apparel'  => (int)filter_input(INPUT_POST, 'hako_apparel'),
            'hako_book'     => (int)filter_input(INPUT_POST, 'hako_book'),
        ];

        $Order['hako'] = $params;
        $Order['hako_total_num'] = array_sum($params);

        return $Order;
    }

    private function set_cleaning_order($Order)
    {
        $Order['cleaning'] = (int)filter_input(INPUT_POST, 'cleaning');

        return $Order;
    }

}