<?php

App::uses('Controller', 'Controller');
App::uses('AppSecurity', 'Lib');
App::uses('CustomerData', 'Model');

class AppController extends Controller
{
    public $helpers = ['Html', 'Title'];
    public $uses = ['CustomerLogin', 'Announcement', 'InfoBox'];

    // ログインチェックが必要か？
    protected $checkLogined = true;

    protected $paginate = array(
        'limit' => 10,
        'paramType' => 'querystring'
    );

    protected $customer = [];

    /**
     * 制御前段処理
     *
     * @param	void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        //* Attack Request Block
        AppSecurity::blockAttackRequest();

        //* Agent Check
        Configure::write('Session.checkAgent', false);
        CakeSession::start();
        CakeSession::write('session_start', true);

        //* Request Count
        // CakeSession::$requestCountdown = 10000;

        $this->set('isLogined', $this->CustomerLogin->isLogined());
        // Customer Information
        $this->customer = CustomerData::restore();

        // header
        if ($this->checkLogined) {
            // 未ログイン
            if (!$this->CustomerLogin->isLogined()) {
                $this->redirect('/login');
                exit;
            }
            // ユーザー名
            $this->set('customer_name', $this->customer->getCustomerName());
            // ユーザー区分
            $this->set('isPrivateCustomer', $this->customer->isPrivateCustomer());
            // 法人：支払区分
            $this->set('corporatePayment', $this->customer->getCorporatePayment());
            // クレジットカード登録済み
            $this->set('hasCreditCard', $this->customer->hasCreditCard());
            // 仮登録
            $this->set('isEntry', $this->customer->isEntry());

            // お知らせ
            $res = $this->Announcement->apiGetResults(['limit' => 5]);
            $this->set('notice_announcements', $res);
            // 利用中サービス
            $summary = $this->InfoBox->getProductSummary();
            $this->set('product_summary', $summary);

            // 債務ユーザーの場合はクレジットカード変更以外は遷移不可
            if ($this->customer->isPaymentNG() && $this->request->prefix !== 'paymentng') {
                return $this->redirect(['controller' => 'credit_card', 'action' => 'edit', 'paymentng' => true]);
            }
        }
    }

    /**
     * レンダー前段処理
     *
     * @param	void
     */
    public function beforeRender()
    {
        parent::beforeRender();
    }

    /**
     * 制御後段処理
     *
     * @param	void
     */
    public function afterFilter()
    {
        parent::afterFilter();

        //* Click Jacking Block
        AppSecurity::blockClickJacking();
    }
}
