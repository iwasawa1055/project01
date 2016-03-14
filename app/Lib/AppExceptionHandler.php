<?php
App::uses('AppInternalCritical', 'Lib');
App::uses('AppExceptionRenderer', 'Lib');
App::uses('ApiCachedModel', 'Model');

/**
 * 例外ハンドラでは、必ず共通例外処理（AppEクラス）を行う様にする
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
                // 例外処理を実行するThrowはしない
                new AppInternalCritical($_e->getMessage(), $_e->getCode());
            } catch (Exception $e) {
            }
        }
        // セッション値をクリア
        ApiCachedModel::deleteAllCache();

        // 未承認、未払いはセッション破棄しログアウトする
        if (in_array($_e->getCode(), [401, 402], true)) {
            CakeSession::destroy();
            header('Location: /');
            exit;
        }

        // 例外表示
        $error = new AppExceptionRenderer($_e);
        $error->render();
    }
}
