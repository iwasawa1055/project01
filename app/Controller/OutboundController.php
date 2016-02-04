<?php

App::uses('AppController', 'Controller');
App::uses('OutboundList', 'Model');
App::uses('Outbound', 'Model');

class OutboundController extends AppController
{
    const MODEL_NAME = 'Outbound';

    private $outboundList = null;

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel('InfoBox');
        $this->loadModel('InfoItem');
        $this->loadModel('CustomerAddress');
        $this->loadModel('OutboundDeliveryDatetime');
        $this->loadModel('Outbound');

        // 配送先
        // TODO 契約情報も取得する
        $addressList = $this->CustomerAddress->apiGetResults();
        // $this->address = $addressList;
        $this->set('addressList', $addressList);
        // TODO: 都度取得？
        $dateTimeList = $this->OutboundDeliveryDatetime->apiGetResults();
        foreach ($dateTimeList as &$dt) {
            $dt['text'] = $dt['datetime_cd'];
        }
        $this->set('dateItemList', $dateTimeList);

        // 取り出しリスト
        $this->outboundList = OutboundList::restore();
    }


    public function item()
    {
        if ($this->request->is('post')) {
            // item
            $itemIdList = Hash::get($this->request->data, 'item_list');
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
        // $outItemList = $this->outboundList->getItemList();
        if ($this->request->is('post')) {
            // box
            $boxIdList = Hash::get($this->request->data, 'box_list');
            $where = $this->getCheckedItemToArray($boxIdList, 'checkbox', 'box_id');
            $boxList = $this->InfoBox->apiGetResultsWhere([], $where);
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
        $this->set('boxList', $this->outboundList->getBoxList());
        $this->set('itemList', $this->outboundList->getItemList());
    }

    /**
     * [['checkbox' => 1, 'box_id' => 'MN-0001'],
     *  ['checkbox' => 0, 'box_id' => 'MN-0004']]
     *
     *
     * [getCheckedItemToArray description]
     * @param  [type] $array      [description]
     * @param  [type] $checkedKey [description]
     * @param  [type] $pickKey    [description]
     * @return [type]             [description]
     */
    private function getCheckedItemToArray($array, $checkedKey, $pickKey)
    {
        $result = [];
        if (!is_array($array) || count($array) === 0) {
            return $result;
        }
        foreach ($array as $a) {
            if (!empty($a[$checkedKey])) {
                $result[$pickKey][] = $a[$pickKey];
            }
        }
        return $result;
    }

    /**
     *
     */
    public function confirm()
    {
        // $out = OutboundList::restore();

        if ($this->request->is('post')) {

            // $boxList = Hash::get($this->request->data, 'box_list');
            // $itemList = Hash::get($this->request->data, 'item_list');
            // $this->Outbound->set(['Outbound' => $this->request->data['Outbound']]);

            // box
            $boxIdList = Hash::get($this->request->data, 'box_list');
            $where = $this->getCheckedItemToArray($boxIdList, 'checkbox', 'box_id');
            $boxList = $this->InfoBox->apiGetResultsWhere([], $where);
            $this->set('boxList', $boxList);

            // item
            $itemIdList = Hash::get($this->request->data, 'item_list');
            $where = $this->getCheckedItemToArray($itemIdList, 'checkbox', 'item_id');
            $itemList = $this->InfoItem->apiGetResultsWhere([], $where);
            $this->set('itemList', $itemList);

            // val
            $data = $this->request->data;
            unset($data['box_list']);
            unset($data['item_list']);


            // params
            // $product = '';
            // foreach ($boxList as $box) {
            //     $product .= "${box['product_cd']}:${box['box_id']}:,";
            // }
            // $product = rtrim($product, ',');
            $data['Outbound']['product'] = $this->Outbound->buildParamProduct($boxList, $itemList);

            // お届け先
            $addressId = $data['Outbound']['address_id'];
            $address = $this->CustomerAddress->apiGetResultsFind([], ['address_id' => $addressId]);
            unset($data['Outbound']['address_id']);

            $data['Outbound']['lastname'] = $address['lastname'];
            $data['Outbound']['lastname_kana'] = $address['lastname_kana'];
            $data['Outbound']['firstname'] = $address['firstname'];
            $data['Outbound']['firstname_kana'] = $address['firstname_kana'];
            $data['Outbound']['tel1'] = $address['tel1'];
            $data['Outbound']['postal'] = $address['postal'];
            $data['Outbound']['pref'] = $address['pref'];
            $data['Outbound']['address1'] = $address['address1'];
            $data['Outbound']['address2'] = $address['address2'];
            $data['Outbound']['address3'] = $address['address3'];

            // お届け
            // $datetime = $data['Outbound']['datetime_cd'];
            // $data

            $this->Outbound->set($data);
            if ($this->Outbound->validates()) {
                $this->set('address_text', "〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}");
                // お届け希望日時
                $datetime = $data['Outbound']['datetime_cd'];
                $this->set('datetime', $datetime);
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
