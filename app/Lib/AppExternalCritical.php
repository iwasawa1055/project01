<?php
/**
 * AppExternalCritical
 */
class AppExternalCritical extends AppE
{

	public $handlers = array(
		AppE::DISPLAY,
		AppE::LOG,
		AppE::MAIL,
		AppE::ALERT,
	);

}

