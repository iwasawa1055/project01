<?php

App::uses('MinikuraController', 'Controller');
App::uses('OutboundList', 'Model');
App::uses('Outbound', 'Model');
App::uses('InfoBox', 'Model');
App::uses('InfoItem', 'Model');

class OutboundController extends MinikuraController
{
    const MODEL_NAME = 'Outbound';
    const MODEL_NAME_POINT_BALANCE = 'PointBalance';
    const MODEL_NAME_POINT_USE = 'PointUse';

    private $outboundList = [];

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->loadModel('InfoBox');
        $this->loadModel('InfoItem');
        $this->loadModel('DatetimeDeliveryOutbound');
        $this->loadModel('Outbound');
        $this->loadModel(self::MODEL_NAME_POINT_BALANCE);
        $this->loadModel(self::MODEL_NAME_POINT_USE);

        // 配送先
        $this->set('addressList', $this->Address->get());

        // 取り出しリスト
        $this->outboundList = OutboundList::restore();
    }

    /**
     * アクセス拒否
     */
    protected function isAccessDeny()
    {
        return !$this->Customer->canOutbound();
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
        $result = $this->getDatetime($address['postal']);
        $status = !empty($result);
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
        if (empty($newIdList)) {
            return [];
        }
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

        // 更新処理し成功した場合アイテム選択へ遷移
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

        // 対象ボックス一覧
        $where = [
            'box_status' => [BOXITEM_STATUS_INBOUND_DONE],
            'product_cd' => [PRODUCT_CD_MONO, PRODUCT_CD_CLEANING_PACK, PRODUCT_CD_SHOES_PACK],
        ];
        $list = $this->InfoBox->apiGetResultsWhere([], $where);
        // 取り出しリスト追加済みフラグ、追加不可フラグ
        foreach ($list as &$box) {
            $box['outbound_list_cehcked'] = in_array($box['box_id'], $this->outboundList->getBoxIdFromMonoList(), true);
            $box['outbound_list_deny'] = $this->outboundList->canAddMono($box);
        }
        HashSorter::sort($list, InfoBox::DEFAULTS_SORT_KEY);
        $this->set('boxList', $list);
    }

    public function item()
    {
        $outItemList = $this->outboundList->getItemList();

        // 更新処理し成功した場合取り出しリストへ遷移
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

        // 対象アイテム一覧
        $where = [
            'item_status' => [BOXITEM_STATUS_INBOUND_DONE * 1],
            'box_id' => $outMonoKeyList,
            'box.product_cd' => [
                PRODUCT_CD_MONO,
                PRODUCT_CD_HAKO,
                PRODUCT_CD_CLEANING_PACK,
                PRODUCT_CD_SHOES_PACK,
            ]
        ];
        $list = $this->InfoItem->apiGetResultsWhere([], $where);
        // 取り出しリスト追加済みフラグ、追加不可フラグ
        foreach ($list as &$item) {
            $item['outbound_list_cehcked'] = in_array($item['item_id'], $this->outboundList->getItemIdFromItemList(), true);
            $item['outbound_list_deny'] = $this->outboundList->canAddItem($item);
        }
        HashSorter::sort($list, InfoItem::DEFAULTS_SORT_KEY);
        $this->set('itemList', $list);
    }

    /**
     * ボックス一覧
     */
    public function box()
    {
        $outBoxList = $this->outboundList->getBoxList();

        // 更新処理し成功した場合取り出しリストへ遷移
        if ($this->request->is('post')) {
            // 更新キーIDと増減情報
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

        // 対象ボックス一覧
        $where = [
            'box_status' => [BOXITEM_STATUS_INBOUND_DONE],
            'product_cd' => [
                PRODUCT_CD_MONO,
                PRODUCT_CD_HAKO,
                PRODUCT_CD_CLEANING_PACK,
                PRODUCT_CD_SHOES_PACK,
            ]
        ];
        $list = $this->InfoBox->apiGetResultsWhere([], $where);
        // 取り出しリスト追加済みフラグ、追加不可フラグ
        foreach ($list as &$box) {
            $box['outbound_list_cehcked'] = in_array($box['box_id'], $this->outboundList->getBoxIdFromBoxList(), true);
            $box['outbound_list_deny'] = $this->outboundList->canAddBox($box, false);
        }
        HashSorter::sort($list, InfoBox::DEFAULTS_SORT_KEY);
        $this->set('boxList', $list);
    }

    /**
     * 取り出しリスト
     */
    public function index()
    {
        $boxList = $this->outboundList->getBoxList();
        HashSorter::sort($boxList, InfoBox::DEFAULTS_SORT_KEY);
        $this->set('boxList', $boxList);

        $itemList = $this->outboundList->getItemList();
        HashSorter::sort($itemList, InfoItem::DEFAULTS_SORT_KEY);
        $this->set('itemList', $itemList);

        // ポイント取得
        $pointBalance = [];
        $this->loadModel(self::MODEL_NAME_POINT_BALANCE);
        $res = $this->PointBalance->apiGet();
        if (!empty($res->error_message)) {
            $this->Flash->set($res->error_message);
        } else {
            $pointBalance = $res->results[0];
        }
        $this->set('pointBalance', $pointBalance);

        $dateItemList = [];

        $isBack = Hash::get($this->request->query, 'back');
        $data = CakeSession::read(self::MODEL_NAME . 'FORM');
        $pointUse = CakeSession::read(self::MODEL_NAME_POINT_USE);
        if ($isBack && !empty($data)) {
            // 前回追加選択は最後のお届け先を選択
            if (Hash::get($data[self::MODEL_NAME], 'address_id') === AddressComponent::CREATE_NEW_ADDRESS_ID) {
                $data[self::MODEL_NAME]['address_id'] = Hash::get($this->Address->last(), 'address_id', '');
                $data[self::MODEL_NAME]['datetime_cd'] = '';
            }
            $this->request->data = $data;
            $addressId = $this->request->data['Outbound']['address_id'];
            $address = $this->Address->find($addressId);
            $postal = $address['postal'];
            // お届け希望日と時間
            $dateItemList = $this->getDatetime($postal);
            // 利用ポイント
            $this->request->data[self::MODEL_NAME_POINT_USE] = $pointUse[self::MODEL_NAME_POINT_USE];
        }
        $this->set('dateItemList', $dateItemList);
        CakeSession::delete(self::MODEL_NAME . 'FORM');
        CakeSession::delete(self::MODEL_NAME_POINT_USE);
    }

    /**
     *
     */
    public function confirm()
    {
        $boxList = $this->outboundList->getBoxList();
        HashSorter::sort($boxList, InfoBox::DEFAULTS_SORT_KEY);
        $this->set('boxList', $boxList);

        $itemList = $this->outboundList->getItemList();
        HashSorter::sort($itemList, InfoItem::DEFAULTS_SORT_KEY);
        $this->set('itemList', $itemList);

        $dateItemList = [];

        if ($this->request->is('post')) {
            $data = $this->request->data;

            // 届け先追加を選択の場合は追加画面へ遷移
            if (Hash::get($data, 'Outbound.address_id') === AddressComponent::CREATE_NEW_ADDRESS_ID) {
                CakeSession::write(self::MODEL_NAME . 'FORM', $this->request->data);
                return $this->redirect([
                    'controller' => 'address', 'action' => 'add', 'customer' => true,
                    '?' => ['return' => 'outbound']
                ]);
            }

            // product
            $data['Outbound']['product'] = $this->Outbound->buildParamProduct($boxList, $itemList);
            // お届け先
            $addressId = $data['Outbound']['address_id'];
            $address = $this->Address->find($addressId);
            $postal = Hash::get($address, 'postal');

            $data['Outbound'] = $this->Address->merge($addressId, $data['Outbound']);

            // ポイント取得
            $pointBalance = [];
            $this->loadModel(self::MODEL_NAME_POINT_BALANCE);
            $res = $this->PointBalance->apiGet();
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
            } else {
                $pointBalance = $res->results[0];
            }
            $this->set('pointBalance', $pointBalance);

            // 利用ポイント
            $this->PointUse->set($data);
            // ポイント残高
            $this->PointUse->data[self::MODEL_NAME_POINT_USE]['point_balance'] = $pointBalance['point_balance'];

            $this->Outbound->set($data);

            $validOutbound = $this->Outbound->validates();
            $validPointUse = $this->PointUse->validates();
            // if ($this->Outbound->validates()) {
            if ($validOutbound && $validPointUse) {
                // 表示ラベル
                $address = $this->Address->find($addressId);
                $this->set('address_text', "〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}");
                $datetime = $this->getDatetimeOne($address['postal'], $data['Outbound']['datetime_cd']);
                $this->set('datetime_text', $datetime['text']);
                CakeSession::write(self::MODEL_NAME . 'FORM', $this->request->data);
                CakeSession::write(self::MODEL_NAME, $this->Outbound->data);
                CakeSession::write(self::MODEL_NAME_POINT_USE, $this->PointUse->data);
                $this->set('pointUse', $this->PointUse->data[self::MODEL_NAME_POINT_USE]);
            } else {
                // お届け希望日と時間
                $dateItemList = [];
                if (!empty($postal)) {
                    $dateItemList = $this->getDatetime($postal);
                }
                $this->set('dateItemList', $dateItemList);
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
        $pointUse = CakeSession::read(self::MODEL_NAME_POINT_USE);
        CakeSession::delete(self::MODEL_NAME_POINT_USE);

        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }

        $this->Outbound->set($data);
        // 利用ポイント
        $this->PointUse->set($pointUse);

        $validOutbound = $this->Outbound->validates();
        $validPointUse = $this->PointUse->validates();
        // if ($this->Outbound->validates()) {
        if ($validOutbound && $validPointUse) {
            // api
            $res = $this->Outbound->apiPost($this->Outbound->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'index']);
            }

            // ポイント消費
            $res = $this->PointUse->apiPost($this->PointUse->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'index']);
            }

            // 取り出しリストクリア
            OutboundList::delete();
            (new InfoBox())->deleteCache();
            (new InfoItem())->deleteCache();
            (new Announcement())->deleteCache();
        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }
    }
}
