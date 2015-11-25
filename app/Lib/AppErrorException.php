<?php

class AppErrorException extends Exception
{

	public static function view($_e)
	{
		// HTTP Response
		$http_status = '500 Internal Server Error';
		header('HTTP/1.0 ' . $http_status, true);

		// Error Log Type
		$type = ini_get('error_log') ? 0 : 4;
		// Error Log
		error_log($_e . "\n", $type);
	}

}

