<?php

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

		new AppInternalCritical($to_string, 500);
	}

}
