<?php

App::uses('ApiModel', 'Model');

class DevWorkId extends ApiModel
{
	public function __construct()
	{
		parent::__construct('DevWorkId', '/dev_work_id');
	}
}
