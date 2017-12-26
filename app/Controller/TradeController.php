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
        //CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($sales, true));
        
        //SOLD OUTか否かをViewに渡す
        $this->set('is_soldout', $this->Sales->isSoldout($sales));
        
        //販売キャンセルか否かをViewに渡す
        $is_sale_cancel = $this->Sales->isSaleCancel($sales);
        $this->set('is_sale_cancel', $is_sale_cancel);
        
        //存在しない商品指定or販売キャンセルならばHTTPステータスコードは404
        //(SEOキャッシュ対策の為)
        if (empty($sales) || $is_sale_cancel) {
            $this->response->statusCode(404);
        }
        
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

    public function ogp_image()
    {
        $this->autoRender = false;
        $this->loadModel(self::MODEL_NAME_SALES);

        $id = str_replace('.png', '', $this->params['id']);
        $sales_result = $this->Sales->apiGet(['sales_id' => $id]);
        if (!isset($sales_result->results[0])) {
            throw new NotFoundException("Not found image of sales_id: $id");
        }

        /*
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
        $replace_image_url = preg_replace($patterns, $replacements, $sales_result->results[0]['item_image'][0]['image_url']);

        //* create
        $get_image = imagecreatefromjpeg($replace_image_url);

        if ($get_image === false) {
            throw new NotFoundException("Cannot create image of url: {$replace_image_url}");
        } else {
            //* recommend for og:image  (横:縦,1.91:1)
            $width = '1528';
            $height = '800';
            $create_image = imagecreatetruecolor($width, $height);
            $background = imagecolorallocate($create_image, 0, 0, 0);
            //* 背景を透明に
            imagecolortransparent($create_image, $background);

            //* $get_imageの配置position_x  =  (1528 - 800) / 2 , position_y=0
            $position_x = ($width - $height) / 2 ;
            $position_y = 0;
            imagecopy($create_image, $get_image, $position_x, $position_y, 0, 0, 800, 800);

            //* output
            header('Content-Type: image/png');
            imagepng($create_image);

            //* メモリから開放
            imagedestroy($create_image);
        }
    }
}
