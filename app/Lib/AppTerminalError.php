<?php

class AppTerminalError extends AppE
{

	public $handlers = array(
		AppE::DISPLAY,
		APPE::LOG,
		APPE::ABORT,
	);
}

