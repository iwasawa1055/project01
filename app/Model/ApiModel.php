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

	public function __construct($end)
	{
		parent::__construct();
		$this->oem_key = Configure::read('api.oem_key');
		$this->access_point = Configure::read('api.minikura.access_point');
		$this->end_point = $end;
	}

	public function login($email, $password)
	{
		$params = [
			'oem_key' => $this->oem_key,
			'email' => $email,
			'password' => $password,
		];

		$responses = $this->request('/login', $params, 'GET', [], '', 'json');

		CakeSession::write('api.token', $responses->results['token']);
		CakeSession::write('api.division', $responses->results['division']);

		return $responses;
	}

	protected function request($end_point, $params, $method, $headers, $block, $accept)
	{
		$url = $this->access_point . $end_point;
		$responses = AppHttp::request($url, $params, $method, $headers, $block, $accept);
		$a = new ApiResponse($responses);
		if (!$a->isSuccess()) {
			new AppMedialCritical(AppE::MEDIAL_SERVER_ERROR . $responses['message'] . ', ' . $responses['results']['support'], 500);
		}
		return $a;
	}

	protected function requestWithToken($end_point, $params = [], $method = 'GET', $headers = [], $block = '', $accept = 'json')
	{
		$token = CakeSession::read('api.token');
		$params['token'] = $token;
		return $this->request($end_point, $params, $method, $headers, $block, $accept);
	}

	public function apiGet($data = [])
	{
		return $this->requestWithToken($this->end_point, $data, 'GET');
	}
	public function apiPost($data)
	{
		return $this->requestWithToken($this->end_point, $data, 'POST');
	}
	public function apiPut($data)
	{
		return $this->requestWithToken($this->end_point, $data, 'PUT');
	}
	public function apiPatch($data)
	{
		return $this->requestWithToken($this->end_point, $data, 'PATCH');
	}
	public function apiDelete($data)
	{
		return $this->requestWithToken($this->end_point, $data, 'DELETE');
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
		return ($this->status == 1);
	}
}
