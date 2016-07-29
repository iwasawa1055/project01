<?php

App::uses('ApiModel', 'Model');

class Sale extends ApiModel
{
	/**
	* API名は未定、以下はひとまず暫定
	*/
    public function __construct($name = 'Sale', $end = '/sale', $access_point_key = 'minikura_v5')
    {
        parent::__construct($name, $end, $access_point_key);
    }

    public $validate = [];

    public function apiPost($data)
    {
        if (array_key_exists($this->model_name, $data)) {
            $data = $data[$this->model_name];
        }

        //$data['oem_key'] = $this->oem_key;
        $results = $this->request($this->end_point, $data, 'POST');

        return $results;
    }
}

