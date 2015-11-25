<?php

class AppHttp
{

	public static $versions = array(
		'default' => 'HTTP/1.1',
		'1.0' => 'HTTP/1.0',
		'1.1' => 'HTTP/1.1',
	);

	//* Response Status Code
	public static $status_codes = array(
		'default' => 200,
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		226 => 'IM Used',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => "I'm a teapot",
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		426 => 'Upgrade Required',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not Extended',
	);

	//* Mime Type
	public static $mime_types = array(
		'default' => '*/*',
		'all' => '*/*',
		'binary' => 'application/octet-stream',
		'css' => 'text/css',
		'gif' => 'image/gif',
		'html' => 'text/html',
		'javascript' => 'application/x-javascript',
		'jpeg' => 'image/jpeg',
		'json' => 'application/json',
		'octet-stream' => 'application/octet-stream',
		'php' => 'application/octet-stream',
		'plain' => 'text/plain',
		'png' => 'image/png',
		'unknown' => 'application/octet-stream',
		'xhtml' => 'application/xhtml+xml',
		'xml' => 'application/xml',
		'yaml' => 'application/x-yaml',
	);

	public static $charsets = array(
		'default' => 'utf-8',
	);

	//* Language
	public static $languages = array(
		'default' => 'ja,en-US;q=0.7,en;q=0.3',
		'append' => ',en-US;q=0.7,en;q=0.3',
	);

	/**
	 * リクエストメソッドチェック
	 *
	 * @access		public
	 * @param		mixed $_method 許容リクエストメソッド
	 * @return		string リクエストメソッド
	 * @todo
	 */
	public static function isRequestMethod($_config_methods)
	{
		if (empty($_config_methods)) {
			new AppInternalCritical(AppE::ARGUMENT, 'No Configured request_method', 500);
		}

		if (! is_array($_config_methods)) {
			$_config_methods = array($_config_methods);
		}

		if (empty($_SERVER['REQUEST_METHOD'])) {
			new AppTerminalWarning(AppE::METHOD_NOT_ALLOWED, 405);
		}

		$query_flag = ! empty($_REQUEST['request_method']) ? true : false;

		$method = $query_flag ? strtoupper($_REQUEST['request_method']) : $_SERVER['REQUEST_METHOD'];

		if (! in_array($method, $_config_methods)) {
			new AppTerminalWarning(AppE::METHOD_NOT_ALLOWED, 405);
		}

		if ($method === 'OPTION') {
			$allow_method = implode(' ', $_config_methods);
			header('Allow: ' . $allow_method);
			exit;
		}

		return $method;
	}

	public static function request($_url, $_requests = array(), $_method = null, $_headers = array(), $_element = null, $_accept = null)
	{
		//* Accept Header
		$accepts = self::_setAcceptHeader($_accept);
		$_headers = array_merge($_headers, $accepts);
		//debug($_headers);
		//debug($_url);

		//* Request
		$curls = self::_curl($_url, $_requests, $_method, $_headers);
		//debug($curls);

		if (! $_element || $_element === 'body') {
			$result = self::_parse($curls['body'], $_accept);
		} else if ($_element === 'request_header') {
			$result = $curls['request_header'];
		} else if ($_element === 'response_header') {
			$result = $curls['response_header'];
		} else if ($_element === 'headers') {
			$result = $curls['headers'];
		} else if ($_element === 'all') {
			$result = $curls;
		}

		return $result;
	}

	protected static function _setAcceptHeader($_accept)
	{
		//* Accept Header
		$mime_type = isset(AppHttp::$mime_types[$_accept]) ? AppHttp::$mime_types[$_accept] : AppHttp::$mime_types['json'];
		$accepts = array('Accept: ' . $mime_type);
		return $accepts;
	}

	protected static function _curl($_url, $_requests, $_method = null, $_headers = array())
	{
		//* Args No Check
		$yahoo_stream = curl_init();
		$query = http_build_query($_requests);
		//debug($_url);
		//debug($query);

		//* Option
		//** Common
		$options = array();
		$options[CURLOPT_USERAGENT] = Configure::check('app.user_agent') ? Configure::read('app.user_agent') : '';
		$options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;
		$options[CURLOPT_RETURNTRANSFER] = true;
		$options[CURLINFO_HEADER_OUT] = true;
		$options[CURLOPT_HEADER] = true;

		//** Headers
		if (! empty($_headers)) {
			$options[CURLOPT_HTTPHEADER] = $_headers;
		}

		//** Method
		if (! $_method || $_method === 'GET') {
			$options[CURLOPT_URL] = $_url . '?' . $query;
			$options[CURLOPT_HTTPGET] = true;
		} else if ($_method === 'POST') {
			$options[CURLOPT_URL] = $_url;
			$options[CURLOPT_POSTFIELDS] = $query;
			$options[CURLOPT_POST] = true;
		} else if ($_method === 'POST_BIN') {
			$options[CURLOPT_URL] = $_url;
			$options[CURLOPT_POST] = true;
			$options[CURLOPT_POSTFIELDS] = $query;
			$options[CURLOPT_BINARYTRANSFER] = true;
		} else if ($_method === 'PUT') {
			$options[CURLOPT_PUT] = true;
		} else if ($_method === 'PATCH') {
			// Task
		} else if ($_method === 'DELETE') {
			// Task
		}

		//** Option Set
		curl_setopt_array($yahoo_stream, $options);

		//* Transfer
		if (! $responses = curl_exec($yahoo_stream)) {
			new AppMedialCritical(AppE::CONNECTION . 'Could not connect external server', 500);
		}
		//debug($responses);

		//* Results
		$results['headers'] = curl_getinfo($yahoo_stream);
		$results['request_header'] = curl_getinfo($yahoo_stream, CURLINFO_HEADER_OUT);
		$header_size = curl_getinfo($yahoo_stream, CURLINFO_HEADER_SIZE);
		$results['response_header'] = substr($responses, 0, $header_size);
		$results['body'] = substr($responses, $header_size);
		curl_close($yahoo_stream);

		return $results;
	}

