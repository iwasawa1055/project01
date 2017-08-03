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

    public function __construct()
    {
        parent::__construct('AmazonPayModel');
        /*
        // amazon pay 設定
        $config = array(
            'merchant_id'   => Configure::read('app.amazon_pay.merchant_id'),
            'access_key'    => Configure::read('app.amazon_pay.access_key'),
            'secret_key'    => Configure::read('app.amazon_pay.secret_key'),
            'client_id'     => Configure::read('app.amazon_pay.client_id'),
            'region'        => Configure::read('app.amazon_pay.region'));
*/

        $config = array(
            'client_id'     => Configure::read('app.amazon_pay.client_id'),
            'region'        => Configure::read('app.amazon_pay.region'));

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
