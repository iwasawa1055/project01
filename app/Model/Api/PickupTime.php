<?php

App::uses('ApiModel', 'Model');

class PickupTime extends ApiModel
{

    public function __construct()
    {
        parent::__construct('PickupTime', '/pickup_time', 'minikura_v4');
    }

    /**
     * 初回購入時のキット価格取得
     *
     * @return type
     */
    public function getPickupTime()
    {
        $app_oem_key = 'api.minikura.oem_key';
        $this->data[$this->model_name]['oem_key'] = Configure::check($app_oem_key) ? Configure::read($app_oem_key) : new AppInternalCritical(AppE::CONFIG . $app_oem_key, 500);

        $responses = $this->request('/pickup_time', $this->data[$this->model_name], 'GET');

        if (!empty($responses->results)) {
            foreach ($responses->results as $key => $val) {
                // time_cd 3を削除
                if ($val['time_cd'] == '3') {
                    unset($responses->results[$key]);
                }
            }
        }

        return $responses;
    }



}
