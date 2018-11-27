<?php

App::uses('AppModel', 'Model');

/**
 * zendesk連携モデル
 *
 */
class ZendeskModel extends AppModel
{
    public $useTable = false;

    public function __construct($name = 'ZendeskModel')
    {
        parent::__construct($name);
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
     *         customer_id 得意先ID
     * @return array
     */
    public function getUser($_param)
    {
        $resource = '/v2/users/search.json';
        $url = Configure::read('app.zendesk.access_point') . $resource;
        $method = 'GET';
        $request_params = [
            'external_id' => $this->_convertExternalId($_param['customer_id']),
        ];

        $response = $this->requestZendeskApi($url, $request_params, $method);
        $results = ! empty($response['body_parsed']['users'][0]) ? $response['body_parsed']['users'][0] : [];

        return $results;
    }


    /**
     * ユーザー取得
     * (メールアドレス重複確認用)
     * @param array $_param
     *         email メールアドレス
     * @return array
     */
    public function getUserByEmail($_param)
    {
        $resource = '/v2/users/search.json';
        $url = Configure::read('app.zendesk.access_point') . $resource;
        $method = 'GET';
        $request_params = [
            'query' => $_param['email']
        ];

        $response = $this->requestZendeskApi($url, $request_params, $method);
        $results = [];
        foreach ($response['body_parsed']['users'] as $key => $value) {
            $results[] = $value;
        }

        return !empty($results[0]) ? $results[0] : $results;
    }



    /**
     * ユーザー登録
     * @param array $_param
     *         name ユーザー名前
     *         email ユーザーメールアドレス
     *         customer_id 得意先ID
     *         customer_cd 得意先CD
     * @return array|string
     */
    public function postUser($_param)
    {
        $resource = '/v2/users.json';
        $url = Configure::read('app.zendesk.access_point') . $resource;
        $method = 'POST';
        $request_params = [
            'user' => [
                'name' => $_param['name'],
                'email' => $_param['email'],
                'external_id' => ! empty($_param['customer_id']) ? $this->_convertExternalId($_param['customer_id']) : '',
                'details' => ! empty($_param['customer_cd']) ? $_param['customer_cd'] : ''
            ],
        ];

        $response = $this->requestZendeskApi($url, $request_params, $method);
        $results = ! empty($response['body_parsed']['user']) ? $response['body_parsed']['user'] : [];

        return $results;
    }


    /**
     * ユーザー更新
     * @param array $_param
     *         zendesk_user_id 変更対象のZendeskユーザーID
     *         email メールアドレス
     *         customer_id 得意先ID
     *         customer_cd 得意先CD
     * @return array
     */
    public function putUser($_param)
    {
        $resource = '/v2/users/' . $_param['zendesk_user_id'] . '.json';
        $url = Configure::read('app.zendesk.access_point') . $resource;
        $method = 'PUT';
        $request_params = [
            'user' => [
                'email' => $_param['email'],
                'external_id' => ! empty($_param['customer_id']) ? $this->_convertExternalId($_param['customer_id']) : '',
                'details' => ! empty($_param['customer_cd']) ? $_param['customer_cd'] : ''
            ],
        ];

        $response = $this->requestZendeskApi($url, $request_params, $method);
        $results = ! empty($response['body_parsed']['user']) ? $response['body_parsed']['user'] : [];

        return $results;
    }


    /**
     * メールアドレス更新
     *     putUserだとメールアドレスが更新できないため
     *     別メソッドで対応
     * @param array $_param
     *         zendesk_user_id 変更対象のZendeskユーザーID
     *         email 変更後のメールアドレス
     * @return array
     */
    public function putUserEmail($_param)
    {
        // identityID 取得
        $resource = '/v2/users/' . $_param['zendesk_user_id'] . '/identities.json';
        $url = Configure::read('app.zendesk.access_point') . $resource;
        $method = 'GET';
        $request_params = [
        ];
        $response = $this->requestZendeskApi($url, $request_params, $method);
        if (empty($response['body_parsed']['identities'])) {
            new AppInternalCritical(AppE::FUNC . ' putUserEmail Failed', 500);
        }
        $identity_id = $response['body_parsed']['identities'][0]['id'];

        // identity 更新
        $resource = '/v2/users/' . $_param['zendesk_user_id'] . '/identities/' . $identity_id . '.json';
        $url = Configure::read('app.zendesk.access_point') . $resource;
        $method = 'PUT';
        $request_params = [
            'identity' => [
                'value' => $_param['email'],
            ],
        ];
        $response = $this->requestZendeskApi($url, $request_params, $method);
        if (empty($response['body_parsed']['identity'])) {
            $results = false;
        } else {
            $results = true;
        }

        return $results;
    }


    /**
     * チケットリスト取得
     * @param array $_param
     *         zendesk_user_id zendeskユーザーID
     * @return array
     */
    public function getTicketList($_param)
    {
        $resource = '/v2/users/' . $_param['zendesk_user_id'] . '/tickets/requested.json';
        $url = Configure::read('app.zendesk.access_point') . $resource;
        $method = 'GET';

        // 100件以上のチケットも取得できるようにする
        $results = [];
        $page = 1;
        do {
            $request_params = [
                'sort_by' => 'updated_at',
                'sort_order' =>'desc',
                'page' => $page,
            ];
            $response = $this->requestZendeskApi($url, $request_params, $method);
            if (!empty($response['body_parsed']['count'])) {
                foreach ($response['body_parsed']['tickets'] as $key => $value) {
                    // TODO 退会済みユーザーのdeleted_ticketフラグは弾く(退会動線次第)
                    $results['tickets'][] = $value;
                }
            }
            $page += 1;
        } while (!is_null($response['body_parsed']['next_page']));

        $results['count'] = !empty($response['body_parsed']['count']) ? $response['body_parsed']['count'] : 0;

        return $results;
    }


    /**
     * チケット詳細取得
     * @param array $_param
     *         ticket_id zendeskチケットID
     * @return array
     */
    public function getTicketByTicketId($_param)
    {
        $resource = '/v2/tickets/' . $_param['ticket_id'] . '.json';
        $url = Configure::read('app.zendesk.access_point') . $resource;
        $method = 'GET';
        $request_params = [
        ];

        $response = $this->requestZendeskApi($url, $request_params, $method);
        $results = ! empty($response['body_parsed']['ticket']) ? $response['body_parsed']['ticket'] : [];

        return $results;
    }


    /**
     * チケットコメント取得
     * @param array $_param
     *         ticket_id zendeskチケットID
     * @return array
     */
    public function getTicketCommentByTicketId($_param)
    {
        $resource = '/v2/tickets/' . $_param['ticket_id'] . '/comments.json';
        $url = Configure::read('app.zendesk.access_point') . $resource;
        $method = 'GET';
        $request_params = [
            'sort_by' => 'asc'
        ];

        $response = $this->requestZendeskApi($url, $request_params, $method);
        $results = ! empty($response['body_parsed']['comments']) ? $response['body_parsed']['comments'] : [];

        if (!empty($results)) {
            if ($request_params['sort_by'] === 'asc') {
                unset($results[0]);
            } elseif ($request_params['sort_by'] === 'desc') {
                // TODO commentのlimit(plusと合わせる)
                $count = count($results);
                unset($results[$count - 1]);
            }
        }
        return $results;
    }


    /**
     * チケット登録
     * @param array $_param
     *          subject タイトル
     *          body 内容
     *          tags タイトル
     *          zendesk_user_id zendeskユーザID
     * @return boolean
     */
    public function postTicket($_param)
    {
        $resource = '/v2/tickets.json';
        $url = Configure::read('app.zendesk.access_point') . $resource;
        $method = 'POST';
        $request_params = [
            'ticket' => [
                'comment' => [
                    'body' => $_param['body'],
                ],
                'subject' => $_param['subject'],
                'requester_id' => $_param['zendesk_user_id'],
                'tags' => $_param['tags'],
            ],
        ];

        $response = $this->requestZendeskApi($url, $request_params, $method);
        if (empty($response['body_parsed']['ticket'])) {
            $results = false;
        } else {
            $results = true;
        }

        return $results;
    }


    /**
     * ユーザーのチケット更新
     * @param array $_param
     *          zendesk_user_id zendeskユーザID
     *          ticket_id zendeskチケットID
     *          comment 内容
     * @return array
     */
    public function putTicketComment($_param)
    {
        $resource = '/v2/tickets/' . $_param['ticket_id'] . '.json';
        $url = Configure::read('app.zendesk.access_point') . $resource;
        $method = 'PUT';
        $request_params = [
            'ticket' => [
                'status' => 'open',
                'comment' => [
                    'body' => $_param['comment'],
                    'author_id' => $_param['zendesk_user_id'],
                ],
            ],
        ];

        $response = $this->requestZendeskApi($url, $request_params, $method);
        $results = !empty($response['body_parsed']['ticket']) ? $response['body_parsed']['ticket'] : [];

        return $results;
    }


    /**
     * 環境ごとにIDを使い分けるための処理
     * customer_idをexternal_idに変換
     * @param array $_param
     *         customer_id 得意先ID
     * @return array|string
     */
    private function _convertExternalId($_param)
    {
        $_env_type = Configure::read('app.zendesk.env_type');
        switch (true) {
            case $_env_type === 'prod':
                $external_id = $_param;
                break;
            case $_env_type === 'stag':
                $external_id = 'stag_' . $_param;
                break;
            case $_env_type === 'dev':
                $external_id = 'dev_' . $_param;
                break;
            default:
                $external_id = 'test_' . $_param;
                break;
        }
        return $external_id;
    }
}
