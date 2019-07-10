<?php
App::uses('MinikuraController', 'Controller');

/**
 * @property CustomerRegistInfoAmazonPay $CustomerRegistInfoAmazonPay
 * @property CustomerLoginAmazonPay $CustomerLoginAmazonPay
 */
class FirstOrderController extends MinikuraController
{
    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    /**
     * 静的ページからの遷移 セットアクション
     */
    public function index()
    {
        //紹介コードの取得
        $alliance_cd = '';
        if (isset($_GET['alliance_cd'])) {
            $alliance_cd = $_GET['alliance_cd'];
        } elseif (CakeSession::read('app.data.alliance_cd')) {
            $alliance_cd = CakeSession::read('app.data.alliance_cd');
        }

        //紹介コードの確認
        if ($alliance_cd === '') {
            #21226 初回登録導線の改修でこのページは会員登録にリダイレクト
            $this->redirect('/customer/register/add');
        } else {
            #21226 初回登録導線の改修でこのページは会員登録にリダイレクト
            $this->redirect('/customer/register/add?alliance_cd=' . $alliance_cd);
        }
    }

    /**
     * Boxの選択 静的ページからのオプション、ユーザ条件によって表示内容を変更
     */
    public function add_order()
    {
        //紹介コードの取得
        $alliance_cd = '';
        if (isset($_GET['alliance_cd'])) {
            $alliance_cd = $_GET['alliance_cd'];
        } elseif (CakeSession::read('app.data.alliance_cd')) {
            $alliance_cd = CakeSession::read('app.data.alliance_cd');
        }

        //紹介コードの確認
        if ($alliance_cd === '') {
            #21226 初回登録導線の改修でこのページは会員登録にリダイレクト
            $this->redirect('/customer/register/add');
        } else {
            #21226 初回登録導線の改修でこのページは会員登録にリダイレクト
            $this->redirect('/customer/register/add?alliance_cd=' . $alliance_cd);
        }
    }
}
