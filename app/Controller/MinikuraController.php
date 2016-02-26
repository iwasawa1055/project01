<?php

App::uses('AppController', 'Controller');
App::uses('AppSecurity', 'Lib');
App::uses('CustomerData', 'Model');

class MinikuraController extends AppController
{
    public $helpers = ['Html', 'Title'];
    public $uses = ['CustomerLogin', 'Announcement', 'InfoBox'];


    protected $checkLogined = true;

    protected $paginate = array(
        'limit' => 10,
        'paramType' => 'querystring'
    );

    protected $customer = [];

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
            // pr(get_class($this));
            if (!$this->CustomerLogin->isLogined()) {
                $this->redirect('/login');
                exit;
            }
            $this->set('customer_name', $this->customer->getCustomerName());
            $this->set('isPrivateCustomer', $this->customer->isPrivateCustomer());
            $this->set('corporatePayment', $this->customer->getCorporatePayment());
            $this->set('hasCreditCard', $this->customer->hasCreditCard());
            $this->set('isEntry', $this->customer->isEntry());

            $res = $this->Announcement->apiGetResults(['limit' => 5]);
            $this->set('notice_announcements', $res);
            $summary = $this->InfoBox->getProductSummary();
            $this->set('product_summary', $summary);

            if ($this->customer->isPaymentNG() && $this->request->prefix !== 'paymentng') {
                return $this->redirect(['controller' => 'credit_card', 'action' => 'edit', 'paymentng' => true]);
            }
        }
    }

    public function beforeRender()
    {
        parent::beforeRender();
    }

    public function afterFilter()
    {
        parent::afterFilter();

        //* Click Jacking Block
       AppSecurity::blockClickJacking();
    }
}
