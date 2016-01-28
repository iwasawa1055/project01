<?php

App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class DatePickup extends ApiModel
{
	public function __construct()
	{
		parent::__construct('DatePickup', '/date_pickup');
	}

	public $validate = [
	];
}
