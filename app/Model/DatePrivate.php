<?php

App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class DatePrivate extends ApiModel
{
	public function __construct()
	{
		parent::__construct('DatePrivate', '/date_private');
	}

	public $validate = [
	];
}
