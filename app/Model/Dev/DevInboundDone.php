<?php

App::uses('ApiModel', 'Model');

class DevInboundDone extends ApiModel
{
	public function __construct()
	{
		parent::__construct('DevInboundDone', '/dev_inbound_done');
	}
}
