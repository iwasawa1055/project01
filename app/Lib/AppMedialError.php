<?php

class AppMedialError extends AppE
{

	public $handlers = array(
		AppE::DISPLAY,
		APPE::LOG,
		APPE::MAIL,
		APPE::ABORT,
	);
}

