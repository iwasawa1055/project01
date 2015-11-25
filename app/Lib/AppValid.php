<?php

class AppValid
{

	// Kanji: \x{3005}\x{3007}\x{303B}\x{3400}-\x{4DBF}\x{4E00}-\x{9FFF}\x{F900}-\x{FAFF}\x{20000}-\x{2FFFF}
	//      : incorrect \p{Han}, [一-龠]
	// HIragana: \x{3041}-\x{3096}
	// Fw-Kana: \x{30A1}-\x{30FF}
	// ・: \x{30FB}
	// ー: \x{30FC}
	public static $ascii = '\x20-\x7F';
	public static $kanji = '\x{3005}\x{3007}\x{303B}\x{3400}-\x{4DBF}\x{4E00}-\x{9FFF}\x{F900}-\x{FAFF}\x{20000}-\x{2FFFF}';
	public static $hiragana = '\x{3000}-\x{303F}\x{3041}-\x{3096}\x{30FB}-\x{30FF}';
	public static $fw_kana = '\x{3000}-\x{303F}\x{30A1}-\x{30FF}';
	public static $hw_kana = '\x{FF61}-\x{FF9F}';
	public static $fw_space = '\x{3000}';
	public static $roma_num = '\x{2160}-\x{2184}';
	public static $fw_mark = '\x{3220}-\x{33FE}';
	public static $fw_ascii = '\x{FF01}-\x{FF60}';

	public static function checkType($_params)
	{
		//* Entry Point Session Prefix
		$session_prefix = Configure::read('app.session.entry_point');

		//* Query Config & Common Validation
		$known_keys = array();
		$requests = array();
		foreach ($_params as $param_type => $params) {
			foreach ($params as $param_name => $default_value) {
				$known_keys[] = $param_name;
				//** Statics
				if ($param_type === 'statics') {
					$requests[$param_name] = $default_value;
					continue;
				}
				//** Sessions
				if ($param_type === 'sessions') {
					$requests[$param_name] = $default_value;
					continue;
				}
				//** Musts
				if ($param_type === 'musts') {
					if (! isset($_REQUEST[$param_name])) {
						new AppTerminalWarning(AppE::PARAMETER_INVALID . $param_name, 400);
					}
					if ($_REQUEST[$param_name] === '') {
						new AppTerminalWarning(AppE::PARAMETER_INVALID . $param_name, 400);
					}
					$requests[$param_name] = $_REQUEST[$param_name];
					continue;
				}
				//** Options
				if ($param_type === 'options') {
					if (isset($_REQUEST[$param_name])) {
						$requests[$param_name] = $_REQUEST[$param_name];
					} else {
						if ($default_value !== null) {
							$requests[$param_name] = $default_value;
						}
					}
					continue;
				}
			}
		}
	
		//* Unknown Parameter Key Check
		foreach ($_REQUEST as $request_key => $value) {
			if ($request_key === 'request_method' || $request_key === 'debug') {
				continue;
			}
			if (! in_array($request_key, $known_keys)) {
				new AppTerminalInfo(AppE::PARAMETER_UNKNOWN . $request_key, 200);
			}
		}

		return $requests;
	}

	public static function isStringInteger(&$_value)
	{
		$_value = mb_convert_kana($_value, 'n');
		return ! preg_match('/^(?:\d|[1-9][\d]+)$/', $_value) ? false : true;
	}

	public static function isStringIntegerRange(&$_value, $_min, $_max)
	{
		$_value = mb_convert_kana($_value, 'n');
		if (! preg_match('/^(?:\d|[1-9][\d]+)$/', $_value)) {
			return false;
		}
		$value = intval($_value);
		return ($_min > $value || $_max < $value) ? false : true;
	}

	public static function isJa($_value)
	{
		return preg_match('/[^' .
			self::$ascii .
			self::$kanji .
			self::$hiragana .
			self::$fw_kana .
			self::$hw_kana .
			self::$fw_space .
			self::$roma_num .
			self::$fw_mark .
			self::$fw_ascii .
		']/u', $_value) ? false : true;
	}

	public static function isKanji_Hiragana($_value)
	{
		$_value = mb_convert_kana($_value, 'cHV');
		return preg_match('/[^' . self::$kanji . self::$hiragana . ']/u', $_value) ? false : true;
	}

	public static function isAscii_Kanji_Hiragana_FwKana(&$_value)
	{
		// Fw Ascii -> Hw, Hw Kana -> Fw
		$_value = mb_convert_kana($_value, 'aKV');
		return preg_match('/[^' . self::$ascii . self::$kanji . self::$hiragana . self::$fw_kana . ']/u', $_value) ? false : true;
	}

	public static function isAscii(&$_value)
	{
		// Fw Ascii -> Hw, Hw Kana -> Fw
		$_value = mb_convert_kana($_value, 'a');
		return preg_match('/[^' . self::$ascii . ']/u', $_value) ? false : true;
	}

	public static function isAlphaNumI(&$_value)
	{
		// Fw Ascii -> Hw, Hw Kana -> Fw
		$_value = mb_convert_kana($_value, 'a');
		return preg_match('/[^a-z\d]/i', $_value) ? false : true;
	}

	public static function isBinary(&$_value)
	{
		// 暫定：あとでやる
		return false === strpos($_value, '\0') ? false : true;
	}

	public static function isMailAddress(&$_value)
	{
		// Fw Ascii -> Hw, Hw Kana -> Fw
		// 暫定：あとでやる
		$_value = mb_convert_kana($_value, 'a');
		return preg_match('/[^' . self::$ascii . ']/u', $_value) ? false : true;
	}

	public static function isPassword(&$_value)
	{
		// Fw Ascii -> Hw, Hw Kana -> Fw
		// 暫定：あとでやる
		$_value = mb_convert_kana($_value, 'a');
		return preg_match('/[^' . self::$ascii . ']/u', $_value) ? false : true;
	}

	public static function isUri(&$_value)
	{
		// Fw Ascii -> Hw, Hw Kana -> Fw
		// 暫定：あとでやる
		$_value = mb_convert_kana($_value, 'a');
		return preg_match('/[^' . self::$ascii . ']/u', $_value) ? false : true;
	}

	public static function isHost(&$_value)
	{
		// Fw Ascii -> Hw, Hw Kana -> Fw
		// 暫定：あとでやる
		$_value = mb_convert_kana($_value, 'a');
		return preg_match('/[^' . self::$ascii . ']/u', $_value) ? false : true;
	}

	public static function isIpv4(&$_value)
	{
		// Fw Ascii -> Hw, Hw Kana -> Fw
		// 暫定：あとでやる
		$_value = mb_convert_kana($_value, 'a');
		return preg_match('/[^' . self::$ascii . ']/u', $_value) ? false : true;
	}

	public static function is_x_o(&$_value)
	{
		$_value = preg_replace(array('/\x{00D7}/u', '/\x{25CB}|\x{3007}|\x{25CE}|\x{25CF}/u'), array('x', 'o'), $_value);
		$_value = mb_convert_kana($_value, 'a');
		$_value = strtolower($_value);
		$lists = array('x', 'o');
		return ! in_array($_value, $lists) ? false : true;
	}

	public static function is_yes_no(&$_value)
	{
		$_value = mb_convert_kana($_value, 'a');
		$_value = strtolower($_value);
		$lists = array('yes', 'no');
		return ! in_array($_value, $lists) ? false : true;
	}


}

