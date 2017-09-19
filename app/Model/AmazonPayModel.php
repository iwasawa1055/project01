<?php

App::uses('AppModel', 'Model');
App::import('Vendor', 'amazon_pay_sdk', array('file' => 'amazon_pay_sdk/amazon_pay-autoloader.php'));
//App::import('Vendor', 'amazon_pay_sdk');


/**
 * S3操作用モデル
 */
class AmazonPayModel extends AppModel
{
    protected $client;
    const AMAZON_ORDER_REFERENCE_ID = 'AKIAIZZ2IUFQHH5JOZEQ';

    public function __construct()
    {
        parent::__construct('AmazonPayModel');
        // amazon pay 設定

        $config = array(
            'merchant_id'   => Configure::read('app.amazon_pay.merchant_id'),
            'access_key'    => Configure::read('app.amazon_pay.access_key'),
            'secret_key'    => Configure::read('app.amazon_pay.secret_key'),
            'client_id'     => Configure::read('app.amazon_pay.client_id'),
            'region'        => Configure::read('app.amazon_pay.region'));

/*
        $config = array(
            'client_id'     => Configure::read('app.amazon_pay.client_id'),
            'region'        => Configure::read('app.amazon_pay.region'));
*/
        // テスト環境かどうか
        if(Configure::read('app.amazon_pay.sandbox')){
            $config['sandbox']  = true;
        }

        // amazon pay クライアント生成
        $this->client = new AmazonPay\Client($config);
    }

    /**
     * ユーザ情報取得
     */
    public function getUserInfo($_access_token)
    {
        $userInfo = $this->client->getUserInfo($_access_token);

        //* Return
        return $userInfo;
    }

    /**
     * ユーザ情報取得
     */
    public function getOrderReferenceDetails($_set_param)
    {
        $requestParameters = array();

        // Create the parameters array to set the order
        $requestParameters['amazon_order_reference_id'] = $_set_param['amazon_order_reference_id'];
        $requestParameters['address_consent_token'] = $_set_param['address_consent_token'];
        $requestParameters['mws_auth_token'] = $_set_param['mws_auth_token'];

        $response = $this->client->getOrderReferenceDetails($requestParameters);
        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' response ' . print_r($response, true));

