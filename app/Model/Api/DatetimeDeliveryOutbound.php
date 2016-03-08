<?php

App::uses('AppHttp', 'Lib');
App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class DatetimeDeliveryOutbound extends ApiModel
{
	public function __construct()
	{
		parent::__construct('DatetimeDeliveryOutbound', '/datetime_delivery_outbound');
	}

	public $validate = [
		'postal' => [
			'rule' => '/^\d{3}\-\d{4}$/i',
			'required' => true,
		],
	];
}
