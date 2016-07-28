<?php

App::uses('MinikuraController', 'Controller');

/**
* 販売機能 SNSなどに貼り付ける用のページ 
* ユーザーには、mypage.minikuraを見せるのではなくコンテンツドメインを見せる。SEOも考慮
* コンテンツ側のApache設定でAliasを設定している。
*
* app/Config/routes.php ルーティング設定中,
* app/webroot/.htaccess mod_rewrite設定中
* 
*/
class C2cSaleController extends MinikuraController
{
    const MODEL_NAME = 'C2c';

    // アクセス許可
    protected $checkLogined = false;


    /**
     * index
     */
    public function index()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }

    /**
     * 暫定 input
     */
    public function input()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }
    /**
     * 暫定 confirm
     */
    public function confirm()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }
    /**
     * 暫定 complete
     */
    public function complete()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);

    }

}
