<?php

App::uses('ApiModel', 'Model');

class CustomerLoginAmazonPay extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CustomerLoginAmazonPay', '/login', 'amazon_pay_v5');
    }

    public function login()
    {
        $this->data[$this->model_name]['oem_key'] = $this->oem_key;
        $responses = $this->request('/login', $this->data[$this->model_name], 'GET');
        // api error
        if (empty($responses->error_message)) {
            CakeSession::write(self::SESSION_API_TOKEN, $responses->results[0]['token']);
            CakeSession::write(self::SESSION_API_DIVISION, $responses->results[0]['division']);
            CakeSession::write(CustomerLogin::SESSION_AMAZON_PAY_ACCESS_KEY, $this->data[$this->model_name]['access_token']);

        }
        return $responses;
    }

    public $validate = [
        'amazon_user_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'amazon_user_id'],
            ],
        ],
        'access_token' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'access_token'],
            ],
        ],
    ];
}
