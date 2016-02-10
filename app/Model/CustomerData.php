<?php

// App::uses('InfoBox', 'Model');
// App::uses('InfoItem', 'Model');

class CustomerData
{
    const SESSION_KEY = 'CUSTOMER_DATA_CACHE';

    public $token = [];
    public $entry = [];
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

    public function setEntryAndSave($data = [])
    {
        $this->entry = $data;
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

    public function isPaymentNG()
    {
        return $this->token['payment'] === CUSTOMER_PAYMENT_NG;
    }
}
