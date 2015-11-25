<?php

class AppInternalError extends AppE
{

	public $handlers = array(
		AppE::DISPLAY,
		APPE::LOG,
		APPE::ABORT,
	);
}
