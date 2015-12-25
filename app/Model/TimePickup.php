<?php

App::uses('AppHttp', 'Lib');
App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class TimePickup extends ApiModel
{
	public function __construct()
	{
		parent::__construct('TimePickup', '/time_pickup');
	}

	public $validate = [
	];
}
