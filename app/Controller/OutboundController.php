<?php

App::uses('AppController', 'Controller');
App::uses('OutboundList', 'Model');
App::uses('Outbound', 'Model');

class OutboundController extends AppController
{
    public $components = array('Address');

    const MODEL_NAME = 'Outbound';

    private $outboundList = [];

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel('InfoBox');
        $this->loadModel('InfoItem');
        $this->loadModel('DatetimeDeliveryOutbound');
        $this->loadModel('Outbound');

        // 配送先
        $this->set('addressList', $this->Address->get());

        // 取り出しリスト
        $this->outboundList = OutboundList::restore();
    }

    /**
     *
     */
    public function getAddressDatetime()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }
        $this->autoRender = false;
        $addressId = $this->request->data['address_id'];
        $address = $this->Address->find($addressId);
        $datetime = $this->getDatetime($address['postal']);
        $status = !empty($datetime);
        return json_encode(compact('status', 'result'));
    }

    private function getDatetime($postal)
    {
        $result = $this->DatetimeDeliveryOutbound->apiGet([
            'postal' => $postal,
        ]);
        return $result->results;
    }

    private function getDatetimeOne($postal, $datetimeCd)
    {
        $list = $this->getDatetime($postal);
        foreach ($list as $datetime) {
            if ($datetime['datetime_cd'] === $datetimeCd) {
                return $datetime;
            }
        }
    }

    public function mono()
    {
        if ($this->request->is('post')) {
            // item
            $itemIdList = Hash::get($this->request->data, 'item_list');
            $where = $this->getCheckedItemToArray($itemIdList, 'checkbox', 'item_id');
            $itemList = $this->InfoItem->apiGetResultsWhere([], $where);
            $this->outboundList->setItemAndSave($itemList);
            $this->redirect(['action' => 'index']);
        }

        // mono
        $outItemList = $this->outboundList->getItemList();
        $outItemKeyList = array_keys($outItemList);
        $list = $this->InfoItem->getListForServiced();
        foreach ($list as &$item) {
            $item['outbound_list'] = in_array($item['item_id'], $outItemKeyList, true);
        }
        $this->set('itemList', $list);
    }

    public function item()
    {
        if ($this->request->is('post')) {
            // item
            $itemIdList = Hash::get($this->request->data, 'item_id');
            $where = $this->getCheckedItemToArray($itemIdList, 'checkbox', 'item_id');
            $itemList = $this->InfoItem->apiGetResultsWhere([], $where);
            $this->outboundList->setItemAndSave($itemList);
            $this->redirect(['action'=>'index']);
        }

        // item
        $outItemList = $this->outboundList->getItemList();
        $outItemKeyList = array_keys($outItemList);
        $list = $this->InfoItem->getListForServiced();
        foreach ($list as &$item) {
            $item['outbound_list'] = in_array($item['item_id'], $outItemKeyList, true);
        }
        $this->set('itemList', $list);
    }

    /**
     * ボックス一覧
     */
    public function box()
    {
        if ($this->request->is('post')) {
            // box
            $where = Hash::get($this->request->data, 'box_id');
            $boxList = [];
            if (is_array($where)) {
                $ids = array_keys($where);
                $boxList = $this->InfoBox->apiGetResultsWhere([], ['box_id' => $ids]);
            }
            $this->outboundList->setBoxAndSave($boxList);
            $this->redirect(['action'=>'index']);
        }

        // Box
        $outBoxList = $this->outboundList->getBoxList();
        $outBoxKeyList = array_keys($outBoxList);
        $list = $this->InfoBox->getListForServiced();
        foreach ($list as &$box) {
            $box['outbound_list'] = in_array($box['box_id'], $outBoxKeyList, true);
        }
        $this->set('boxList', $list);
    }

    /**
     * 取り出しリスト
     */
    public function index()
    {
        $boxList = $this->outboundList->getBoxList();
        $this->set('boxList', $boxList);
        $itemList = $this->outboundList->getItemList();
        $this->set('itemList', $itemList);

        $postal = $this->Address->get()[0]['postal'];

        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = CakeSession::read($this::MODEL_NAME . 'FORM');
            $addressId = $this->request->data['Outbound']['address_id'];
            $address = $this->Address->find($addressId);
            $postal = $address['postal'];
        } else {
            // 初期は選択済み
            foreach ($boxList as $box) {
                $this->request->data['box_id'][$box['box_id']] = 1;
            }
            foreach ($itemList as $item) {
                $this->request->data['item_id'][$item['item_id']] = 1;
            }
        }
        // お届け希望日と時間
        $dateTimeList = $this->DatetimeDeliveryOutbound->apiGetResults(['postal' => $postal]);
        $this->set('dateItemList', $dateTimeList);

        CakeSession::delete($this::MODEL_NAME . 'FORM');
    }

    /**
     *
     */
    public function confirm()
    {
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $boxList = [];
            $itemList = [];

            // box
            $boxIdList = Hash::get($data, 'box_id');
            if (is_array($boxIdList)) {
                $ids = array_keys($boxIdList);
                $boxList = $this->InfoBox->apiGetResultsWhere([], ['box_id' => $ids]);
            }
            $this->set('boxList', $boxList);
            // item
            $itemIdList = Hash::get($data, 'item_id');
            if (is_array($boxIdList)) {
                $ids = array_keys($boxIdList);
                $itemList = $this->InfoBox->apiGetResultsWhere([], ['item_id' => $ids]);
            }
            $this->set('itemList', $itemList);
            // unset
            unset($data['box_id']);
            unset($data['item_id']);

            // product
            $data['Outbound']['product'] = $this->Outbound->buildParamProduct($boxList, $itemList);

            // お届け先
            $addressId = $data['Outbound']['address_id'];

            $data['Outbound'] = $this->Address->merge($addressId, $data['Outbound']);

            $this->Outbound->set($data);
            if ($this->Outbound->validates()) {
                // 表示ラベル
                $address = $this->Address->find($addressId);
                $this->set('address_text', "〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}");
                $datetime = $this->getDatetimeOne($address['postal'], $data['Outbound']['datetime_cd']);
                $this->set('datetime_text', $datetime['text']);
                CakeSession::write($this::MODEL_NAME . 'FORM', $this->request->data);
                CakeSession::write($this::MODEL_NAME, $this->Outbound->data);
            } else {
                return $this->render('index');
            }
        }
    }

    /**
     *
     */
    public function complete()
    {
        $data = CakeSession::read($this::MODEL_NAME);
        CakeSession::delete($this::MODEL_NAME);
        CakeSession::delete($this::MODEL_NAME . 'FORM');

        // unset($data['Outbound']['address_id']);
        if (empty($data)) {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'add']);
        }

        $this->Outbound->set($data);
        if ($this->Outbound->validates()) {
            // api
            $res = $this->Outbound->apiPost($this->Outbound->toArray());
            if (!empty($res->error_message)) {
                // TODO: 例外処理
                $this->Session->setFlash($res->error_message);
                return $this->redirect(['action' => 'index']);
            }
            // 取り出しリストクリア
            OutboundList::delete();
            InfoBox::deleteAllCache();
            InfoItem::deleteAllCache();
            Announcement::deleteAllCache();
        } else {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'add']);
        }
    }
}
