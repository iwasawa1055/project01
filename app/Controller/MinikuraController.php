<?php

App::uses('AppController', 'Controller');
App::uses('AppSecurity', 'Lib');
App::uses('CustomerData', 'Model');

class MinikuraController extends AppController
{
    public $helpers = ['Html', 'Title'];
    public $uses = ['CustomerLogin', 'Announcement', 'InfoBox'];
    public $components = ['Customer', 'Address'];

    // アクセス許可
    protected $checkLogined = true;
    protected $denyEntry = false;

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

        $this->set('isLogined', $this->Customer->isLogined());
        $this->set('customerName', $this->Customer->getName());
        $this->set('isPrivateCustomer', $this->Customer->isPrivateCustomer());
        $this->set('corporatePayment', $this->Customer->getCorporatePayment());
        $this->set('hasCreditCard', $this->Customer->hasCreditCard());
        $this->set('isEntry', $this->Customer->isEntry());

        $this->set('canOrder', $this->Customer->canOrder());
        $this->set('canInbound', $this->Customer->canInbound());
        $this->set('canOutbound', $this->Customer->canOutbound());

        // header
        if ($this->checkLogined) {
            if (!$this->Customer->isLogined()) {
                return $this->redirect(['controller' => 'login', 'action' => 'index']);
            }

            if ($this->Customer->isPaymentNG() && $this->request->prefix !== 'paymentng') {
                if ($this->Customer->hasCreditCard()) {
                    return $this->redirect(['controller' => 'credit_card', 'action' => 'edit', 'paymentng' => true]);
                } else {
                    $this->Flash->set(__('paymentng_no_credit_card'));
                    return $this->redirect(['controller' => 'login', 'action' => 'logout']);
                }
            }

            if ($this->denyEntry && $this->Customer->isEntry()) {
                new AppTerminalCritical('denyEntry', 403);
            }

            $res = $this->Announcement->apiGetResults(['limit' => 5]);
            $this->set('notice_announcements', $res);
            $summary = $this->InfoBox->getProductSummary();
            $this->set('product_summary', $summary);

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
