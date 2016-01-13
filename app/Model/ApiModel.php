<?php

App::uses('AppHttp', 'Lib');
App::uses('AppValid', 'Lib');
App::uses('AppModel', 'Model');

class ApiModel extends AppModel
{
    public $useTable = false;

    protected $oem_key = null;
    protected $access_point = null;
    protected $email = null;
    protected $password = null;
    protected $end_point = null;
    protected $model_name = null;

    /**
     * [__construct description].
     *
     * @param [type] $name             [description]
     * @param [type] $end              [description]
     * @param string $access_point_key API種別キー
     */
    public function __construct($name, $end, $access_point_key = 'minikura_v3')
    {
        parent::__construct();
        $this->oem_key = Configure::read('api.oem_key');
        $this->access_point = Configure::read('api.minikura.access_point.'.$access_point_key);
        $this->model_name = $name;
        $this->end_point = $end;
    }

    public function apiGet($data = [])
    {
        if (array_key_exists($this->model_name, $data)) {
            $data = $data[$this->model_name];
        }

        return $this->requestWithToken($this->end_point, $data, 'GET');
    }
    public function apiPost($data)
    {
        if (array_key_exists($this->model_name, $data)) {
            $data = $data[$this->model_name];
        }

        return $this->requestWithToken($this->end_point, $data, 'POST');
    }
    public function apiPut($data)
    {
        if (array_key_exists($this->model_name, $data)) {
            $data = $data[$this->model_name];
        }

        return $this->requestWithToken($this->end_point, $data, 'PUT');
    }
    public function apiPatch($data)
    {
        if (array_key_exists($this->model_name, $data)) {
            $data = $data[$this->model_name];
        }

        return $this->requestWithToken($this->end_point, $data, 'PATCH');
    }
    public function apiDelete($data)
    {
        if (array_key_exists($this->model_name, $data)) {
            $data = $data[$this->model_name];
        }

        return $this->requestWithToken($this->end_point, $data, 'DELETE');
    }

    /**
     * [request description].
     *
     * @param [type] $end_point [description]
     * @param [type] $params    [description]
     * @param [type] $method    [description]
     * @param [type] $headers   [description]
     * @param [type] $block     [description]
     * @param [type] $accept    [description]
     *
     * @return [type] [description]
     */
    protected function request($end_point, $params, $method, $headers = [], $block = '', $accept = 'json')
    {
		// TODO: APIの仕様を確認
		// GET, POST以外はすべてPOSTで送る
		if ('GET' !== $method && 'POST' !== $method) {
			$params['request_method'] = strtolower($method);
			$method = 'POST';
		}

        $url = $this->access_point.$end_point;
        $responses = AppHttp::request($url, $params, $method, $headers, $block, $accept);
        $a = new ApiResponse($responses);
        if (!$a->isSuccess()) {
            new AppMedialCritical(AppE::MEDIAL_SERVER_ERROR.$responses['message'].', '.$responses['results']['support'], 500);
        }

        return $a;
    }

    protected function requestWithToken($end_point, $params = [], $method = 'GET', $headers = [], $block = '', $accept = 'json')
    {
        $token = CakeSession::read('api.token');
        $params['token'] = $token;

        return $this->request($end_point, $params, $method, $headers, $block, $accept);
    }
}

class ApiResponse
{
    public $status = null;
    public $message = null;
    public $results = null;

    public function __construct($json)
    {
        $this->status = $json['status'];
        $this->message = $json['message'];
        $this->results = $json['results'];
    }
    public function isSuccess()
    {
        return $this->status === '1';
    }
}
