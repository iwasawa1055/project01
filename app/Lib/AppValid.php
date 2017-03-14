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

	public static $prefs = array(
		1 => '北海道',
		2 => '青森県',
		3 => '岩手県',
		4 => '宮城県',
		5 => '秋田県',
		6 => '山形県',
		7 => '福島県',
		8 => '茨城県',
		9 => '栃木県',
		10 => '群馬県',
		11 => '埼玉県',
		12 => '千葉県',
		13 => '東京都',
		14 => '神奈川県',
		15 => '新潟県',
		16 => '富山県',
		17 => '石川県',
		18 => '福井県',
		19 => '山梨県',
		20 => '長野県',
		21 => '岐阜県',
		22 => '静岡県',
		23 => '愛知県',
		24 => '三重県',
		25 => '滋賀県',
		26 => '京都府',
		27 => '大阪府',
		28 => '兵庫県',
		29 => '奈良県',
		30 => '和歌山県',
		31 => '鳥取県',
		32 => '島根県',
		33 => '岡山県',
		34 => '広島県',
		35 => '山口県',
		36 => '徳島県',
		37 => '香川県',
		38 => '愛媛県',
		39 => '高知県',
		40 => '福岡県',
		41 => '佐賀県',
		42 => '長崎県',
		43 => '熊本県',
		44 => '大分県',
		45 => '宮崎県',
		46 => '鹿児島県',
		47 => '沖縄県',
	);

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
						new AppTerminalError(AppE::PARAMETER_INVALID . $param_name, 400);
					}
					if ($_REQUEST[$param_name] === '') {
						new AppTerminalError(AppE::PARAMETER_INVALID . $param_name, 400);
					}
					$requests[$param_name] = $_REQUEST[$param_name];
					continue;
				}
				//** Options
				if ($param_type === 'options') {
					if (isset($_REQUEST[$param_name])) {
						$requests[$param_name] = $_REQUEST[$param_name];
					} else {
						if ($default_value !== null && $default_value !== '') {
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
				//new AppTerminalInfo(AppE::PARAMETER_UNKNOWN . $request_key, 200);
			}
		}

		return $requests;
	}

	public static function isStringInteger(&$_value)
	{
		return ! preg_match('/^(?:\d|[1-9][\d]+)$/', $_value) ? false : true;
	}

	public static function isStringIntegerRange(&$_value, $_min, $_max)
	{
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
		return preg_match('/[^' . self::$kanji . self::$hiragana . ']/u', $_value) ? false : true;
	}

	public static function isAscii_Kanji_Hiragana_FwKana(&$_value)
	{
		return preg_match('/[^' . self::$ascii . self::$kanji . self::$hiragana . self::$fw_kana . ']/u', $_value) ? false : true;
	}

	public static function isFwKana(&$_value)
	{
		return preg_match('/[^' . self::$fw_kana . ']/u', $_value) ? false : true;
	}

	public static function isAscii(&$_value)
	{
		return preg_match('/[^' . self::$ascii . ']/u', $_value) ? false : true;
	}

	public static function isAlphaNumI(&$_value)
	{
		return preg_match('/[^a-z\d]/i', $_value) ? false : true;
	}

	public static function isBinary(&$_value)
	{
		// 暫定：あとでやる
		return false === strpos($_value, '\0') ? false : true;
	}

	public static function isMailAddress(&$_value)
	{
		$mails = explode('@', $_value);

		//* @ check
		if (2 !== count($mails)) {
			return false;
		}

		//* Local Part
		if (preg_match('/^"([\d!#$%&\'*+-.\/=?^_`{|}~a-z()<>\[\]:;,@\s]*?)([^\d!"#$%&\'*+-.\/=?^_`{|}~\\a-z()<>\[\]:;,@\s]+?|(?<!\\\)")([\d!#$%&\'*+-.\/=?^_`{|}~a-z()<>\[\]:;,@\s]*?)"$|^(?<!")([\d!#$%&\'*+\-.\/=?^_`{|}~a-z]*?)([^\d!#$%&\'*+\-.\/=?^_`{|}~a-z]+?)([\d!#$%&\'*+\-.\/=?^_`{|}~a-z]*?)(?!")$|^"[^"]+?(?!")$|^(?<!")[^"]+?"$|^\.|\.$|^(?<!")\.{2,}(?!")$/i', $mails[0])) {
			return false;
		}

		//* Domain Part
		if (preg_match('/^\[(?!(\d|[1-9]\d|1[\d][\d]|2[0-5][0-5])\.(\d|[1-9]\d|1[\d][\d]|2[0-5][0-5])\.(\d|[1-9]\d|1[\d][\d]|2[0-5][0-5])\.(\d|[1-9]\d|1[\d][\d]|2[0-5][0-5])\]$)|^[^\[].+?\.[a-z]*?[^a-z.]+?[a-z]*?(?!\])$|^(?<!\[)[\d\-.a-z]*?[^\d\-.a-z]+?[\d\-.a-z]*?(?!\])$|^\.|\.$|\.{2,}|^-|-$|-{2,}|-\.|\.-/i', $mails[1])) {
			return false;
		}

		if (64 < strlen($mails[0])) {
			return false;
		}

		if (253 < strlen($mails[1])) {
			return false;
		}

		if (254 < strlen($_value)) {
			return false;
		}

		return true;
	}

	public static function isDate(&$_value)
	{
		//* Format Check
		if (! preg_match('/^(?:[1-2]\d\d\d)-(?:0[1-9]|1[0-2])-(?:0[1-9]|[1-2]\d|3[0-1])$/', $_value)) {
			return false;
		}

		//* Date Check
		$parts = explode('-', $_value);
		if (! checkdate(intval($parts[1]), intval($parts[2]), intval($parts[0]))) {
			return false;
		}

		return true;
	}

	public static function isUri(&$_value)
	{
		// 暫定：あとでやる
		return preg_match('/[^' . self::$ascii . ']/u', $_value) ? false : true;
	}

	public static function isHost(&$_value)
	{
		// あとでやる
		//* Domain Part
//		if (preg_match('/^\[(?!(\d|[1-9]\d|1[\d][\d]|2[0-5][0-5])\.(\d|[1-9]\d|1[\d][\d]|2[0-5][0-5])\.(\d|[1-9]\d|1[\d][\d]|2[0-5][0-5])\.(\d|[1-9]\d|1[\d][\d]|2[0-5][0-5])\]$)|^[^\[].+?\.[a-z]*?[^a-z.]+?[a-z]*?(?!\])$|^(?<!\[)[\d\-.a-z]*?[^\d\-.a-z]+?[\d\-.a-z]*?(?!\])$|^\.|\.$|\.{2,}|^-|-$|-{2,}|-\.|\.-/i', $_val)) {
//			return false;
//		}

		if (253 < strlen($_value)) {
			return false;
		}

		return true;
	}

	public static function isIpv4(&$_value)
	{
		return preg_match('/^(?:(?:\d|\d\d|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d|\d\d|1\d\d|2[0-5][0-5])$/', $_value) ? true : false;
	}

	public static function isIpv4Cidr(&$_value)
	{
		return preg_match('/^(?:(?:\d|\d\d|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d|\d\d|1\d\d|2[0-5][0-5])\/(?:\d|[1-2]\d|3[0-2])$/', $_value) ? true : false;
	}

	public static function isPort(&$_value)
	{
		if (! is_int($_value)) {
			return false;
		}

		if ($_value < 0 || $_value > 65535) {
			return false;
		}

		return true;
	}


	public static function isCreditCardNumber(&$_value)
	{
		if (! preg_match('/^[1-9]\d{13,15}$/', $_value)) {
			return false;
		}
		return true;
	}

	public static function isCreditCardHolderName(&$_value)
	{
		if (preg_match('/[^\dA-Z ]/', $_value)) {
			return false;
		}
		return true;
	}

	public static function isCreditCardPassword(&$_value)
	{
		if (preg_match('/[^\da-z]/i', $_value)) {
			return false;
		}
		return true;
	}

	public static function isCreditCardSecurityCode(&$_value)
	{
		if (! preg_match('/^\d{3,4}$/', $_value)) {
			return false;
		}
		return true;
	}

	public static function isCreditCardExpireReverse(&$_value)
	{
		//** Format Check
		if (! preg_match('/^(?:0[1-9]|1[0-2])\d\d$/', $_value)) {
			return false;
		}

		//** Date Check
		$m = substr($_value, 0, 2);
		$y = substr($_value, 2, 2);
		if (! checkdate(intval($m), 1, intval($y))) {
			return false;
		}
		$ym = $y . $m;

		//** Backward Check
		if ($ym < date('ym')) {
			return false;
		}

		//** Future Check 50 years
		$now_y = date('y');
		$future_y = intval($now_y) + 50;
		if (intval($y) > $future_y) {
			return false;
		}
		return true;
	}

	public static function isCreditCardExpireForward(&$_value)
	{
		//** Format Check
		if (! preg_match('/^\d\d(?:0[1-9]|1[0-2])$/', $_value)) {
			return false;
		}

		//** Date Check
		$y = substr($_value, 0, 2);
		$m = substr($_value, 2, 2);
		if (! checkdate(intval($m), 1, intval($y))) {
			return false;
		}
		$ym = $y . $m;

		//** Backward Check
		if ($ym < date('ym')) {
			return false;
		}

		//** Future Check 50 years
		$now_y = date('y');
		$future_y = intval($now_y) + 50;
		if (intval($y) > $future_y) {
			return false;
		}
		return true;
	}

	//ハイフンなし電話番号国内
	public static function isPhoneNumberJp(&$_value)
	{
		$_value = str_replace("-", "", $_value);
		
		if (! preg_match('/^0/', $_value)) {
			return false;
		}
		if (! preg_match('/(^(?!090|080|070|050|060|020)\d{10}$)|(^(090|080|070|050|060|020)\d{8}$)|(^0120\d{6}$)|(^0080\d{7}$)/', $_value)) {
			return false;
		}
		return true;
	}

	public static function isPostalCodeJp(&$_value)
	{
		if ( preg_match('/^\d{7}$/', $_value) || preg_match('/^\d{3}-\d{4}$/', $_value)) {
			return true;
		} else {
			//* NG
			return false;
		}
	}

	public static function isPrefNameJp(&$_value, $_pref_pos = null)
	{
		foreach (self::$prefs as $pref_name) {
			if (false === ($pref_pos = mb_strpos($_value, $pref_name))) {
				continue;
			}

			if (null === $_pref_pos) {
				return $pref_name;
			} else {
				if ($_pref_pos === $pref_pos) {
					return $pref_name;
				}
			}
		}
		return false;
	}

	public static function isDatetimeDelivery(&$_value)
	{
		if (! preg_match('/^\d{4}-\d{2}-\d{2}(-\d{1})?$/', $_value)) {
			return false;
		}
		$array_ymd = explode('-', $_value);
		$ymd = $array_ymd[0].'-'.$array_ymd[1].'-'.$array_ymd[2];
		if (! AppValid::isDate($ymd)) {
			return false;
		}
		return true;
	}

	public static function isLoginPassword(&$_value)
	{
		if (! preg_match('/^[0-9a-zA-Z!,.:?@^_~]{6,64}$/', $_value)) {
			return false;
		}
		return true;
	}

	/// ＡＰＩ用の日時コードかどうかチェック
	/// 【例】2012-09-25-2（2012/09/25 (火) 午前中）
	/// 2012-09-25のように時間帯なしの場合もある
	public static function isDatetimeCd($_value)
	{
		$params = explode('-', $_value);
		if (count($params) == 4) {  // YYYY-MM-DD-時間帯
			// 年月日チェック
			if (!checkdate($params[1], $params[2], $params[0])) {
				return false;
			}
			// 時間帯コードチェック
			if (!(2 <= $params[3] and $params[3] <= 7)) {
				return false;
			}
		}
		elseif (count($params) == 3) {  // YYYY-MM-DD
			// 年月日チェック
			if (!checkdate($params[1], $params[2], $params[0])) {
				return false;
			}
		}
		else {
			return false;
		}

		return true;
	}

	/**
	 * 全角スペース チェック
	 *
	 * @access      public
	 * @param       バリデーション
	 * @return      boolean
	 */
	public static function checkFullWordSpace($value)
	{
		// 全角スペースかどうか
		if (preg_match('/^[　]{1,}$/', $value)) {
			return false;
		}
		// すべて全角スペースではない
		return true;
	}

	/**
	 * 半角スペース チェック
	 *
	 * @access      public
	 * @param       バリデーション
	 * @return      boolean
	 */
	public static function checkHalfWordSpace($value)
	{
		// 半角スペースかどうか
		if (preg_match('/^[ ]{1,}$/', $value)) {
			return false;
		}
		// すべて半角スペースではない
		return true;
	}

	/**
	 * 共通 validation
	 * $_requestsの内容をチェックする。フォーマットは
	 * $_requests = [
	 *     'keyhoge' => valuehoge,
	 *     'keydoge' => valuedoge,
	 * ]
	 * の連想配列
	 * どんなチェックをするかはkey名に依存する。
	 *
	 * $ret = [
	 *     '引っかかったキー' => 'エラーメッセージ'
	 * ]
	 * のフォーマットで返る(エラーなしなら空の配列を返す)。
	 *
	 * $_excludesにはチェックしないキー名を入れる
	 */
	public function validate($_requests, array $_excludes = array())
	{
		$ret = [];
		//* Detail Validation
		foreach ($_requests as $name => $value) {
			//* Exclusion
			if (in_array($name, $_excludes)) {
				continue;
			}
			//* Case
			switch (true) {
				case $name === 'lastname':
				case $name === 'firstname':
					$check_space_error = false;
					if ($value == '') {
						$check_space_error = true;
					}
					// 全角スペースチェック
					if (! self::checkFullWordSpace($value)) {
						$check_space_error = true;
					}
					// 半角スペースチェック
					if (! self::checkHalfWordSpace($value)) {
						$check_space_error = true;
					}

					// 空白、スペースエラー
					if ($check_space_error) {
						$msg_add = '';
						switch ($name) {
							case 'lastname':
								$msg_add = '苗字を';
								break;
							case 'firstname':
								$msg_add = '名前を';
								break;
							default:
								break;
						}
						$ret[$name] = $msg_add . '入力してください。';
					}
					elseif (29 < mb_strlen($value)){
						$ret[$name] = '29文字以内で入力してください。';
					}
					break;
				case $name === 'lastname_kana':
				case $name === 'firstname_kana':
					$check_space_error = false;
					if ($value == '') {
						$check_space_error = true;
					}
					// 全角スペースチェック
					if (! self::checkFullWordSpace($value)) {
						$check_space_error = true;
					}
					// 半角スペースチェック
					if (! self::checkHalfWordSpace($value)) {
						$check_space_error = true;
					}

					// 空白、スペースエラー
					if ($check_space_error) {
						$msg_add = '';
						switch ($name) {
							case 'lastname_kana':
								$msg_add = '苗字カナを';
								break;
							case 'firstname_kana':
								$msg_add = '名前カナを';
								break;
							default:
								break;
						}
						$ret[$name] = $msg_add . '入力してください。';
					}
					if (! self::isFwKana($value)) {
						$ret[$name] = 'カナの入力をご確認ください。';
					}
					elseif (29 < mb_strlen($value)){
						$ret[$name] = '29文字以内で入力してください。';
					}
					break;
				case $name === 'postal':
					if (! self::isPostalCodeJp($value)) {
						$ret[$name] = '郵便番号の入力をご確認ください。';
					}
					break;
				case $name === 'pref':
					if (! in_array($value, self::$prefs, 'true')) {
						$ret[$name] = '都道府県の入力をご確認ください。';
					}
					break;
				case $name === 'address1':
					if ($value == '') {
						$ret[$name] = '住所を入力してください。';
					}
					elseif (8 < mb_strlen($value)) {
						$ret[$name] = '8文字以下で入力してください。';
					}
					// 全角スペースチェック
					if (! self::checkFullWordSpace($value)) {
						$ret[$name] = '住所を入力してください。';
					}
					// 半角スペースチェック
					if (! self::checkHalfWordSpace($value)) {
						$ret[$name] = '住所を入力してください。';
					}
					break;
				case $name === 'address2':
					if ($value == '') {
						$ret[$name] = '町村番地を入力してください。';
					}
					elseif (18 < mb_strlen($value)) {
						$ret[$name] = '18文字以下で入力してください。';
					}
					// 全角スペースチェック
					if (! self::checkFullWordSpace($value)) {
						$ret[$name] = '町村番地を入力してください。';
					}
					// 半角スペースチェック
					if (! self::checkHalfWordSpace($value)) {
						$ret[$name] = '住所を入力してください。';
					}
					break;
				case $name === 'address3': // ※建物名はブランクOK
					if (30 < mb_strlen($value)) {
						$ret[$name] = '30文字以下で入力してください。';
					}
					break;
				case $name === 'tel1':
				case $name === 'tel2':
					if (! self::isPhoneNumberJp($value)) {
						$ret[$name] = '電話番号の入力をご確認ください。';
					}
					break;
				case $name === 'email':
				case $name === 'email_confirm':
					if ($value == '') {
						$ret[$name] = 'メールアドレスを入力してください。';
					}
					elseif (! self::isMailAddress($value)) {
						$ret[$name] = 'メールアドレスの入力をご確認ください。';
					}
					break;
				case $name === 'password':
				case $name === 'password_confirm':
				case $name === 'new_password':
				case $name === 'now_password':
					if (preg_match('/[^\da-z!,.:?@^_~]/i', $value)) {
						$ret[$name] = 'パスワードに使用できない文字があります。再入力をお願いします';
					}
					else if (6 > strlen($value) || strlen($value) > 64) {
						$ret[$name] = 'パスワードは6文字以上64文字以下です。';
						//$ret[$name] = 'パスワードが一致しません。';
					}
					break;
				case $name === 'birth':
					if (! self::isDate($value)) {
						$ret[$name] = '生年月日の入力をご確認ください。';
					}
					//* Future Check
					if ($value > date('Y-m-d')) {
						$ret[$name] = '生年月日の入力をご確認ください。';
					}

					//* Older Check
					$parts = explode('-', $value);
					if (130 < (intval(date('Y')) - intval($parts[0]))) {
						$ret[$name] = '生年月日の入力をご確認ください。';
					}
					break;
				case $name === 'alliance_cd':
					// 未記入用を許容
					if ($value == '') {
						break;
					}
					if (! preg_match('/^[a-zA-Z0-9_-]+$/', $value)) {
						$ret[$name] = '入力をご確認ください。';
					}
					break;
				case $name === 'offset':
					if (! preg_match('/^\d$|^[1-9][\d]*$/', $value)) {
						$ret[$name] = '入力をご確認ください。';
					}
					break;
				case $name === 'limit':
					if (! preg_match('/^\d$|^[1-9][\d]*$/', $value)) {
						$ret[$name] = '入力をご確認ください。';
					}
					else if (1000 < $value){
						$ret[$name] = '1000以内の数字を入力してください。';
					}
					break;
				case $name === 'item_id':
					if (! preg_match('/^[A-Z\-]{2,}\d{3,20}[A-Z]{0,}/', $value)) {
						$ret[$name] = '入力内容をご確認ください。';
					}
					break;
				case $name === 'box':
					$box_data = explode(',', $value);

					//* Count Check
					if (50 < count($box_data)) {
						//new AppTerminalError(AppE::PARAMETER_INVALID . $name . ' : The boxes maximum number can be up to 50 counts.', 400);
						$ret[$name] = '50件以内で入力してください。';
					}
					break;
				case $name === 'product':
					$product_data = explode(',', $value);

					//* Count Check
					if (100 < count($product_data)) {
						//new AppTerminalError(AppE::PARAMETER_INVALID . $name . ' : The products maximum number can be up to 100 counts.', 400);
						$ret[$name] = '100件以内で入力してください。';
					}
					//* Format Check
					for ($i = 0; $i < count($product_data); $i++) {
						if (preg_match('/[^\dA-Z\-:]/i', $product_data[$i])) {
							$ret[$name] = '入力内容をご確認ください。';
							break;
						}
					}
					break;
				case $name === 'approval':
				case $name === 'newsletter':
					if (! preg_match('/^[01]{1}$/', $value)) {
						$ret[$name] = '入力内容をご確認ください。';
					}
					break;
				case $name === 'gender':
					if (! in_array($value, array('m', 'f'), true) ) {
						$ret[$name] = '入力内容をご確認ください。';
					}
					break;
				case $name === 'card_no':
					if(!self::isCreditCardNumber($value)){
						$ret[$name] = 'クレジットカード番号をご確認ください。';
					}
					break;
				case $name === 'security_cd':
					if(!self::isCreditCardSecurityCode($value)){
						$ret[$name] = 'セキュリティコードをご確認ください。';
					}
					break;
				case $name === 'holder_name':
					if ($value == '') {
						$ret[$name] = 'クレジットカード名義を入力してください。';
					}
					elseif(!self::isCreditCardHolderName($value)){
						$ret[$name] = 'クレジットカード名義をご確認ください。';
					}
					break;
				case $name === 'expire':
					if(!self::isCreditCardExpireReverse($value)){
						$ret[$name] = 'カードの有効期限をご確認ください。';
					}
					break;
				case $name === 'kit_1':
				case $name === 'kit_6':
					if (! preg_match('/^[\d]*$/', $value)) {
						$ret[$name] = '個数をご確認ください。';
					}
					break;
				case $name === 'address_id':
					if ($value == '') {
						$ret[$name] = 'お届け先を選択してください。';
					}
					break;
				case $name === 'datetime_cd':
					if ($value == '') {
						$ret[$name] = 'お届け希望日時を選択してください。';
					}
					elseif (!self::isDatetimeCd($value)) {
						$ret[$name] = 'お届け希望日時をご確認ください。';
					}
					break;
				case $name === 'payment_method':
					if (! preg_match('/^[01]{1}$/', $value)) {
						$ret[$name] = 'お支払い方法をご確認ください。';
					}
					break;
				case $name === 'select_starter_kit':
					if ($value === 0) {
						$ret[$name] = 'ボックスを選択してください。';
					}
					break;
				case $name === 'select_oreder_mono':
					if ($value === 0) {
						$ret[$name] = 'ボックスを選択してください。';
					}
					break;
				case $name === 'select_oreder_hako':
					if ($value === 0) {
						$ret[$name] = 'ボックスを選択してください。';
					}
					break;
				case $name === 'select_oreder_cleaning':
					if ($value === 0) {
						$ret[$name] = 'ボックスを選択してください。';
					}
					break;
			}
		}

		//* Return
		return $ret;
	}
   
    /// 利用規約に同意するチェックがされているか判定
    public static function validateTermsAgree($remember)
    {
        $ret = [];
        if ($remember != 'Remember Me') {
            $ret['remember'] = '利用規約への同意は必須です';
        }
        return $ret;
    }

}

