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

        $withOutboudDone = !empty(Hash::get($this->request->query, 'hide_outboud'));
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

    /**
     * 一覧.
     */
    public function index()
    {
        $withOutboudDone = true;
        if (!empty(Hash::get($this->request->query, 'hide_outboud'))) {
            $withOutboudDone = false;
        }
        // 商品指定
        $product = $this->request->query('product');
        // 並び替えキー指定
        $sortKey = $this->getRequestSortKey();
        $results = $this->InfoBox->getListForServiced($product, $sortKey, $withOutboudDone);
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
        return [];
    }

    /**
     *
     */
    public function detail()
    {
        $id = $this->params['id'];
        $box = $this->InfoBox->apiGetResultsFind([], ['box_id' => $id]);
        $this->set('box', $box);

        $itemList = $this->InfoItem->apiGetResultsWhere([], ['box_id' => $id]);
        $this->set('itemList', $itemList);

        // 取り出しリスト追加許可
        $outboundList = OutboundList::restore();
        $this->set('denyOutboundList', in_array($box['box_id'], $outboundList->getBoxIdFromItemList(), true));
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

            $res = $this->Box->apiPatch($this->Box->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->render('edit');
            }

            return $this->redirect(['controller' => 'box', 'action' => 'detail', 'id' => $id]);
        }
    }
}
