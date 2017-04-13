<?php

App::uses('ApiModel', 'Model');

class InboundDirectArrival extends ApiModel
{

    public function __construct()
    {
        parent::__construct('InboundDirectArrival', '/inbound_direct_arrival', 'minikura_v5');
    }

    /**
     * 入庫・登録（ダイレクト・マイボックス）
     *
     * @return type
     */
    public function postInboundDirectArrival($_request_params)
    {
        $app_oem_key = 'api.minikura.oem_key';
        $this->data[$this->model_name]['oem_key'] = Configure::check($app_oem_key) ? Configure::read($app_oem_key) : new AppInternalCritical(AppE::CONFIG . $app_oem_key, 500);
        $token = CakeSession::read(self::SESSION_API_TOKEN);
        $this->data[$this->model_name]['token'] = $token;

        /*        $this->data[$this->model_name] = $_request_params;
                $responses = $this->request('/inbound_direct', $this->data[$this->model_name], 'POST');
        */
        $responses = array('status' => "1", 'message' => "", 'results' => array('total' => "1", 'contents' => array('sales_id' => "1")));

        return $responses;
    }



}