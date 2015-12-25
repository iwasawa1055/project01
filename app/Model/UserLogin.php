<?php

App::uses('AppHttp', 'Lib');
App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class UserLogin extends ApiModel
{
	public function __construct()
	{
		parent::__construct('UserLogin', '/login');
	}


	public function login()
	{
			// $params = [
			// 		'oem_key' => $this->oem_key,
			// 		'email' => $email,
			// 		'password' => $password,
			// ];

			$this->data[$this->model_name]['oem_key'] = $this->oem_key;
			$responses = $this->request('/login', $this->data[$this->model_name], 'GET');

			CakeSession::write('api.token', $responses->results['token']);
			CakeSession::write('api.division', $responses->results['division']);

			return $responses;
	}

	public $validate = [
		// 'oem_key' => [
		// 	'required' => true,
		// ],
		'email' => [
			'rule' => ['maxLength', 29],
			'required' => true,
		],
		'password' => [
			'rule' => '/^[0-9a-zA-Z!,.:?@^_~]{6,64}$/i',
			'required' => true,
		],
	];
}
