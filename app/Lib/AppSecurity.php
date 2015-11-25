<?php

class AppSecurity
{
	public static $globs = array('GLOBALS', '_ENV', '_SERVER', '_POST', '_GET', '_REQUEST', '_COOKIE', '_SESSION', '_FILES');

	public static function blockAttackRequest()
	{
		try {
			foreach (self::$globs as $glob) {
				if (! isset($$glob)) {
					continue;
				}
				foreach ($$glob as $k => $v) {
					self::_checkKeyValue($k, $v);
				}
			}
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), 400);
		}
	}

	protected static function _checkKeyValue($_k, $_v)
	{
		//* Key
		//** Tainted
		foreach (self::$globs as $glob) {
			if ($_k === $glob) {
				throw new Exception('Attack - Tainted Key');
			}
		}
		//** Null Byte or Controlled Character 1
		if (preg_match('/[\x00-\x1F\x7F]/ui', $_k)) {
			throw new Exception('Attack - Null Byte or Controlled Character Key', 400);
		}
		//** Null Byte or Controlled Character 2
		if (preg_match('/\[01][0-9A-F]|\7F/ui', $_k)) {
			throw new Exception('Attack - Null Byte or Controlled Character Key', 400);
		}
		//** Null Byte or Controlled Character 3
		if (preg_match('/%[01][0-9A-F]|%7F/ui', $_k)) {
			throw new Exception('Attack - Null Byte or Controlled Character Key', 400);
		}
		if (is_array($_v)) {
			foreach ($_v as $k => $v) {
				$this->_checkKeyValue($k, $v);
			}
		//* Value
		} else {
			//** Null Byte or Controlled Character 1
			if (preg_match('/[\x00-\x1F\x7F]/ui', $_v)) {
				throw new Exception('Attack - Null Byte or Controlled Character Value', 400);
			}
			//** Null Byte or Controlled Character 2
			if (preg_match('/\[01][0-9A-F]|\7F/ui', $_v)) {
				throw new Exception('Attack - Null Byte or Controlled Character Value', 400);
			}
			//** Null Byte or Controlled Character 3
			if (preg_match('/%[01][0-9A-F]|%7F/ui', $_v)) {
				throw new Exception('Attack - Null Byte or Controlled Character Value', 400);
			}
			//** HTTP Header Injection
			if ($_k === '_SERVER' || $_k === '_COOKIE' || $_k === '_GET') {
				if (preg_match('/[\\n|\\r|%0A|%0D|\0A|\0D|\x0A|\x0D/ui', $_v)) {
					throw new Exception('Attack - HTTP Header Injuction Value', 400);
				}
			}
			//** Path Traversal
			if ($_k === '_SERVER' || $_k === '_GET') {
				if (preg_match('/\.\.|%2E|%2F|\2E|\2F|\x2E|\x2F/ui', $_v)) {
					throw new Exception('Attack - Path Traversal Value', 400);
				}
			}
			//** XSS
			if (preg_match('/SCRIPT|<|>|%3C|%3E|\3C|\3E|\x3C|\x3E/ui', $_v)) {
				throw new Exception('Attack - XSS Value', 400);
			}
		}
	}

	public static function blockClickJacking()
	{
		header('X-FRAME-OPTIONS: SAMEORIGIN', true);
	}

}

