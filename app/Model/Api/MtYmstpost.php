<?php

App::uses('ApiModel', 'Model');

class MtYmstpost extends ApiModel
{
    public function __construct()
    {
        parent::__construct('MtYmstpost', '/postal', 'minikura_v5');
    }

    public function getPostal($_params)
    {
        $app_oem_key = 'api.minikura.oem_key';
        $this->data[$this->model_name]['oem_key'] = Configure::check($app_oem_key) ? Configure::read($app_oem_key) : new AppInternalCritical(AppE::CONFIG . $app_oem_key, 500);
        $this->data[$this->model_name]['postal'] = $_params['postal'];

        $responses = $this->request('/postal', $this->data[$this->model_name], 'GET');
        return $responses;
    }
}
