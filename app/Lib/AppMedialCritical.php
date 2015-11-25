<?php
/**
 * AppMedialCritical
 */
class AppMedialCritical extends AppE {

	public $handlers = array(
		AppE::DISPLAY,
		AppE::LOG,
		AppE::MAIL,
		AppE::ALERT,
	);

}

