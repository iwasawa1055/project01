<?php

App::uses('ApiModel', 'Model');

class InboundDirect extends ApiModel
{

    public function __construct()
    {
        parent::__construct('InboundDirect', '/inbound_direct', 'minikura_v3');
    }

    /**
     * 入庫・登録（ダイレクト・マイボックス）
     *
     * @return type
     */
    public function postInboundDirect($_request_params)
    {
        $this->data[$this->model_name] = $_request_params;

        $app_oem_key = 'api.minikura.oem_key';
        $this->data[$this->model_name]['oem_key'] = Configure::check($app_oem_key) ? Configure::read($app_oem_key) : new AppInternalCritical(AppE::CONFIG . $app_oem_key, 500);
        $token = CakeSession::read(self::SESSION_API_TOKEN);
        $this->data[$this->model_name]['token'] = $token;
        $responses = $this->request('/inbound_direct', $this->data[$this->model_name], 'POST');
        return $responses;
    }



}