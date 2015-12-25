<?php

App::uses('AppHttp', 'Lib');
App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class DatetimeDeliveryKit extends ApiModel
{
	public function __construct()
	{
		parent::__construct('DatetimeDeliveryKit', '/datetime_delivery_kit');
	}

	public $validate = [
		'postal' => [
			'rule' => '/^\d{3}\-\d{4}$/i',
			'required' => true,
		],
	];
}
