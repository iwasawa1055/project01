<?php
/**
 * AppTerminalCritical
 */
class AppTerminalCritical extends AppE
{

	public $handlers = array(
		AppE::DISPLAY,
		AppE::LOG,
		AppE::MAIL,
		AppE::ALERT,
	);

}

