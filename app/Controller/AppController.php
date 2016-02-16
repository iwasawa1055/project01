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

        // announcements of header
        if ($this->checkLogined) {
            if (!$this->CustomerLogin->isLogined()) {
                $this->redirect('/login');
                exit;
            }

            // ユーザー名
            $this->set('customer_name', $this->customer->getCustomerName());

            $res = $this->Announcement->apiGetResults(['limit' => 5]);
            $this->set('notice_announcements', $res);

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
