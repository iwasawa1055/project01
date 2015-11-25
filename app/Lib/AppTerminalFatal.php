<?php
/**
 * AppTerminalFatal
 */
class AppTerminalFatal extends AppE
{

	public $handlers = array(
		AppE::DISPLAY,
		AppE::LOG,
		AppE::MAIL,
		AppE::SHUT,
	);

}

