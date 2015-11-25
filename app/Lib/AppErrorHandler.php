<?php

App::uses('AppApi', 'Lib');

/**
 * Final Error ハンドラー
 *
 * 言語レベル、Fatal エラー捕捉処理
 * CakePHP より権限奪取
 * 最終エラー処理のため、極力ライブラリ等使用せず、自前ロジックで実装すべき
 * （エラ処理ーのエラーで本来のエラーを不透明にしないため）
 */
class AppErrorHandler
{

	public static function handle($_level, $_message, $_file, $_line, $_traces)
	{
		//* Error Type
		$error_type = 'App Internal Fatal Error';

		//* To String
		$traces = debug_backtrace();
		$stack = '';
		foreach ($traces as $i => $trace) {
			$num = '#' . $i . ' ';
			$file = isset($trace['file']) ? $trace['file'] : '';
			$line = isset($trace['line']) ? '(' . $trace['line'] . '): ': '';
			$class = isset($trace['class']) ? $trace['class'] : '';
			$type = isset($trace['type']) ? $trace['type'] : '';
			$func = isset($trace['function']) ? $trace['function'] : '';
			$stack .= $num . $file . $line . $class . $type . $func . "\n";
		}
		$to_string = $_message . " in " . $_file . ": " . $_line . "\n" .
		"Stack Trace:" . "\n" . 
		$stack;

		$ref_code = microtime(true);
		$http_code = 500;
		$str = "\n";
		$str .= '[' . date('Y-m-d H:i:s') . ']' . "\n";
		$str .= 'App Internal Core (-1) Error' . "\n";
		$str .= 'Request UTC: ' . (isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : '') . "\n";
		$str .= 'Refers Code: ' . $ref_code . "\n";
		$str .= 'Server Name: ' . $_SERVER['SERVER_NAME'] . "\n";
		$str .= 'Server IPv4: ' . $_SERVER['SERVER_ADDR'] . "\n";
		$str .= 'Request URL: ' . $_SERVER['REQUEST_URI'] . "\n";
		$str .= 'Referer: ' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '') . "\n";
		$str .= 'Remote Host: ' . (isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : '') . "\n";
		$str .= 'Remote IPv4: ' . $_SERVER['REMOTE_ADDR'] . "\n";
		$str .= 'User Agent: "' . (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '') . '"' . "\n";
		$str .= 'HTTP Code: ' . $http_code . "\n";
		$str .= 'Message: ' . $to_string . "\n\n";

		if (Configure::read('debug')) {
			echo "\n\n" . $to_string;
		}
		CakeLog::write(ERROR_LOG, $str);

		$status = false;
		$message = 'Internal Server Error';
		$results = array();
		$results['support'] = $ref_code;
		$results['reference'] = '';
		AppApi::respond($status, $results, $message, $http_code, $error_type, $to_string);
	}

}

