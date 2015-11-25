<?php
/**
 * AppExternalWarning
 */
class AppExternalWarning extends AppE
{

	public $handlers = array(
		AppE::DISPLAY,
		AppE::LOG,
		AppE::MAIL,
	);

}

