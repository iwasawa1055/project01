<?php

App::uses('AppController', 'Controller');
App::uses('AppSecurity', 'Lib');
App::uses('CustomerData', 'Model');

class MinikuraController extends AppController
{
    public $helpers = ['Html', 'Title'];
    public $uses = ['CustomerLogin', 'Announcement', 'InfoBox'];
    public $components = ['Customer', 'Address'];

    protected $checkLogined = true;

    protected $paginate = array(
        'limit' => 10,
        'paramType' => 'querystring'
    );

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

        $this->set('isLogined', $this->Customer->isLogined());
        $this->set('customerName', $this->Customer->getName());
        $this->set('isPrivateCustomer', $this->Customer->isPrivateCustomer());
        $this->set('corporatePayment', $this->Customer->getCorporatePayment());
        $this->set('hasCreditCard', $this->Customer->hasCreditCard());
        $this->set('isEntry', $this->Customer->isEntry());

        // header
        if ($this->checkLogined) {
            if (!$this->Customer->isLogined()) {
                $this->redirect('/login');
                exit;
            }

            $res = $this->Announcement->apiGetResults(['limit' => 5]);
            $this->set('notice_announcements', $res);
            $summary = $this->InfoBox->getProductSummary();
            $this->set('product_summary', $summary);

            if ($this->Customer->isPaymentNG() && $this->request->prefix !== 'paymentng') {
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
