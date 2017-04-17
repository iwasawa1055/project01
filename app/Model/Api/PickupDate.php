<?php

App::uses('ApiModel', 'Model');

class PickupDate extends ApiModel
{

    public function __construct()
    {
        parent::__construct('PickupDate', '/pickup_date', 'minikura_v4');
    }

    /**
     * 初回購入時のキット価格取得
     *
     * @return type
     */
    public function getPickupDate()
    {
        $app_oem_key = 'api.minikura.oem_key';
        $this->data[$this->model_name]['oem_key'] = Configure::check($app_oem_key) ? Configure::read($app_oem_key) : new AppInternalCritical(AppE::CONFIG . $app_oem_key, 500);

        $responses = $this->request('/pickup_date', $this->data[$this->model_name], 'GET');
        return $responses;
    }



}