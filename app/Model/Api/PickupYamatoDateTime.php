<?php

App::uses('ApiModel', 'Model');

class PickupYamatoDateTime extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PickupYamatoDateTime', '/pickup_yamato_date_time', 'minikura_v5');
    }

    public function getPickupYamatoDateTime()
    {
        $app_oem_key = 'api.minikura.oem_key';
        $this->data[$this->model_name]['oem_key'] = Configure::check($app_oem_key) ? Configure::read($app_oem_key) : new AppInternalCritical(AppE::CONFIG . $app_oem_key, 500);

        $responses = $this->request('/pickup_date_time_yamato', $this->data[$this->model_name], 'GET');
        return $responses;
    }

}
