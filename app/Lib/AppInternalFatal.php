<?php
/**
 * AppInternalFatal
 */
class AppInternalFatal extends AppE
{

	public $handlers = array(
		AppE::DISPLAY,
		AppE::LOG,
		AppE::MAIL,
		AppE::SHUT,
	);

}

