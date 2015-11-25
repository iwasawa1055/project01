<?php
/**
 * AppMedialWarning
 */
class AppMedialWarning extends AppE {

	public $handlers = array(
		AppE::DISPLAY,
		AppE::LOG,
		AppE::MAIL,
	);

}

