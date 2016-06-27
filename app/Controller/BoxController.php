<?php

App::uses('MinikuraController', 'Controller');
App::uses('OutboundList', 'Model');

class BoxController extends MinikuraController
{
    const MODEL_NAME = 'InfoBox';
    const MODEL_NAME_BOX_EDIT = 'Box';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
        $this->loadModel('InfoItem');
        $this->loadModel(self::MODEL_NAME_BOX_EDIT);

        $this->set('sortSelectList', $this->makeSelectSortUrl());
        $this->set('select_sort_value', Router::reverse($this->request));
    }

    private function makeSelectSortUrl()
    {
        // 並び替え選択
        $selectSortKeys = [
            'box_id' => __('box_id'),
            'box_name' => __('box_name'),
            'product_name' => __('product_name'),
            'box_status' => __('box_status')
        ];

        // 出庫済み　hide_outbound=0：表示、hide_outbound=1：非表示、初期表示：非表示
        $withOutboundDone = !empty(Hash::get($this->request->query, 'hide_outbound', 1));
        $product = $this->request->query('product');
        $page = $this->request->query('page');
        $data = [];
        foreach ($selectSortKeys as $key => $value) {
            $desc = Router::url(['action'=>'index', '?' => ['product' => $product, 'order' => $key, 'direction' => 'desc', 'hide_outbound' => $withOutboundDone, 'page' => $page]]);
            $data[$desc] = $value . __('select_sort_desc');
            $asc = Router::url(['action'=>'index', '?' => ['product' => $product, 'order' => $key, 'direction' => 'asc', 'hide_outbound' => $withOutboundDone, 'page' => $page]]);
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
        $withOutboundDone = true;
        if (!empty(Hash::get($this->request->query, 'hide_outbound', 1))) {
            $withOutboundDone = false;
        }

        // 商品指定
        $product = $this->request->query('product');

        // oemに紐づく商品じゃない場合、productを空にする
        if (!$this->checkProduct($product)) {
            $product = null;
        }

        // 並び替えキー指定
        $sortKey = $this->getRequestSortKey();

        $results = $this->InfoBox->getListForServiced($product, $sortKey, $withOutboundDone, true);
        $results = $this->InfoBox->editBySearchTerm($results, $this->request->query);

        // paginate
        $list = $this->paginate(self::MODEL_NAME, $results);
        $this->set('boxList', $list);
        $this->set('product', $product);
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
        $this->set('productName', $productName);

        // button active
        $button_status = ['product' => 'on', 'all' => null,'mono' => null, 'hako' => null, 'cargo01' => null, 'cargo02' => null, 'cleaning' => null, 'shoes' => null, ];
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
        //default
        return ['inbound_date' => false, 'box_id' => true];
    }

    /**
     *
     */
    public function detail()
    {
        // 出庫済み　hide_outbound=0:表示, hide_outbound=1:非表示, 初期表示：非表示
        // 出庫済み withOutboundDone=true:表示, withOutboundDone=false:非表示
        $withOutboundDone = true;

        if (!empty(Hash::get($this->request->query, 'hide_outbound', 1))) {
            // hide_outbound が 1 の場合、出庫済みは非表示
            $withOutboundDone = false;
        }

        $id = $this->params['id'];
        $box = $this->InfoBox->apiGetResultsFind([], ['box_id' => $id]);
        $this->set('box', $box);

        // withOutboundDone が true の場合、出庫済みも表示する
        $itemList = $this->InfoItem->getListForServiced(['inbound_date' => false, 'item_id' => true], ['box_id' => $id], $withOutboundDone, true);
        $this->set('itemList', $itemList);

        // 取り出しリスト追加許可
        $outboundList = OutboundList::restore();
        $this->set('denyOutboundList', $outboundList->canAddBox($box));

        // 出庫済みアイテム制御
        $this->set('hideOutbound', $withOutboundDone);

        $query = $this->request->query;
        $query['hide_outbound'] = !empty($withOutboundDone);
        $url = Router::url([
            'action'=>'detail',
            $id,
            '?' => http_build_query($query),
        ]);

        $this->set('hideOutboundSwitchUrl', $url);
    }

    /**
     *
     */
    public function edit()
    {
        $id = $this->params['id'];
        $box = $this->InfoBox->apiGetResultsFind([], ['box_id' => $id]);
        $this->set('box', $box);

        if ($this->request->is('get')) {

            $this->request->data[self::MODEL_NAME_BOX_EDIT] = $box;
            return $this->render('edit');

        } elseif ($this->request->is('post')) {

            $this->Box->set($this->request->data);
            if (!$this->Box->validates()) {
                return $this->render('edit');
            }

            // 「半角コロンまたはカンマ」をそれぞれ全角に自動変換
            $this->Box->data['Box']['box_name'] = $this->InfoBox->replaceBoxtitleChar($this->Box->data['Box']['box_name']);

            $res = $this->Box->apiPatch($this->Box->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->render('edit');
            }

            return $this->redirect(['controller' => 'box', 'action' => 'detail', 'id' => $id]);
        }
    }
}
