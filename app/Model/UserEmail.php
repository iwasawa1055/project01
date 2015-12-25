<?php

App::uses('AppHttp', 'Lib');
App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class UserEmail extends ApiModel
{
	public function __construct()
	{
		parent::__construct('UserEmail', '/email');
	}

	public $validate = [
		'oem_key' => [
			'required' => true,
		],
		'email' => [
			'rule' => ['maxLength', 29],
			'required' => true,
		],
	];
}
