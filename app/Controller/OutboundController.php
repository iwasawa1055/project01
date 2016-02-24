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

    /**
     * data [
     *   'box_id' => ['001' => 1, '002' => 1, '003' => 1]
     * ]
     * @param $beforeList
     * @param $dataKey
     */
    private function mergeDataKey($dataKey, $beforeList = [])
    {
        $newIdList = Hash::get($this->request->data, $dataKey);

        $beforeKeyList = array_keys($beforeList);
        foreach ($newIdList as $value => $isAdd) {
            if ($isAdd === '1' && !in_array($value, $beforeKeyList, true)) {
                $beforeList[$value] = [];
            } elseif ($isAdd === '0' && in_array($value, $beforeKeyList, true)) {
                unset($beforeList[$value]);
            }
        }
        return array_keys($beforeList);
    }

    public function mono()
    {
        // 保持しているデータ
        $outMonoList = $this->outboundList->getMonoList();

        // 増減処理
        if ($this->request->is('post')) {
            $idList = $this->mergeDataKey('box_id', $outMonoList);
            $errorList = $this->outboundList->setMono($idList);
            if (empty($errorList)) {
                $this->redirect(['action' => 'item']);
            } else {
                // has error
                $outMonoList = $this->outboundList->getMonoList();
                $this->set('errorList', $errorList);
            }
        }

        // 表示
        $list = $this->InfoBox->getListForServiced('mono');
        $keyList = array_keys($outMonoList);
        // 選択フラグ
        foreach ($list as &$box) {
            $box['outbound_list'] = in_array($box['box_id'], $keyList, true);
        }
        $this->set('boxList', $list);
    }

    public function item()
    {
        $outItemList = $this->outboundList->getItemList();


        if ($this->request->is('post')) {

            $idList = $this->mergeDataKey('item_id', $outItemList);

            $errorList = $this->outboundList->setItem($idList);
            if (empty($errorList)) {
                $this->redirect(['action' => 'index']);
            } else {
                // has error
                $outItemList = $this->outboundList->getItemList();
                $this->set('errorList', $errorList);
            }
        }

        // 対象MONOボックス
        $outMonoList = $this->outboundList->getMonoList();
        $outMonoKeyList = array_keys($outMonoList);

        // item
        $list = $this->InfoItem->getListForServiced(null, ['box_id' => $outMonoKeyList]);
        $keyList = array_keys($outItemList);
        foreach ($list as &$item) {
            $item['outbound_list'] = in_array($item['item_id'], $keyList, true);
        }
        $this->set('itemList', $list);
    }

    /**
     * ボックス一覧
     */
    public function box()
    {
        $outBoxList = $this->outboundList->getBoxList();

        if ($this->request->is('post')) {

            // ids
            $idList = $this->mergeDataKey('box_id', $outBoxList);

            // check and save
            $errorList = $this->outboundList->setBox($idList);
            if (empty($errorList)) {
                $this->redirect(['action' => 'index']);
            } else {
                // has error
                $outBoxList = $this->outboundList->getBoxList();
                $this->set('errorList', $errorList);
            }
        }

        // Box
        $list = $this->InfoBox->getListForServiced();
        $keyList = array_keys($outBoxList);
        foreach ($list as &$box) {
            $box['outbound_list'] = in_array($box['box_id'], $keyList, true);
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
            $this->request->data = CakeSession::read(self::MODEL_NAME . 'FORM');
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
        $r = $this->DatetimeDeliveryOutbound->apiGet(['postal' => $postal]);
        if (!$r->isSuccess()) {
            // TODO: 例外処理
            return;
        }
        $this->set('dateItemList', $r->results);

        CakeSession::delete(self::MODEL_NAME . 'FORM');
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
            if (is_array($itemIdList)) {
                $ids = array_keys($itemIdList);
                $itemList = $this->InfoItem->apiGetResultsWhere([], ['item_id' => $ids]);
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
                CakeSession::write(self::MODEL_NAME . 'FORM', $this->request->data);
                CakeSession::write(self::MODEL_NAME, $this->Outbound->data);
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
        $data = CakeSession::read(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME . 'FORM');

        if (empty($data)) {
            // TODO:
            $this->Flash->set('try again');
            return $this->redirect(['action' => 'add']);
        }

        $this->Outbound->set($data);
        if ($this->Outbound->validates()) {
            // api
            $res = $this->Outbound->apiPost($this->Outbound->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'index']);
            }
            // 取り出しリストクリア
            OutboundList::delete();
            InfoBox::deleteAllCache();
            InfoItem::deleteAllCache();
            Announcement::deleteAllCache();
        } else {
            // TODO:
            $this->Flash->set('try again');
            return $this->redirect(['action' => 'add']);
        }
    }
}
