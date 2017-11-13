<?php
App::uses('AppInternalCritical', 'Lib');
App::uses('AppExceptionRenderer', 'Lib');
App::uses('ApiCachedModel', 'Model');
App::uses('CakeSession', 'Model/Datasource');

/**
 * 例外ハンドラでは、必ず共通例外処理（AppEクラス）を行う様にする
 *
 */

class AppExceptionHandler
{
    public static function handle($_e)
    {
        if ('AppE' !== get_parent_class($_e)) {
            // AppE継承クラスではない場合はAppInternalCriticalの例外処理を行う
            try {
                switch (true) {
                    // pngController, cssControllerなどのエラーが.comで多発しているようなので、一旦メール発報しないように暫定
                    // メール発報させたくないExceptionを追加
                    case $_e instanceof MissingControllerException:
                        new AppInternalInfo($_e->getMessage(), $_e->getCode(), $_e);
                        break;
                    case $_e instanceof MissingActionException:
                        new AppInternalInfo($_e->getMessage(), $_e->getCode(), $_e);
                        break;
                    //404系 CakeがNotFoundExceptionをthrowしてくる=>AppTerminal系の処理で適宜対応する(メールは発報しないなど)
                    case $_e instanceof NotFoundException:
                        new AppTerminalInfo($_e->getMessage(), $_e->getCode(), $_e);
                        break;
                    //メール発報します
                    default:
                        new AppInternalCritical($_e->getMessage(), $_e->getCode(), $_e);
                        break;
                }
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
