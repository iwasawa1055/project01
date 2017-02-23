<?php

App::uses('MinikuraController', 'Controller');

/**
* クリーニングメニュー　各ページ  
*
* 
*/
class CleaningController extends MinikuraController
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

        return $results;
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
     * input
     */
    public function input()
    {
        // 引数でidがリターンされた場合はすでにチェックを入れる
        if ( isset($this->request->query['id']) ) {
            $selected_id = $this->request->query['id'];
        } else {
            $selected_id = null;
        }
        $this->set('selected_id', $selected_id);
        
        // 商品指定
        $where = [];
        $where['product'] = null;
        
        // 並び替えキー指定
        $sortKey = $this->getRequestSortKey();

        // 保管品リストを取得する
        $results = $this->InfoItem->getListForServiced($sortKey, $where, false, true);
        $results = $this->InfoItem->editBySearchTerm($results, $this->request->query);
        
        $item_all_count = count($results);

        // paginate
        $list = $this->paginate(self::MODEL_NAME, $results);
        
        $this->set('itemList', $list);
        $this->set('item_all_count', $item_all_count);

        $query = $this->request->query;
        $query['page'] = 1;
        $url = Router::url(['action'=>'input', '?' => http_build_query($query)]);

        $query_params = $this->setQueryParameter();

        $this->set('keyword', $query_params['keyword']);
        $this->set('order', $query_params['order']);
        $this->set('direction', $query_params['direction']);

        
        $this->set('price',Configure::read('app.kit.cleaning.item_group_cd'));
        
        // Session Referer
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
    }

    /**
     * input
     */
    public function input_confirm()
    {
        $flg_error = false;

        // 選択されたID一覧をセッションに保管する
        if ( !isset($this->request->data['selected']) ) {
            $flg_error = true;
        }
        $selected_ids = $this->request->data['selected'];
        if ( count($selected_ids) < 1 ) {
            $flg_error = true;
        }
        
        if ( $flg_error ) {
          return $this->redirect(['controller' => 'cleaning', 'action' => 'input']);
        }
        
        $session_data = array();

        foreach ( $selected_ids as $_tmp ) {
            list($data['item_id'], $data['price'], $data['image_url']) = explode(",",$_tmp,3);
            array_push($session_data,$data);
        }
        
        CakeSession::write('app.data.session_cleaning',$session_data);
        
        // Session Referer
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
        
        return $this->redirect(['controller' => 'cleaning', 'action' => 'confirm']);
    }

    /**
     *  confirm
     */
    public function confirm()
    {
        $selected_data = CakeSession::read('app.data.session_cleaning');
        $this->set('selected_count', count($selected_data));
        
        $totalprice = 0;
        foreach ( $selected_data as $tmp ) {
            $totalprice += $tmp['price'];
        }
        $this->set('selected_total', $totalprice);
        $this->set('itemList', $selected_data);

        // Session Referer
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
    }

    /**
     *  complete 
     */
    public function complete()
    {
        $selected_data = CakeSession::read('app.data.session_cleaning');
        $this->set('itemList', $selected_data);
        
        // API Request
        
        
        
        // 登録に成功した場合
        
        
        
        // 登録に失敗した場合
        
        
        
        
        
        
        // 処理が完了したら、セッションとクッキーを削除する
        #CakeSession::delete("app.data.session_cleaning");
        #setcookie("mn_cleaning_list", "", time()-3600);
        
        
        
    }
}
