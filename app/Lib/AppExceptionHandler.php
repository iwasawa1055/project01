<?php
App::uses('AppInternalCritical', 'Lib');
App::uses('AppExceptionRenderer', 'Lib');

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

class AppExceptionHandler
{
    public static function handle($_e)
    {
        CakeLog::write(DEBUG_LOG, 'AppExceptionHandler::handle');
        if ('AppE' !== get_parent_class($_e)) {
            // AppE継承クラスではない場合はAppInternalCriticalの例外処理を行う
            try {
                // 例外処理を実行
                new AppInternalCritical($_e->getMessage(), $_e->getCode());
            } catch (Exception $e) {
            }
        }
        // セッション値をクリア
        ApiCachedModel::deleteAllCache();
        // 例外表示
        $error = new AppExceptionRenderer($_e);
        $error->render();
    }
}
