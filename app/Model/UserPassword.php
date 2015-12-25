<?php

App::uses('AppHttp', 'Lib');
App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class UserPassword extends ApiModel
{
	public function __construct()
	{
		parent::__construct('UserPassword', '/password');
	}

	public $validate = [
		'password' => [
			'required' => true,
		],
		'email' => [
			'required' => true,
		],
		'new_password' => [
			'rule' => '/^[0-9a-zA-Z!,.:?@^_~]{6,64}$/i',
			'required' => true,
		],
	];
}
