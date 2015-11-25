<?php

App::uses('AppApi', 'Lib');
App::uses('AppHttp', 'Lib');
App::uses('AppSecurity', 'Lib');

/**
 * Final Exception Handler
 *
 * ファイナル例外ハンドラー
 * 未捕捉の例外最終処理
 * CakePHP より権限奪取
  * 最終エラー処理のため、極力ライブラリ等使用せず、自前ロジックで実装すべき
 * （エラ処理ーのエラーで本来のエラーを不透明にしないため）
 *
 */
class AppExceptionHandler extends Exception
{

	public static function handle($_e, $_mail = false)
	{
		//* Error Type
		$error_type = ! empty($_e->error_type) ? $_e->error_type : 'App Su Error';

		//* HTTP Code
		$http_code = isset($_e->http_code) ? $_e->http_code : (isset($_e->code) ? $_e->code : 500);

		//* Message
		if ($http_code === 404) {
			$_e->message = 'Not Found - url';
		}

		//* To String
		$to_string = $_e->__toString();

		//* Response Body
		//** Status
		$status = false;

		if (isset($_e->error_node)) {
			if ($_e->error_node === 'Terminal') {
				$messages = explode(':', $_e->message);
				$message = trim($messages[0]);
				$debug = isset($messages[1]) ? trim($messages[1]) : '';
			} else if ($_e->error_node === 'Internal' || $_e->error_node === 'Medial') {
				$message = 'Internal Server Error';
			} else if ($_e->error_node === 'External') {
				$message = 'External Server Error';
			} else {
				$message = 'Unknown Error';
			}
		} else {
			$message = $_e->message;
		}

		$results = array();
		if (isset($_REQUEST['debug']) && $_REQUEST['debug'] === '1') {
			$results['debug'] = $debug;
		}

        //* Content-Type
		AppHttp::respondContentLanguage(false);
        //* Click Jacking Block
        AppSecurity::blockClickJacking();

		//* Log
        if (empty($_e->log_disuse)) {
			AppE::log();
        }

		//* Mail
        if (empty($_e->mail_disuse)) {
			AppE::mail($_e);
        }   
		
		//* view
		$error = new AppExceptionRenderer($_e);
		$error->render();
	}

}

