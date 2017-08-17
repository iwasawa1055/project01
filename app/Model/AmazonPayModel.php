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
