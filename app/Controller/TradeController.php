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
        //CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($sales, true));

        //* for og:image 
        if (!empty($sales)) {
            //* url  
            $replace_image_file = preg_replace('/\.jpg/', '_fb.png', $sales['item_image'][0]['image_url']);
            //CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($replace_image_file, true));

            /*
            * file_get_contents() 確認用 検証機用
            * 開発srvから画像検証srvへ接続するには以下必要
            * ゲートウェイがポートフォワーディングしている関係の為
            */
            $patterns = [];
            $patterns[0] = '/dev-image.minikura.com:10080/';
            $patterns[1] = '/dev-image.minikura.com:10443/';
            $patterns[2] = '/stag-image.minikura.com:10080/';
            $patterns[3] = '/stag-image.minikura.com:10443/';
            $patterns[4] = '/image.minikura.com/';
            $replacements = [];
            $replacements[0] = 'dev-image.minikura.lan';
            $replacements[1] = 'dev-image.minikura.lan';
            $replacements[2] = 'stag-image.minikura.lan';
            $replacements[3] = 'stag-image.minikura.lan';
            $replacements[4] = 'image.minikura.lan';
            $check_url = preg_replace($patterns, $replacements, $replace_image_file);
            //CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($check_url, true));

            if (!@file_get_contents($check_url)) {
                new AppInternalInfo('Error : found not fb.png ', $code = 500);
            } else {
                $sales['og_fb_image_url'] = $replace_image_file;
            }
        }
        $this->set('sales', $sales);
        //CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($sales, true));

    }

    /**
     * widget
     */
    public function widget()
    {
        //* widget 用
        $this->autoLayout = false;

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
