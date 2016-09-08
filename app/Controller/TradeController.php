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
class TradeController extends MinikuraController
{
    const MODEL_NAME_SALES = 'Sales';

    // アクセス許可
    protected $checkLogined = false;

    // beforeRender
    public function beforeRender()
    {
        //* mypageとは違うlayoutにする
        $this->layout = 'trade';
    }


    /**
     * index
     */
    public function index()
    {

        $this->loadModel(self::MODEL_NAME_SALES);
        $sales = null;
        $id = $this->params['id'];
        $sales_result = $this->Sales->apiGet(['sales_id' => $id]);
        if (!empty($sales_result->results[0])) {
            $sales = $sales_result->results[0];
        }
        $this->set('sales', $sales);
        //CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($sales_result, true));

    }

    /**
     * 暫定 input
     */
    public function input()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);
        $this->redirect(['action' => 'index']);
    }
    /**
     * 暫定 confirm
     */
    public function confirm()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);
        $this->redirect(['action' => 'index']);

    }
    /**
     * 暫定 complete
     */
    public function complete()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);
        $this->redirect(['action' => 'index']);

    }

}
