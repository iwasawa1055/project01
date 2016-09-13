<?php

App::uses('MinikuraController', 'Controller');
App::uses('OutboundList', 'Model');
App::uses('AppFile', 'Lib');

class ItemController extends MinikuraController
{
    const MODEL_NAME = 'InfoItem';
    const MODEL_NAME_ITEM_EDIT = 'Item';
    const MODEL_NAME_SALES = 'Sales';

    protected $paginate = array(
        'limit' => 20,
        'paramType' => 'querystring'
    );

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
        $this->loadModel('InfoBox');
        $this->loadModel(self::MODEL_NAME_ITEM_EDIT);
        $this->loadModel(self::MODEL_NAME_SALES);

        $this->set('sortSelectList', $this->makeSelectSortUrl());
        $this->set('select_sort_value', Router::reverse($this->request));
    }

    private function makeSelectSortUrl()
    {
        // 並び替え選択
        $selectSortKeys = [
            'box_id' => __('box_id'),
            'box_name' => __('box_name'),
            'item_id' => __('item_id'),
            'item_name' => __('item_name'),
            'item_status' => __('item_status'),
            // 'item_group_cd' => __('item_group_cd'),
        ];

        // 出庫済み　hide_outbound=0：表示、hide_outbound=1：非表示、初期表示：非表示
        $withOutboundDone = !empty(Hash::get($this->request->query, 'hide_outbound', 1));
        $page = $this->request->query('page');
        $data = [];
        foreach ($selectSortKeys as $key => $value) {
            $desc = Router::url(['action'=>'index', '?' => ['order' => $key, 'direction' => 'desc', 'hide_outbound' => $withOutboundDone, 'page' => $page]]);
            $data[$desc] = $value . __('select_sort_desc');
            $asc = Router::url(['action'=>'index', '?' => ['order' => $key, 'direction' => 'asc', 'hide_outbound' => $withOutboundDone, 'page' => $page]]);
            $data[$asc] = $value . __('select_sort_asc');
        }

        return $data;
    }

    private function getProductName($_product)
    {
        $productName = '';
        if ($_product === 'mono') {
            $productName = 'minikuraMONO';
        } else if ($_product === 'hako') {
            $productName = 'minikuraHAKO';
        } else if ($_product === 'cargo01') {
            $productName = 'minikura CARGO じぶんでコース';
        } else if ($_product === 'cargo02') {
            $productName = 'minikura CARGO ひとまかせコース';
        } else if ($_product === 'cleaning') {
            $productName = 'クリーニングパック';
        } else if ($_product === 'shoes') {
            $productName = 'シューズパック';
        } else if ($_product === 'sneakers') {
            $productName = 'minikura SNEAKERS';
        }
        return $productName;
    }

    protected function setQueryParameter()
    {
        $query = $this->request->query;
        $results = [];
        // keyword
        if (empty($query['keyword'])) {
            $results['keyword'] = null;
        } else {
            $results['keyword'] = $query['keyword'];
        }

        // order
        if (empty($query['order'])) {
            $results['order'] = null;
        } else {
            $results['order'] = $query['order'];
        }

        // direction
        if (empty($query['direction'])) {
            $results['direction'] = null;
        } else {
            $results['direction'] = $query['direction'];
        }

        // フォームhidden値設定
        if (isset($query['hide_outbound'])) {
            if ($query['hide_outbound'] === '0' ) {
                $results['hide_outbound'] = '0';
            } else {
                $results['hide_outbound'] = '1';
            }
        } else {
            $results['hide_outbound'] = '1';
        }

        return $results;
    }    

    /**
     * 一覧.
     */
    public function index()
    {
        // 出庫済み　hide_outbound=0：表示、hide_outbound=1：非表示、初期表示：非表示
        // 出庫済み withOutboundDone=true:表示, withOutboundDone=false:非表示
        $withOutboundDone = true;
        if (!empty(Hash::get($this->request->query, 'hide_outbound', 1))) {
            $withOutboundDone = false;
        }
        
        //*  mockv22にあわせたmenu改修(アイテムも商品毎にリスト表示) 
        // 商品指定
        $product = $this->request->query('product');

        // oemに紐づく商品じゃない場合、productを空にする
        if (!$this->checkProduct($product)) {
            $product = null;
        }
        $where = [];
        $where['product'] = $product;

        // 並び替えキー指定
        $sortKey = $this->getRequestSortKey();
        $results = $this->InfoItem->getListForServiced($sortKey, $where, $withOutboundDone, true);
        $results = $this->InfoItem->editBySearchTerm($results, $this->request->query);

        $inbounds = $this->InfoItem->getListForServiced($sortKey, $where, false, true);
        $outbounds = $this->InfoItem->getListForServiced($sortKey, $where, true, true);
        $item_all_count = count($outbounds) + count($inbounds);

        // paginate
        $list = $this->paginate(self::MODEL_NAME, $results);
        $this->set('itemList', $list);
        $this->set('item_all_count', $item_all_count);
        $this->set('hideOutbound', $withOutboundDone);

        $query = $this->request->query;
        $query['hide_outbound'] = !empty($withOutboundDone);
        $query['page'] = 1;
        $url = Router::url(['action'=>'index', '?' => http_build_query($query)]);
        $this->set('hideOutboundSwitchUrl', $url);


        $query_params = $this->setQueryParameter();
        $this->set('hide_outbound', $query_params['hide_outbound']);
        $this->set('keyword', $query_params['keyword']);
        $this->set('order', $query_params['order']);
        $this->set('direction', $query_params['direction']);

        // product_name  
        $productName = $this->getProductName($product);
        $this->set('product', $product);
        $this->set('productName', $productName);

        // button active
        $button_status = ['item' => 'on', 'all' => null,'mono' => null, 'hako' => null, 'cargo01' => null, 'cargo02' => null, 'cleaning' => null, 'shoes' => null, ];
        if (empty($this->request->query['product'])) {
            $button_status['all'] = ' on';
        } elseif ($this->request->query['product'] === 'mono') {
            $button_status['mono'] = ' on';
        } elseif ($this->request->query['product'] === 'hako') {
            $button_status['hako'] = ' on';
        } elseif ($this->request->query['product'] === 'cargo01') {
            $button_status['cargo01'] = ' on';
        } elseif ($this->request->query['product'] === 'cargo02') {
            $button_status['cargo02'] = ' on';
        } elseif ($this->request->query['product'] === 'cleaning') {
            $button_status['cleaning'] = ' on';
        } elseif ($this->request->query['product'] === 'shoes') {
            $button_status['shoes'] = ' on';
        }

        $this->set('button_status', $button_status);        
    }

    private function getRequestSortKey()
    {
        $order = $this->request->query('order');
        $direction = $this->request->query('direction');
        if (!empty($order)) {
            return [$order => ($direction === 'asc')];
        }
        return [];
    }

    /**
     *
     */
    public function detail()
    {
        $id = $this->params['id'];
        $item = $this->InfoItem->apiGetResultsFind([], ['item_id' => $id]);
        $this->set('item', $item);

        $box = $item['box'];
        $this->set('box', $box);

        $linkToAuction = null;
        if (in_array($box['product_cd'], [PRODUCT_CD_MONO, PRODUCT_CD_CLEANING_PACK], true)) {
            $linkToAuction = "/mini_auction/lite/item/${item['box_id']}/${item['item_id']}";
        }
        $this->set('linkToAuction', $linkToAuction);

        // 取り出しリスト追加許可
        $outboundList = OutboundList::restore();
        $this->set('denyOutboundList', $outboundList->canAddItem($item));
        
        //* 販売機能
        $customer_sales = $this->Customer->isCustomerSales();
        $this->set('customer_sales', $customer_sales);
        //* 販売情報 
        $sales = null;
        $trade_url = null;
        $widget_url = null;
        /* viewで表示分け用
        *  sales情報があれば$itemで取得済み => Model/Api/InfoItem.php 
        */
        $sales = $this->Sales->checkSales($item);
        if (!empty($sales) && $sales['sales_status'] === SALES_STATUS_ON_SALE) {
            $sales_id = $sales['sales_id'];
            //* trade page url
            $trade_url = Configure::read('site.trade.url').$sales_id;
            //* widget page url
            $widget_url = Configure::read('site.trade.url') . 'widget/' . $sales_id;
        }
        //*  戻る用
        $session_sales = null;
        $session_sales = CakeSession::read(self::MODEL_NAME_SALES);
        $this->set('session_sales', $session_sales);

        $this->set('sales', $sales);
        $this->set('trade_url', $trade_url);
        $this->set('widget_url', $widget_url);

    
    }

    /**
     *
     */
    public function edit()
    {
        $id = $this->params['id'];
        $item = $this->InfoItem->apiGetResultsFind([], ['item_id' => $id]);
        $this->set('item', $item);

        $box = $this->InfoBox->apiGetResultsFind([], ['box_id' => $item['box_id']]);
        $this->set('box', $box);

        if ($this->request->is('get')) {

            $this->request->data[self::MODEL_NAME_ITEM_EDIT] = $item;
            return $this->render('edit');

        } elseif ($this->request->is('post')) {

            $this->Item->set($this->request->data);
            if (!$this->Item->validates()) {
                return $this->render('edit');
            }

            $res = $this->Item->apiPatch($this->Item->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['controller' => 'item', 'action' => 'detail', 'id' => $id]);
            }

            return $this->redirect(['controller' => 'item', 'action' => 'detail', 'id' => $id]);
        }
    }

    /*
    * for sns share
    */
    public function ajax_image_up()
    {
        $this->autoLayout =null;
        $return = true;
        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->request->data, true));

        //* 800 * 800 jpeg from img_server
        if (empty($this->request->data['image_url'])) {
            //* error info
            new AppInternalCritical('Error : no request data  image_url', $code = 500);
            $return = false;
            echo $return;
            exit;
        }
        $image_url = $this->request->data['image_url'];

        CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($image_url, true));

        //* image src
        /*
        * 開発srvから画像検証srvへ接続するには以下必要
        * ゲートウェイがポートフォワーディングしている関係の為
        */
        $patterns = [];
        $patterns[0] = '/dev-image.minikura.com:10080/';
        $patterns[1] = '/dev-image.minikura.com:10443/';
        $patterns[2] = '/stag-image.minikura.com:10080/';
        $patterns[3] = '/stag-image.minikura.com:10443/';
        $replacements = [];
        $replacements[0] = 'dev-image.minikura.lan:80';
        $replacements[1] = 'dev-image.minikura.lan:443';
        $replacements[2] = 'stag-image.minikura.lan:80';
        $replacements[3] = 'stag-image.minikura.lan:443';
        $replace_image_url = preg_replace($patterns, $replacements, $image_url);

        $get_image = imagecreatefromjpeg($replace_image_url);
        if ($get_image === false) {
            //*  error
            new AppInternalCritical('Error : not create image_resouce_id from  image_url', $code = 500);
            $return = false;
            echo $return;
            exit;
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
            //* create
            $image_name = 'test_' . date('md_Hi') . '.png';
            imagepng($create_image, APP  . WEBROOT_DIR . DS . 'images' . DS . $image_name);
            imagepng($create_image, APP  . 'tmp' . DS  . $image_name);

            /*
            * file upload to drvsrv
            */
           $image_url_data = explode('/', $image_url);
           $replace_image_file = preg_replace('/\.jpg/', '_fb.png', $image_url_data[6]);
           $replace_image_file = explode('?', $replace_image_file);


           /*
           CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($image_url_data, true));
           CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($replace_image_file, true));
           CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export(Configure::read('app.strage.file_dir') . $image_url_data[4] . DS . $image_url_data[5] . DS . $replace_image_file[0], true));
            */
            $fileObject  = new AppFile();
            $file_upload_flag = $fileObject->upload(
                $host = Configure::read('app.strage.host'), 
                $user = Configure::read('app.strage.ssh,username'), 
                $publick = Configure::read('app.strage.ssh.rsa.id_rsa_public'), 
                $id_rsa = Configure::read('app.strage.ssh.rsa.id_rsa'), 
                $image_src = APP  . 'tmp' . DS  . $image_name,
                $to_srv = Configure::read('app.strage.file_dir') . $image_url_data[4] . DS . $image_url_data[5] . DS . $replace_image_file[0] 
            );
           CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($file_upload_flag, true));

            /*
            $config['app.strage.host'] = '192.168.16.124';
            $config['app.strage.file_dir'] = '/data/s/dev-image.minikura.com/app/webroot/i/';
            $config['app.strage.url'] = 'http://dev-image.minikura.com:10080/i/';
            $config['app.strage.ssh.username'] = 'minikura.com';
            $config['app.strage.ssh.rsa.id_rsa_public'] = '/home/minikura.com/.ssh/id_rsa.pub';
            $config['app.strage.ssh.rsa.id_rsa'] = '/home/minikura.com/.ssh/id_rsa';
            */

            imagedestroy($create_image);

            // *作成ファイルも消す todo test
            unlink(APP  . WEBROOT_DIR . DS . 'images' . DS . 'test_0912_1921.png');
            unlink(APP  . 'tmp' . DS . 'test_0912_1921.png');
        
        }

        echo $return;
        exit; 
    }
}
