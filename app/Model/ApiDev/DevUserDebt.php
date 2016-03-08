<?php

App::uses('ApiModel', 'Model');

class DevUserDebt extends ApiModel
{
	public function __construct()
	{
		parent::__construct('DevUserDebt', '/dev_user_debt');
	}
}
