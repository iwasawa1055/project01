<?php

App::uses('ApiModel', 'Model');

class DevDeliveryCancel extends ApiModel
{
	public function __construct()
	{
		parent::__construct('DevDeliveryCancel', '/dev_delivery_cancel');
	}
}
