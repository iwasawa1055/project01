<?php

App::uses('ApiModel', 'Model');

class DevBilling extends ApiModel
{
	public function __construct()
	{
		parent::__construct('DevBilling', '/dev_billing');
	}
}
