<?php
/**
 * AppMedialDefect
 */
class AppMedialDefect extends AppE {

	public $handlers = array(
		AppE::DISPLAY,
		AppE::LOG,
		AppE::MAIL,
		AppE::PATCH,
	);

}

