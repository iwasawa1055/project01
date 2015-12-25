<?php

App::uses('AppHttp', 'Lib');
App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class User extends ApiModel
{
	public function __construct()
	{
		parent::__construct('User', '/user');
	}

	public $validate = [
		'oem_key' => [
			'required' => true,
		],
		'lastname' => [
				'aa12' => [
					'rule' => ['maxLength', 29],
					'required' => true,
				],
				// 'aaa223' => [
				// 	'rule' => ['isJa']
				// ]
		],
		'lastname_kana' => [
			'rule' => ['maxLength', 29],
			'required' => true,
		],
		'firstname' => [
			'rule' => ['maxLength', 29],
			'required' => true,
		],
		'firstname_kana' => [
			'rule' => ['maxLength', 29],
			'required' => true,
		],
		'nickname' => [
			'rule' => ['maxLength', 40],
			'required' => false,
		],
		'gender' => [
			'rule' => '/^m|f$/i',
			'required' => true,
		],
		'birth' => [
			'rule' => '/^\d{4}\-\d{2}-\d{2}$/i',
			'required' => true,
		],
		'tel1' => [
			'rule' => ['maxLength', 29],
			'required' => true,
		],
		'tel2' => [
			'rule' => ['maxLength', 29],
			'required' => false,
		],
		'email' => [
			'rule' => ['maxLength', 29],
			'required' => true,
		],
		'password' => [
			'rule' => '/^[0-9a-zA-Z!,.:?@^_~]{6,64}$/i',
			'required' => true,
		],
		'postal' => [
			'rule' => '/^\d{3}\-\d{4}$/i',
			'required' => true,
		],
		'pref' => [
			'rule' => ['maxLength', 29],
			'required' => true,
		],
		'address1' => [
			'rule' => ['maxLength', 8],
			'required' => true,
		],
		'address2' => [
			'rule' => ['maxLength', 18],
			'required' => true,
		],
		'address3' => [
			'rule' => ['maxLength', 30],
			'required' => false,
		],
		'newsletter' => [
			'rule' => '/^0|1$/i',
			'required' => true,
		],
	];
}