	protected static function _parse($_content, $_accept = null)
	{
		//* Args No Check
		//debug($_content);
		//* php
		if ($_accept === 'php') {
			if (! $results = unserialize($_content)) {
				new AppInternalCritical(AppE::FUNC . 'Could not unserialize', 500);
			}
		//* json
		} else if ($_accept === 'json') {
			//* Patch For Asteria
			$_content = preg_replace(array('/^{record:\[/', '/\]}$/'), array('[', ']'), $_content);
			//* Patch For Yahoo
			$_content = preg_replace(array('/^loaded\(/', '/\)$/'), array(''), $_content);
			//* 汎用
			if (! $results = json_decode(trim($_content), true)) {
				new AppInternalCritical(AppE::FUNC . 'Could not decode json', 500);
			}
		//* xml
		} else if ($_accept === 'xml') {
			if (! $SimpleXml = simplexml_load_string(mb_convert_encoding($_content, 'UTF-8', 'SJIS'))) {
				new AppInternalCritical(AppE::FUNC . 'Could not decode xml', 500);
			}
			if (! $results = json_decode(json_encode($SimpleXml), true)) {
				new AppInternalCritical(AppE::FUNC . 'Could not cast array from object', 500);
			}
		//* yaml
		} else if ($_accept === 'yaml') {
			if (! $results = yaml_parse($_content)) {
				new AppInternalCritical(AppE::FUNC . 'Could not decode yaml', 500);
			}
		//* other
		} else {
			$results = $_content;
			// Task
		}

		return $results;
	}

	public static function respond($_status, $_results, $_message = '', $_http_code = 200, $_reference = null)
	{
		$version = self::_getApiVersion();
		$version = strtoupper($version);
		$results = $version::makeResults($_status, $_results, $_message, $_http_code, $_reference);
		$result = $version::parse($results);
		$version::feed($result);
		exit;
	}

	protected static function _getApiVersion()
	{
		if (! $version = Configure::read('app.url.version')) {
			new AppInternalCritical(AppE::CONFIG, 'app.url.version', 500);
		}
		return $version;
	}

	public static function respondStatusCode($_return = false, $_code = null, $_version = null)
	{
		if ($_code === null) {
			$code = self::$status_codes['default'];
			$staus = self::$status_codes[$code];
		} else {
			$code = isset(self::$status_codes[$_code]) ? $_code : self::$status_codes['default'];
			$status = isset(self::$status_codes[$_code]) ? self::$status_codes[$_code] : self::$status_codes[self::$status_codes['default']];
		}

		if ($_version === null) {
			$version = self::$versions['default'];
		} else {
			$version = isset(self::$versions[$_version]) ? self::$versions[$_version] : self::$versions['default'];
		}

		$status_code = $version . ' ' . $code . ' ' . $status;

		if ($_return) {
			return $status_code;
		} else {
			header($status_code);
		}
	}

	public static function getAcceptMime()
	{
		if (! isset($_SERVER['HTTP_ACCEPT'])) {
			return false;
		}

		$server = str_replace(' ', '', $_SERVER['HTTP_ACCEPT']);
		$accepts = explode(',', $server);
		$types = array();
		foreach ($accepts as $accept) {
			if (! preg_match('/;q=(.+?),|;q=(.+?)$/i', $accept, $matches)) {
				$types[] = $accept;
			} else {
				$types[$matches[1]] = $accept;
			}
		}

		$mimes = explode('/', $types[0]);
		$mime = str_replace('x-', '', $mimes[0]);
		return $mime;
	}

	public static function respondContentType($_return = false, $_mime = null, $_charset = null)
	{
		if ($_mime === null) {
			$mime_type = isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : self::$mime_types['unknown'];
		} else {
			$mime_type = isset(self::$mime_types[$_mime]) ? self::$mime_types[$_mime] : self::$mime_types['unknown'];
			$mime_type .= ',*/*;q=0.9';
		}

		if ($_charset === null) {
			$charset = isset($_SERVER['HTTP_ACCEPT_CHARSET']) ? $_SERVER['HTTP_ACCEPT_CHARSET'] : self::$charsets['default'];
		} else {
			$charset = ! empty($_charset) ? $_charset : self::$charactersets['default'];
		}

		$content_type = 'Content-type: ' . $mime_type . '; charset=' . $charset;

		if ($_return) {
			return $content_type;
		} else {
			header($content_type);
		}
	}

	public static function respondContentLanguage($_return = false, $_lang = null)
	{
		if ($_lang === null) {
			$lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : self::$languages['default'];
		} else {
			$lang = $_lang . self::$languages['append'];
		}
		$content_language = 'Content-Language: ' . $lang;

		if ($_return) {
			return $content_language;
		} else {
			header($content_language);
		}
	}


}

