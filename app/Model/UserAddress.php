<?php

App::uses('AppHttp', 'Lib');
App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class UserAddress extends ApiModel
{
	public function __construct()
	{
		parent::__construct('/address');
	}

	public $validate = [
		'address_id' => [
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
		'tel1' => [
			'rule' => ['maxLength', 29],
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
	];
}
