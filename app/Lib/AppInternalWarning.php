<?php
/**
 * AppInternalWarning
 */
class AppInternalWarning extends AppE
{

	public $handlers = array(
		AppE::DISPLAY,
		AppE::LOG,
		AppE::MAIL,
	);

}

