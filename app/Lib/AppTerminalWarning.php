<?php
/**
 * AppTerminalWarning
 */
class AppTerminalWarning extends AppE
{

	public $handlers = array(
		AppE::DISPLAY,
		AppE::LOG,
		AppE::MAIL,
	);

}

