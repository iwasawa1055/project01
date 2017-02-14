<?php
App::uses('MinikuraController', 'Controller');
App::uses('OutboundLimit', 'Model');

class FirstOrderController extends MinikuraController
{

    // アクセス許可
    protected $checkLogined = false;
    const MODEL_NAME = 'OutboundLimit';

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
            CakeSession::write(Configure::read('app.lp_option.session_name'), $option);
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

        $this->set('is_logined', $is_logined);
        $this->set('select_starter_kit', false);

        $back  = filter_input(INPUT_GET, 'back');
        if ($back) {

            if($is_logined) {

            } else {
                $select_starter_kit = CakeSession::read('select_starter_kit');
                $this->set('select_starter_kit', $select_starter_kit);
            }
        }


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
            $this->render('add_order');
            return;
        }

        // 入力カード情報セット
        $this->set('select_starter_kit', $params['select_starter_kit']);

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

    public function add_credit()
    {

        $back  = filter_input(INPUT_GET, 'back');
        if ($back) {


        }
    }

    public function add_email()
    {

        $back  = filter_input(INPUT_GET, 'back');
        if ($back) {


        }
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