        //* Return
        return $response->toArray();
    }

    /**
     * ユーザ情報取得
     */
    public function getBillingAgreementDetails($_set_param)
    {
        $requestParameters = array();

        // Create the parameters array to set the order
        $requestParameters['amazon_billing_agreement_id'] = $_set_param['amazon_billing_agreement_id'];
        $requestParameters['address_consent_token'] = $_set_param['address_consent_token'];
        $requestParameters['mws_auth_token'] = $_set_param['mws_auth_token'];

        $response = $this->client->getBillingAgreementDetails($requestParameters);
        // CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' response ' . print_r($response, true));

        //* Return
        return $response->toArray();
    }

    /**
     * ユーザ情報取得
     */
    public function setConfirmBillingAgreement($_set_param)
    {
        $requestParameters = array();

        // Create the parameters array to set the order
        $requestParameters['merchant_id'] = $_set_param['merchant_id'];
        $requestParameters['amazon_billing_agreement_id'] = $_set_param['amazon_billing_agreement_id'];
        $requestParameters['mws_auth_token'] = $_set_param['mws_auth_token'];

        $response = $this->client->ConfirmBillingAgreement($requestParameters);

        //* Return
        return $response->toArray();
    }

    //
    public function setOrderReferenceDetails($_set_param)
    {
        $requestParameters = array();

        // Create the parameters array to set the order
        $requestParameters['amazon_order_reference_id'] = $_set_param['amazon_order_reference_id'];
        $requestParameters['merchant_id']               = Configure::read('app.amazon_pay.merchant_id');

        $response = $this->client->setOrderReferenceDetails($requestParameters);
        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' response ' . print_r($response, true));

        //* Return
        return $response;
    }


    public function wrapPhysicalDestination(Array $physicaldestination)
    {
        // see : Amazon Payデータタイプ - Address <https://pay.amazon.com/jp/developer/documentation/apireference/201752430>
        $ret = [
            'Name' => '',
            'AddressLine1' => '',
            'AddressLine2' => '',
            'AddressLine3' => '',
            'City' => '',
            'Country' => '',
            'District' => '',
            'StateOrRegion' => '',
            'PostalCode' => '',
            'CountryCode' => '',
            'Phone' => '',
        ];

        $ret['Name'] = $physicaldestination['Name'];

        $tmp_address_parts = [];
        if (isset($physicaldestination['AddressLine1'])) {
            $tmp_address_parts[] = $physicaldestination['AddressLine1'];
        }
        if (isset($physicaldestination['AddressLine2'])) {
            $tmp_address_parts[] = $physicaldestination['AddressLine2'];
        }
        if (isset($physicaldestination['AddressLine3'])) {
            $tmp_address_parts[] = $physicaldestination['AddressLine3'];
        }
        $tmp_address = implode(' ', $tmp_address_parts);

        if ( mb_strlen($tmp_address) > 8) {
            $ret['AddressLine1'] = mb_substr($tmp_address, 0, 8);
            $ret['AddressLine2'] = mb_substr($tmp_address, 8, 18);
            $ret['AddressLine3'] = mb_substr($tmp_address, 26, 30);
        } else {
            // 一定長さ以下
            $ret['AddressLine1'] = $tmp_address_parts[0];
            $ret['AddressLine2'] = $tmp_address_parts[1];
            if (isset($tmp_address_parts[2])) {
                $ret['AddressLine3'] = $tmp_address_parts[2];
            }
        }

        if (isset($physicaldestination['City'])) {
            $ret['City'] = $physicaldestination['City'];
        }

        if (isset($physicaldestination['Country'])) {
            $ret['Country'] = $physicaldestination['Country'];
        }

        if (isset($physicaldestination['District'])) {
            $ret['District'] = $physicaldestination['District'];
        }

        $pref_name = $physicaldestination['StateOrRegion'];

        $pref_master_list = AppValid::$prefs;
        if (in_array($pref_name . '都', $pref_master_list)) {
            $pref_name = $pref_name . '都';
        } elseif (in_array($pref_name . '道', $pref_master_list)) {
            $pref_name = $pref_name . '道';
        } elseif (in_array($pref_name . '府', $pref_master_list)) {
            $pref_name = $pref_name . '府';
        } elseif (in_array($pref_name . '県', $pref_master_list)) {
            $pref_name = $pref_name . '県';
        } elseif (!in_array($pref_name, $pref_master_list)) {
            $pref_name = '東京都';
        }
        $ret['StateOrRegion'] = $pref_name;

        $postal_code = $physicaldestination['PostalCode'];
        $postal_code = mb_convert_kana($postal_code, "n");
        $postal_code = preg_replace('/[^0-9]/', '', $postal_code);
        $postal_code = substr($postal_code, 0, 7);
        $postal_code = str_pad($postal_code, 7, '0', STR_PAD_RIGHT);
        $ret['PostalCode'] = $postal_code;

        $ret['CountryCode'] = $physicaldestination['CountryCode'];

        if (isset($physicaldestination['Phone'])) {
            $phone = $physicaldestination['Phone'];
            $phone = mb_convert_kana($phone, "n");
            $phone = preg_replace('/[^0-9]/', '', $phone);
            $ret['Phone'] = $phone;
        }

        return $ret;
    }

    public function devideUserName($_amazon_info_user_name)
    {
        $ret['lastname'] = '';
        $ret['firstname'] = '';
        $user_name = $_amazon_info_user_name;
        $split_name;

        /* 空白処理 */
        //空白を全て半角へ
        $user_name = str_replace("　", " ", $user_name);
        //文字列の前後にある半角空白を削除
        $user_name = trim($user_name);
        //連続したスペーズを1文字へ
        $user_name = preg_replace('/ +/', ' ', $user_name);
        //名前が1文字のとき
        if (mb_strlen($user_name) === 1)
        {
          return $ret;
        }

        //名前が空白１個のとき
        if (mb_substr_count($user_name, " ") === 1)
        {
          $split_name = explode(" ", $user_name);
          if (mb_strlen($split_name[0]) <= 29 && mb_strlen($split_name[1]) <= 29)
          {
            $ret['lastname'] = $split_name[0];
            $ret['firstname'] = $split_name[1];
            return $ret;
          } 
        }

        $split_name = array();
        //名前が空白なし or 空白２個以上 or 空白１つだけど分割すると文字数オーバー のとき 
        if (mb_strlen($user_name) <= 29)
        {
            //29文字以下のとき
            $split_name[0] = mb_substr($user_name , 0, mb_strlen($user_name) - 1);
            $split_name[1] = mb_substr($user_name, -1);
        }
        else if (mb_strlen($user_name) <= 58)
        {
            //最大文字数超えてないとき
            $split_name[0] = mb_substr($user_name, 0, 29);
            $split_name[1] = mb_substr($user_name, 29);
        } else {
            return $ret;
        }
        $ret['lastname'] = $split_name[0];
        $ret['firstname'] = $split_name[1];
        return $ret;
    }
}
