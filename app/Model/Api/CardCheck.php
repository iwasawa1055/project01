<?php

App::uses('ApiModel', 'Model');

class CardCheck extends ApiModel
{

    public function __construct()
    {
        parent::__construct('CardCheck', '/card_check', 'gmopayment_v4');
    }

    /**
     * クレジットカードの与信チェック
     *
     * @return type
     */
    public function getCardCheck($_request_params)
    {
        $app_oem_key = 'api.minikura.oem_key';
        $this->data[$this->model_name]['oem_key'] = Configure::check($app_oem_key) ? Configure::read($app_oem_key) : new AppInternalCritical(AppE::CONFIG . $app_oem_key, 500);
        $this->data[$this->model_name]['card_no'] = $_request_params['card_no'];
        $this->data[$this->model_name]['expire'] = $_request_params['expire'];
        $this->data[$this->model_name]['security_cd'] = $_request_params['security_cd'];
        
        $responses = $this->request('/card_check', $this->data[$this->model_name], 'GET');
        return $responses;
    }



}