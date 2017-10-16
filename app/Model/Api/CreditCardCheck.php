<?php

App::uses('ApiModel', 'Model');

class CreditCardCheck extends ApiModel
{

    public function __construct()
    {
        parent::__construct('CreditCardCheck', '/credit_card_check', 'gmopayment_v4');
    }

    /**
     * クレジットカードのチェック
     *
     * @return type
     */
    public function getCreditCardCheck($_request_params)
    {
        $app_oem_key = 'api.minikura.oem_key';
        $this->data[$this->model_name]['oem_key'] = Configure::check($app_oem_key) ? Configure::read($app_oem_key) : new AppInternalCritical(AppE::CONFIG . $app_oem_key, 500);
        $this->data[$this->model_name]['gmo_token'] = $_request_params['gmo_token'];

        $responses = $this->request('/credit_card_check', $this->data[$this->model_name], 'GET');
        return $responses;
    }
}
