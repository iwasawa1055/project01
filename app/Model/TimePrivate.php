<?php

App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class TimePrivate extends ApiModel
{
	public function __construct()
	{
		parent::__construct('TimePrivate', '/time_private');
	}

	public $validate = [
	];
}
