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

    protected $paginate = array(
        'limit' => 10,
        'paramType' => 'querystring'
    );

    public function beforeFilter()
    {
        parent::beforeFilter();

        // * Attack Request Block
        AppSecurity::blockAttackRequest();

        CakeSession::start();

        // アクセス拒否
        if ($this->isAccessDeny()) {
            new AppTerminalCritical(__('access_deny'), 404);
            return;
        }

        // use customer for view
        $this->set('customer', $this->Customer);

        // header
        if ($this->checkLogined) {
            if (!$this->Customer->isLogined()) {
                return $this->redirect(['controller' => 'login', 'action' => 'index', 'customer' => false]);
            }

            if ($this->Customer->isPaymentNG() && $this->request->prefix !== 'paymentng') {
                if ($this->Customer->hasCreditCard()) {
                    return $this->redirect(['controller' => 'credit_card', 'action' => 'edit', 'paymentng' => true]);
                } else {
                    $this->Flash->set(__('paymentng_no_credit_card'));
                    return $this->redirect(['controller' => 'login', 'action' => 'logout']);
                }
            }

            // ヘッダー表示、お知らせ
            $res = $this->Announcement->apiGetResults(['limit' => 5]);
            $this->set('notice_announcements', $res);
            // ご利用中サービスの集計
            $this->set('product_summary', []);
            if (!$this->Customer->isEntry()) {
                $summary = $this->InfoBox->getProductSummary();
                $this->set('product_summary', $summary);
            }
        }
    }

    protected function isAccessDeny()
    {
        return false;
    }

    public function beforeRender()
    {
        parent::beforeRender();
    }

    public function afterFilter()
    {
        parent::afterFilter();

        // 転送処理の妨げになる一時除外
        //* Click Jacking Block
        // AppSecurity::blockClickJacking();
    }
}
