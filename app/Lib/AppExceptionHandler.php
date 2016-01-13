<?php

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
	public static function handle($_e)
	{
		// AppE継承クラスではない場合はログ出力
		if ('AppE' !== get_parent_class($_e)) {
			AppE::log();
		}

		//* view
		$error = new AppExceptionRenderer($_e);
		$error->render();
	}
}
