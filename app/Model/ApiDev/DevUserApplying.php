<?php

App::uses('ApiModel', 'Model');

class DevUserApplying extends ApiModel
{
	public function __construct()
	{
		parent::__construct('DevUserApplying', '/dev_user_applying');
	}
}
