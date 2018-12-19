<?php

App::uses('PaymentGMOCreditCard', 'Model');
App::uses('CustomerEntry', 'Model');
App::uses('CustomerInfo', 'Model');
App::uses('CorporateInfo', 'Model');
App::uses('CustomerAccount', 'Model');

/**
 * カスタマーログイン情報
 */
class CustomerData
{
    const SESSION_KEY = 'CUSTOMER_DATA_CACHE';

    public $token = [];
    public $info = null;

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

    public function setPassword($password)
    {
        CakeSession::write(self::SESSION_KEY . '_PASSWORD', $password);
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
    public function getPassword()
    {
        return CakeSession::read(self::SESSION_KEY . '_PASSWORD');
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
        if (is_array($this->token) && array_key_exists('regist_level', $this->token)) {
            return $this->token['regist_level'] === CUSTOMER_REGIST_LEVEL_ENTRY;
        }
        return null;
    }

    public function isPrivateCustomer()
    {
        if (is_array($this->token) && array_key_exists('division', $this->token)) {
            return $this->token['division'] === CUSTOMER_DIVISION_PRIVATE;
        }
        return null;
    }

    public function isCorporateCustomer()
    {
        if (is_array($this->token) && array_key_exists('division', $this->token)) {
            return $this->token['division'] === CUSTOMER_DIVISION_CORPORATE;
        }
        return null;
    }

    public function isPaymentNG()
    {
        if (is_array($this->token) && array_key_exists('payment', $this->token)) {
            return $this->token['payment'] === CUSTOMER_PAYMENT_NG;
        }
        return null;
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

    /**
     * 法人口座振替状態
     * null クレジットカード
     * ACCOUNT_SITUATION_REGISTRATION　口座未登録（キットの購入・ボックスの入庫が出来ません）
     * ACCOUNT_SITUATION_REGISTRATION　口座登録完了（キットの購入・ボックスの入庫ができます）
     * @return string|null [description]
     */
    public function getCorporatePayment()
    {
        $info = $this->getInfo();
        if (!$this->isPrivateCustomer() && !empty($info['account_situation'])) {
            if ($info['account_situation'] === ACCOUNT_SITUATION_REGISTRATION) {
                return ACCOUNT_SITUATION_REGISTRATION;
            }
            return ACCOUNT_SITUATION_UNREGISTERED;
        }
        return null;
    }

    /**
     * クレジットカード登録有無
     * @return boolean [description]
     */
    public function hasCreditCard()
    {
        return !empty($this->getDefaultCard());
    }

    /**
     * 利用可能なクレジットカードを1つ取得
     * @return array|null クレジットカード情報
     */
    public function getDefaultCard()
    {
        if ($this->isPrivateCustomer() || empty($this->getCorporatePayment())) {
            $ca = new PaymentGMOCreditCard();
            return $ca->apiGetDefaultCard();
        }
        return null;
    }

    /**
     * 利用可能な銀行口座を1つ取得　販売機能の振り込み口座
     * @return array|null 口座情報
     */
    public function getCustomerBankAccount()
    {
        $o = new CustomerAccount();
        $customer_account_result = $o->apiGet();
        if (!empty($customer_account_result->results[0])) {
            $customer_account = $customer_account_result->results[0];
            return $customer_account;
        }
        return null;
    }

    /**
     * ユーザ登録時の紹介コードを取得する
     * @return string|null 紹介コード
     */
    public function getCustomerAllianceCd()
    {
        $info = $this->getInfo();
        if (array_key_exists('alliance_cd', $info)) {
            return $info['alliance_cd'];
        }
        return '';
    }


}
