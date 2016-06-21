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

        // 出庫済み　hide_outboud=0：表示、hide_outboud=1：非表示、初期表示：非表示
        $withOutboudDone = !empty(Hash::get($this->request->query, 'hide_outboud', 1));
        $product = $this->request->query('product');
        $page = $this->request->query('page');
        $data = [];
        foreach ($selectSortKeys as $key => $value) {
            $desc = Router::url(['action'=>'index', '?' => ['product' => $product, 'order' => $key, 'direction' => 'desc', 'hide_outboud' => $withOutboudDone, 'page' => $page]]);
            $data[$desc] = $value . __('select_sort_desc');
            $asc = Router::url(['action'=>'index', '?' => ['product' => $product, 'order' => $key, 'direction' => 'asc', 'hide_outboud' => $withOutboudDone, 'page' => $page]]);
            $data[$asc] = $value . __('select_sort_asc');
        }

        return $data;
    }

    /*
    private function checkProduct($product = null)
    {
        if(empty($product)) return true;

        // sneakers のユーザに minikura の商品を見せない（逆も然り）
        $oem_cd = $this->Customer->getInfo()['oem_cd'];

        // 各OEMのproductリスト生成
        foreach (IN_USE_SERVICE['minikura'] as $service_data) {
            $minikura_services[] = $service_data['product'];
        }
        foreach (IN_USE_SERVICE['sneakers'] as $service_data) {
            $sneakers_services[] = $service_data['product'];
        }

        // oem_cdに属するかどうかをチェック
        if ($oem_cd === OEM_CD_LIST['sneakers']) {
            if (!in_array($product, $sneakers_services)) {
                return false;
            }
        } else {
            if (!in_array($product, $minikura_services)) {
                return false;
            }
        }
        return true;
    }
	*/

    /**
     * 一覧.
     */
    public function index()
    {
        // 出庫済み　hide_outboud=0：表示、hide_outboud=1：非表示、初期表示：非表示
        $withOutboudDone = true;
        if (!empty(Hash::get($this->request->query, 'hide_outboud', 1))) {
            $withOutboudDone = false;
        }
        // 商品指定
        $product = $this->request->query('product');

        // oemに紐づく商品じゃない場合、productを空にする
        if (!$this->checkProduct($product)) {
            $product = null;
        }

        // 並び替えキー指定
        $sortKey = $this->getRequestSortKey();
		/*
		debug('master_key');
		debug($product);
		debug($sortKey);
		*/
        $results = $this->InfoBox->getListForServiced($product, $sortKey, $withOutboudDone, true);
        // paginate
        $list = $this->paginate(self::MODEL_NAME, $results);
        $this->set('boxList', $list);
        $this->set('product', $product);
        $this->set('hideOutboud', $withOutboudDone);

        $query = $this->request->query;
        $query['hide_outboud'] = !empty($withOutboudDone);
        $query['page'] = 1;
        $url = Router::url(['action'=>'index', '?' => http_build_query($query)]);
        $this->set('hideOutboudSwitchUrl', $url);
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
