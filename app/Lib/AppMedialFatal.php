<?php
/**
 * AppMedialFatal
 */
class AppMedialFatal extends AppE {

	public $handlers = array(
		AppE::DISPLAY,
		AppE::LOG,
		AppE::MAIL,
		AppE::SHUT,
	);

}

