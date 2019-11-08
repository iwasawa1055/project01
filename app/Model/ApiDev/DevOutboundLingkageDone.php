<?php

App::uses('ApiModel', 'Model');

class DevOutboundLingkageDone extends ApiModel
{
	public function __construct()
	{
		parent::__construct('DevOutboundLingkageDone', '/dev_outbound_linkage_done');
	}
}
