<?php

App::uses('ApiModel', 'Model');

class KitPrice extends ApiModel
{

    public function __construct()
    {
        parent::__construct('KitPrice', '/kit_price', 'minikura_v5');
    }

    /**
     * 初回購入時のキット価格取得
     *
     * @return type
     */
    public function getKitPrice($_request_params)
    {
        $app_oem_key = 'api.minikura.oem_key';
        $this->data[$this->model_name]['oem_key'] = Configure::check($app_oem_key) ? Configure::read($app_oem_key) : new AppInternalCritical(AppE::CONFIG . $app_oem_key, 500);

        // 紹介コードがある場合は値をセット
         if (key_exists('alliance_cd', $_request_params)) {
             $this->data[$this->model_name]['alliance_cd'] = $_request_params['alliance_cd'];
         }

        $this->data[$this->model_name]['kit'] = $_request_params['kit'];
        $responses = $this->request('/kit_price', $this->data[$this->model_name], 'GET');
        return $responses;
    }



}