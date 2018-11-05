<?php

/**
 * zendesk連携モデル
 *
 */
class ZendeskModel
{
    public function __construct()
    {
    }

    /**
     * requestZendeskApi
     *
     * @param string $_url
     * @param array  $_requests
     * @param null   $_method
     * @return array|string
     */
    private function requestZendeskApi($_url, array $_requests = null, $_method = null)
    {
//        $requests = AppArray::forwardKey($_requests, $this->changer);
        // BASIC認証・mime_type情報設定
        $headers = [
            'Authorization: Basic ' . base64_encode(Configure::read('app.zendesk.site_id') . ':' . Configure::read('app.zendesk.site_token')),
            'Accept: ' . 'application/json',
        ];

        $responses = AppHttp::request($_url, $_requests, $_method, $headers);
        return $responses;
    }


    /**
     * ユーザー取得
     * @param array $_param
     *         customers_id 得意先ID
     * @return array
     */
    public function getUser($_param)
    {
        $resource = '/v2/users/search.json';
        $url = Configure::read('app.zendesk.access_point') . $resource;
        $method = 'GET';
        $request_params = [
            //'external_id' => $this->_convertExternalId($_param),
            'external_id' => 'dev_11000'
        ];

        $response = $this->requestZendeskApi($url, $request_params, $method);

        $results = ! empty($response['body_parsed']['users'][0]) ? $response['body_parsed']['users'][0] : [];

        return $results;
    }


    /**
     * ユーザー登録
     * @param array $_param
     *         name ユーザー名前
     *         email ユーザーメールアドレス
     *         customers_id 得意先ID
     * @return array|string
     */
    public function postUser($_param)
    {
        $resource = '/v2/users.json';
        $url = Configure::read('app.zendesk.access_point') . $resource;
        $method = 'POST';
//        $request_params = [
//            'user' => [
//                'name' => $_param['name'],
//                'email' => $_param['email'],
//                'external_id' => ! empty($_param['customer_id']) ? $this->_convertExternalId($_param) : '',
//                'details' => ! empty($_param['customer_cd']) ? $_param['customer_cd'] : ''
//            ],
//        ];
        $request_params = [
            'user' => [
                'name' => '保田テスト1',
                'email' => 'yasuda.soichi+5@terrada.co.jp',
                'external_id' => 'dev_10000',
                'details' => '123456'
            ],
        ];

//        $request_params = AppArray::forwardKey($request_params, $this->changer);
        $response = $this->requestZendeskApi($url, $request_params, $method);

        $results = ! empty($response['body_pased']['user']) ? $response['body_parsed']['user'] : [];

        return $results;
    }


    /**
     * チケットリスト取得
     * @param array $_param
     *         zendesk_user_id zendeskユーザーID
     *         page ページ数
     *         per_page 1ページあたりの表示数
     * @return array
     */
    public function getTicketList($_param)
    {
        $resource = '/v2/users/' . $_param['zendesk_user_id'] . '/tickets/requested.json';
        $url = Configure::read('app.zendesk.access_point') . $resource;
        $method = 'GET';
        $request_params = [
            //'page' => $_param['page'],
            //'per_page' => $_param['per_page'],
            'sort_by' => 'updated_at',
            'sort_order' =>'desc',
        ];

        $response = $this->requestZendeskApi($url, $request_params, $method);
//debug($response);exit;
        $results = [];
        if (!empty($response['body_parsed']['count'])) {
            foreach ($response['body_parsed']['tickets'] as $key => $value) {
                $results['tickets'][$key] = $value;
            }
        }
        $results['count'] = !empty($response['body_parsed']['count']) ? $response['body_parsed']['count'] : 0;
//debug($results);exit;

        return $results;
    }


    /**
     * チケット詳細取得
     * @param array $_param
     *         customers_id 得意先ID
     * @return array
     */
    public function getTicketDetail($_param)
    {
        $resource = '/v2/users/search.json';
        $url = Configure::read('app.zendesk.access_point') . $resource;
        $method = 'GET';
        $request_params = [
            //'external_id' => $this->_convertExternalId($_param),
            'external_id' => 'dev_10000'
        ];

        $response = $this->requestZendeskApi($url, $request_params, $method);

        $results = ! empty($response['body_parsed']['users'][0]) ? $response['body_parsed']['users'][0] : [];

        return $results;
    }


    /**
     * 環境ごとにIDを使い分けるための処理
     * customers_idをexternal_idに変換
     * @param array $_param
     *                  customers_id 得意先ID
     * @return array|string
     */
    private function _convertExternalId($_param)
    {
        $_env_type = Configure::read('app.zendesk.env_type');
        switch (true) {
            case $_env_type === 'prod':
                $external_id = $_param['customer_id'];
                break;
            case $_env_type === 'stag':
                $external_id = 'stag_' . $_param['customer_id'];
                break;
            case $_env_type === 'dev':
                $external_id = 'dev_' . $_param['customer_id'];
                break;
            default:
                $external_id = 'test_' . $_param['customer_id'];
                break;
        }
        return $external_id;
    }
}
