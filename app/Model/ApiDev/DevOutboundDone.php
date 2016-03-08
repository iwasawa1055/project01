<?php

App::uses('ApiModel', 'Model');

class DevOutboundDone extends ApiModel
{
	public function __construct()
	{
		parent::__construct('DevOutboundDone', '/dev_outbound_done');
	}
}
