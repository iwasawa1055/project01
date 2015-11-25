<?php

class AppExternalError extends AppE
{

	public $handlers = array(
		AppE::DISPLAY,
		AppE::LOG,
		APPE::ABORT,
	);
}

