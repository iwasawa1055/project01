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
    public function GetOrderReferenceDetails($_access_token, $_set_param)
    {
        $requestParameters = array();

        // Create the parameters array to set the order
        $requestParameters['amazon_order_reference_id'] = self::AMAZON_ORDER_REFERENCE_ID;
        $requestParameters['amount']            = '175';
        $requestParameters['currency_code']     = 'JPY';
        $requestParameters['seller_note']   = 'Love this sample';
        $requestParameters['seller_order_id']   = '123456-TestOrder-123456';
        $requestParameters['store_name']        = 'Saurons collectibles in Mordor';

        if($this->client->success)
        {
            $requestParameters['address_consent_token'] = null;
            $response = $this->client->GetOrderReferenceDetails($requestParameters);
        }

        // Pretty print the Json and then echo it for the Ajax success to take in
 //       $json = json_decode($response->toJson());
        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' response ' . print_r($response, true));

        //* Return
//        return json_encode($json, JSON_PRETTY_PRINT);
        return true;
    }

    /**
     * ファイル削除
     */
    public function deleteObjects($params)
    {
        /*
        $required_keys = ['Bucket', 'Delete'];
        foreach ($required_keys as $key) {
            if (!isset($params[$key])) {
                new AppInternalCritical(AppE::PARAMETER_INVALID . var_export($params, true), 500);
            }
        }
        try {
            $response = $this->s3->deleteObjects([
                'Bucket' => $params['Bucket'],
                'Delete' => $params['Delete'],
            ]);
        } catch (\Aws\Exception\AwsException $e) {
            $error_str = '';
            $error_str .= sprintf("StatusCode:%s", $e->getStatusCode());
            $error_str .= sprintf("Result:%s", $e->getResult());
            $error_str .= sprintf("AwsErrorType:%s", $e->getAwsErrorType());
            $error_str .= sprintf("AwsErrorCode:%s", $e->getAwsErrorCode());
            $error_str .= sprintf("Code:%s", $e->getCode());
            new AppExternalCritical(AppE::EXTERNAL_SERVER_ERROR . 'putObject failed.' . $error_str, 500);
        }
        
        // APIのレスポンスっぽく整形
        $result = [
            'status' => '1',
            'message' => '',
            'results' => $response,
        ];

        //* Return
        return $result;
        */
    }
}
