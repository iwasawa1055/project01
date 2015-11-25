<?php
/**
 * AppExternalDefect
 */
class AppExternalDefect extends AppE
{

	public $handlers = array(
		AppE::DISPLAY,
		AppE::LOG,
		AppE::MAIL,
		AppE::PATCH,
	);

}

