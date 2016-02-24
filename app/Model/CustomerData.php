<?php

App::uses('PaymentGMOCard', 'Model');
App::uses('CustomerEntry', 'Model');
App::uses('CustomerInfo', 'Model');
App::uses('CorporateInfo', 'Model');

class CustomerData
{
    const SESSION_KEY = 'CUSTOMER_DATA_CACHE';

    public $token = [];
    private $info = null;

    public function __construct()
    {
    }

    public static function restore()
    {
        $d = CakeSession::read(self::SESSION_KEY);
        if (empty($d)) {
            $d = new CustomerData();
        }
        return $d;
    }

    public static function save($d)
    {
        CakeSession::write(self::SESSION_KEY, $d);
    }

    public static function delete()
    {
        CakeSession::delete(self::SESSION_KEY);
    }

    public function setTokenAndSave($data = [])
    {
        $this->token = $data;
        CustomerData::save($this);
    }

    public function setInfoAndSave($data = [])
    {
        $this->info = $data;
        CustomerData::save($this);
    }

    public function getInfo()
    {
        if (empty($this->info)) {
            $this->info = $this->createInfo();
            CustomerData::save($this);
        }
        return $this->info;
    }

    private function createInfo()
    {
        $model = null;
        if ($this->isPrivateCustomer()) {
            // 個人
            if ($this->isEntry()) {
                // 仮登録情報取得
                $model = new CustomerEntry();
            } else {
                // 本登録情報取得
                $model = new CustomerInfo();
            }
        } else {
            // 法人
            // 本登録情報取得
            $model = new CorporateInfo();
        }
        $res = $model->apiGet();
        if ($res->isSuccess()) {
            return $res->results[0];
        }
        return null;
    }

    public function switchEntryToCustomer()
    {
        $this->token['regist_level'] = CUSTOMER_REGIST_LEVEL_CUSTOMER;
        $this->reloadInfo();
    }
    public function reloadInfo()
    {
        $this->info = null;
    }

    public function isEntry()
    {
        return $this->token['regist_level'] === CUSTOMER_REGIST_LEVEL_ENTRY;
    }

    public function isPrivateCustomer()
    {
        return $this->token['division'] === CUSTOMER_DIVISION_PRIVATE;
    }

    public function isPaymentNG()
    {
        return $this->token['payment'] === CUSTOMER_PAYMENT_NG;
    }

    public function getCustomerName()
    {
        $info = $this->getInfo();
        if ($this->isPrivateCustomer()) {
            return "${info['lastname']}${info['firstname']}";
        } else {
            return $info['company_name'];
        }
    }

    public function getCorporatePayment()
    {
        /*
        * null：クレジットカード
        * unregistered：口座未登録（キットの購入・ボックスの入庫が出来ません）
        * registration：口座登録完了（キットの購入・ボックスの入庫ができます）
        */
        $info = $this->getInfo();
        if (!$this->isPrivateCustomer()) {
            return $info['account_situation'];
        }
        return null;
    }

    public function hasCreditCard()
    {
        if ($this->isPrivateCustomer()) {
            $ca = new PaymentGMOCard();
            $dc = $ca->apiGetDefaultCard();
            return 0 < count($dc);
        } else {
            return empty($this->getCorporatePayment());
        }
    }
    public function getDefaultCard()
    {
        if ($this->isPrivateCustomer() || empty($this->getCorporatePayment())) {
            $ca = new PaymentGMOCard();
            $dc = $ca->apiGetDefaultCard();
            if (!empty($dc)) {
                return $dc;
            }
        }
        return null;
    }
}
