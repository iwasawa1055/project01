<?php

App::uses('ApiModel', 'Model');

class DevDeliveryDone extends ApiModel
{
	public function __construct()
	{
		parent::__construct('DevDeliveryDone', '/dev_delivery_done');
	}
}
