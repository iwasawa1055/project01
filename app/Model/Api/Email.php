<?php

App::uses('ApiModel', 'Model');

class Email extends ApiModel
{

    public function __construct()
    {
        parent::__construct('email', '/email', 'minikura_v3');
    }

    /**
     * 既存メールチェック
     *
     * @return type
     */
    public function getEmail($_request_params)
    {
        $app_oem_key = 'api.minikura.oem_key';
        $this->data[$this->model_name]['oem_key'] = Configure::check($app_oem_key) ? Configure::read($app_oem_key) : new AppInternalCritical(AppE::CONFIG . $app_oem_key, 500);
        $this->data[$this->model_name]['email'] = $_request_params['email'];
        $responses = $this->request('/email', $this->data[$this->model_name], 'GET');

        return $responses;
    }



}