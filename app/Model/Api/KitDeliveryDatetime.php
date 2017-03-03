<?php

App::uses('ApiModel', 'Model');

class KitDeliveryDatetime extends ApiModel
{

    public function __construct()
    {
        parent::__construct('KitDeliveryDatetime', '/kit_delivery_datetime', 'minikura_v4');
    }

    /**
     * キット配送日時・取得
     *
     * @return type
     */
    public function getKitDeliveryDatetime($_request_params)
    {
        $app_oem_key = 'api.minikura.oem_key';
        $this->data[$this->model_name]['oem_key'] = Configure::check($app_oem_key) ? Configure::read($app_oem_key) : new AppInternalCritical(AppE::CONFIG . $app_oem_key, 500);
        $this->data[$this->model_name]['postal'] = $_request_params['postal'];
        $responses = $this->request('/kit_delivery_datetime', $this->data[$this->model_name], 'GET');

        return $responses;
    }



}