<?php

App::uses('PaymentGMOCard', 'Model');

class CustomerData
{
    const SESSION_KEY = 'CUSTOMER_DATA_CACHE';

    public $token = [];
    public $info = [];

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
        if ($this->isPrivateCustomer()) {
            return "{$this->info['lastname']}{$this->info['firstname']}";
        } else {
            return $this->info['company_name'];
        }
    }

    public function getCorporatePayment()
    {
        /*
        * null：クレジットカード
        * unregistered：口座未登録（キットの購入・ボックスの入庫が出来ません）
        * registration：口座登録完了（キットの購入・ボックスの入庫ができます）
        */
        if (!$this->isPrivateCustomer()) {
            return $this->info['account_situation'];
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
}
