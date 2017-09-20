<?php

App::uses('AppValid', 'Lib');
App::uses('MinikuraController', 'Controller');
App::uses('OutboundList', 'Model');
App::uses('Outbound', 'Model');
App::uses('InfoBox', 'Model');
App::uses('InfoItem', 'Model');
App::uses('AmazonPayModel', 'Model');
App::uses('PaymentAmazonPay', 'Model');
App::uses('PaymentAmazonKitAmazonPay', 'Model');

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
        $isIsolateIsland = in_array($address['pref'], ISOLATE_ISLANDS);
        return json_encode(compact('status', 'result', 'isIsolateIsland'));
    }

    /**
     *
     */
    public function getAddressDatetimeByAmazon()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        $this->autoRender = false;

        $amazon_pay_data = $this->request->data['amazon_pay_data'];

        $amazon_order_reference_id = $amazon_pay_data['amazon_order_reference_id'];

        $this->loadModel('AmazonPayModel');
        $set_param = array();
        $set_param['amazon_order_reference_id'] = $amazon_order_reference_id;
        $set_param['address_consent_token'] = $this->Customer->getAmazonPayAccessKey();
        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->getOrderReferenceDetails($set_param);
        // 住所に関する箇所を取得
        $physicaldestination = $res['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination']['PhysicalDestination'];

        $address = array();
        $address['postal']      = $this->_editPostalFormat($physicaldestination['PostalCode']);
        $address['pref']        = $physicaldestination['StateOrRegion'];

        $result = $this->getDatetime($address['postal']);
        $status = !empty($result);
        $isIsolateIsland = in_array($address['pref'], ISOLATE_ISLANDS);
        return json_encode(compact('status', 'result', 'isIsolateIsland'));
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
     *
     */
    public function getAmazonUserInfoDetail()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        $this->autoRender = false;
        $amazon_pay_data = $this->request->data['amazon_pay_data'];
        $amazon_order_reference_id = $amazon_pay_data['amazon_order_reference_id'];

        $this->loadModel('AmazonPayModel');
        $set_param = array();
        $set_param['amazon_order_reference_id'] = $amazon_order_reference_id;
        $set_param['address_consent_token'] = $this->Customer->getAmazonPayAccessKey();
        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->getOrderReferenceDetails($set_param);
        // 住所に関する箇所を取得
        $physicaldestination = $res['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination']['PhysicalDestination'];

        $address = array();
        $address['name'] = $physicaldestination['Name'];

        $name = array();
        $name = $this->AmazonPayModel->devideUserName($address['name']);

        if (empty($name['lastname']) || empty($name['firstname']))
        {
            $status = false;
        } else {
            $status =  true;
        }

        return json_encode(compact('status', 'name'));
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
            'product_cd' => [PRODUCT_CD_MONO, PRODUCT_CD_CLEANING_PACK, PRODUCT_CD_SHOES_PACK, PRODUCT_CD_SNEAKERS, PRODUCT_CD_DIRECT_INBOUND],
        ];
        $list = $this->InfoBox->apiGetResultsWhere([], $where);
        // 取り出しリスト追加済みフラグ、追加不可フラグ
        foreach ($list as &$box) {
            $box['outbound_list_cehcked'] = in_array($box['box_id'], $this->outboundList->getBoxIdFromMonoList(), true);
            $box['outbound_list_deny'] = $this->outboundList->canAddMono($box);
        }
        //* 入庫・出庫ページ用sort #8679
        HashSorter::sort($list, InfoBox::INBOUND_OUTBOUND_SORT_KEY);
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
                PRODUCT_CD_SNEAKERS,
                PRODUCT_CD_DIRECT_INBOUND,
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
                PRODUCT_CD_SNEAKERS,
                PRODUCT_CD_DIRECT_INBOUND,
            ]
        ];
        $list = $this->InfoBox->apiGetResultsWhere([], $where);

        // 取り出しリスト追加済みフラグ、追加不可フラグ
        foreach ($list as &$box) {
            $box['outbound_list_cehcked'] = in_array($box['box_id'], $this->outboundList->getBoxIdFromBoxList(), true);
            $box['outbound_list_deny'] = $this->outboundList->canAddBox($box, false);
        }
        //* 入庫/出庫ページ用sort #8679
        HashSorter::sort($list, InfoBox::INBOUND_OUTBOUND_SORT_KEY);
        $this->set('boxList', $list);
    }

    /**
     * 取り出しリスト
     */
    public function index()
    {
        // アマゾンペイメント対応
        if ($this->Customer->isAmazonPay()) {
            $this->redirect(['controller' => 'outbound', 'action'=>'add_amazon_pay']);
        }

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
        $isIsolateIsland = '';

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
            $isIsolateIsland = in_array($address['pref'], ISOLATE_ISLANDS);
        }
        $this->set('dateItemList', $dateItemList);
        $this->set('isolateIsland', $isIsolateIsland);
        CakeSession::delete(self::MODEL_NAME . 'FORM');
        CakeSession::delete(self::MODEL_NAME_POINT_USE);
    }

    public function add_amazon_pay()
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
        $isIsolateIsland = '';

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
            $isIsolateIsland = in_array($address['pref'], ISOLATE_ISLANDS);
        }
        $this->set('dateItemList', $dateItemList);
        $this->set('isolateIsland', $isIsolateIsland);
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

            $isIsolateIsland = false;
            if (!empty($this->Outbound->data['Outbound']['pref'])) {
                $isIsolateIsland = in_array($this->Outbound->data['Outbound']['pref'], ISOLATE_ISLANDS);
            }

            // 離島 and 航空搭載不可あり
            if (!empty($this->Outbound->data['Outbound']['pref']) && $isIsolateIsland &&
                $this->Outbound->data['Outbound']['aircontent_select'] === OUTBOUND_HAZMAT_EXIST) {
                $this->Outbound->validator()->remove('datetime_cd');
            }

            $validOutbound = $this->Outbound->validates();
            $validPointUse = $this->PointUse->validates();
            // if ($this->Outbound->validates()) {
            if ($validOutbound && $validPointUse) {
                // 表示ラベル
                $address = $this->Address->find($addressId);
                $this->set('address_text', "〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}");
                $datetime = $this->getDatetimeOne($address['postal'], $data['Outbound']['datetime_cd']);
                $this->set('datetime_text', $datetime['text']);
                $this->set('isolateIsland', $isIsolateIsland);
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
                $this->set('isolateIsland', $isIsolateIsland);
                return $this->render('index');
            }
        }

    }

    /**
     *
     */
    public function confirm_amazon_pay()
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

            // product
            $data['Outbound']['product'] = $this->Outbound->buildParamProduct($boxList, $itemList);

            // お届け先
            $get_address = array();
            $get_address_amazon_pay = array();

            $get_address = [
                'firstname'         => filter_input(INPUT_POST, 'firstname'),
                'firstname_kana'    => '　',
                'lastname'          => filter_input(INPUT_POST, 'lastname'),
                'lastname_kana'     => '　',   
            ];

            // amazon pay 情報取得
            // アマゾンウィジェットID取得
            $amazon_order_reference_id = filter_input(INPUT_POST, 'amazon_order_reference_id');
            if($amazon_order_reference_id === null) {
                // 初回かリターン確認
                if(CakeSession::read('Order.amazon_pay.amazon_order_reference_id') != null) {
                    $amazon_order_reference_id = CakeSession::write('Order.amazon_pay.amazon_order_reference_id');
                }
            }

            // 住所情報等を取得
            $this->loadModel('AmazonPayModel');
            $set_param = array();
            $set_param['amazon_order_reference_id'] = $amazon_order_reference_id;
            $set_param['address_consent_token'] = $this->Customer->getAmazonPayAccessKey();
            $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

            $res = $this->AmazonPayModel->getOrderReferenceDetails($set_param);
            // GetOrderReferenceDetails
            if($res['ResponseStatus'] != '200') {
                // ↓AmazonPayのエラーがどのような頻度で起きるか様子見するためのログ。消さないでー！
                CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));
                $this->Flash->validation('Amazon Pay からの情報取得に失敗しました。再度お試し下さい。', ['key' => 'customer_amazon_pay_info']);
                return $this->render('add_amazon_pay');
            }

             // 有効なアマゾンウィジェットIDを設定
            CakeSession::write('Order.amazon_pay.amazon_order_reference_id', $amazon_order_reference_id);
            // 住所に関する箇所を取得
            $physicaldestination = $res['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination']['PhysicalDestination'];
            $physicaldestination = $this->AmazonPayModel->wrapPhysicalDestination($physicaldestination);

            $PostalCode = $this->_editPostalFormat($physicaldestination['PostalCode']);
            $get_address_amazon_pay['postal']      = $PostalCode;
            $get_address_amazon_pay['pref']        = $physicaldestination['StateOrRegion'];

            $get_address_amazon_pay['address1'] = $physicaldestination['AddressLine1'];
            $get_address_amazon_pay['address2'] = $physicaldestination['AddressLine2'];
            $get_address_amazon_pay['address3'] = $physicaldestination['AddressLine3'];
            $get_address_amazon_pay['tel1']        = $physicaldestination['Phone'];

            $get_address = array_merge($get_address, $get_address_amazon_pay);

            CakeSession::write('OutboundAddress', $get_address);

            //バリデーション表示用
            $validation = AppValid::validate($get_address_amazon_pay);
            if (!empty($validation)) {
                $this->Flash->validation(AMAZON_PAY_ERROR_URGING_INPUT, ['key' => 'customer_amazon_pay_info']);
            }

            $data['Outbound'] = array_merge_recursive($data['Outbound'], $get_address);
            //$data['Outbound'] = $get_address;
            $data['Outbound']['address_id'] = '-10';

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

            $isIsolateIsland = false;

            if (!empty($this->Outbound->data['Outbound']['pref'])) {
                $isIsolateIsland = in_array($this->Outbound->data['Outbound']['pref'], ISOLATE_ISLANDS);
            }

            // 離島 and 航空搭載不可あり
            if (!empty($this->Outbound->data['Outbound']['pref']) && $isIsolateIsland &&
                $this->Outbound->data['Outbound']['aircontent_select'] === OUTBOUND_HAZMAT_EXIST) {
                $this->Outbound->validator()->remove('datetime_cd');
            }

            $validOutbound = $this->Outbound->validates();
            $validPointUse = $this->PointUse->validates();

            if ($validOutbound && $validPointUse) {
                // 表示ラベル
                //$address = $this->Address->find($addressId);
                $this->set('address_text', "〒{$get_address['postal']} {$get_address['pref']}{$get_address['address1']}{$get_address['address2']}{$get_address['address3']}　{$get_address['lastname']}{$get_address['firstname']}");
                $datetime = $this->getDatetimeOne($get_address['postal'], $data['Outbound']['datetime_cd']);
                $this->set('datetime_text', $datetime['text']);
                $this->set('isolateIsland', $isIsolateIsland);
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
                $this->set('isolateIsland', $isIsolateIsland);
                return $this->render('add_amazon_pay');
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

        $isIsolateIsland = false;
        if (!empty($this->Outbound->data['Outbound']['pref'])) {
            $isIsolateIsland = in_array($this->Outbound->data['Outbound']['pref'], ISOLATE_ISLANDS);
        }

        $existHazmat = false;
        // 離島 and 航空搭載不可あり
        if (!empty($this->Outbound->data['Outbound']['pref']) && $isIsolateIsland &&
            $this->Outbound->data['Outbound']['aircontent_select'] === OUTBOUND_HAZMAT_EXIST) {
            $this->Outbound->validator()->remove('datetime_cd');
            $existHazmat = true;
        }

        $validOutbound = $this->Outbound->validates();
        $validPointUse = $this->PointUse->validates();
        // if ($this->Outbound->validates()) {
        if ($validOutbound && $validPointUse) {
            // api
            if ($existHazmat) {
                $this->loadModel('ContactAny');
                $res = $this->ContactAny->apiPostIsolateIsland($this->Outbound->data['Outbound']);
            } else {
                $res = $this->Outbound->apiPost($this->Outbound->toArray());
            }

            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'index']);
            }

            if ($this->PointUse->verifyCallPointUse()) {
                // ポイント消費
                $res = $this->PointUse->apiPost($this->PointUse->toArray());
                if (!empty($res->error_message)) {
                    $this->Flash->set($res->error_message);
                    return $this->redirect(['action' => 'index']);
                }
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


    /**
     *
     */
    public function complete_amazon_pay()
    {
        $data = CakeSession::read(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME . 'FORM');
        $pointUse = CakeSession::read(self::MODEL_NAME_POINT_USE);
        CakeSession::delete(self::MODEL_NAME_POINT_USE);

        if (empty($data)) {

            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add_amazon_pay']);
        }

        $this->Outbound->set($data);
        // 利用ポイント
        $this->PointUse->set($pointUse);

        $isIsolateIsland = false;
        if (!empty($this->Outbound->data['Outbound']['pref'])) {
            $isIsolateIsland = in_array($this->Outbound->data['Outbound']['pref'], ISOLATE_ISLANDS);
        }

        $existHazmat = false;
        // 離島 and 航空搭載不可あり
        if (!empty($this->Outbound->data['Outbound']['pref']) && $isIsolateIsland &&
            $this->Outbound->data['Outbound']['aircontent_select'] === OUTBOUND_HAZMAT_EXIST) {
            $this->Outbound->validator()->remove('datetime_cd');
            $existHazmat = true;
        }

        $validOutbound = $this->Outbound->validates();
        $validPointUse = $this->PointUse->validates();
        // if ($this->Outbound->validates()) {
        if ($validOutbound && $validPointUse) {
            // api
            if ($existHazmat) {
                $this->loadModel('ContactAny');
                $res = $this->ContactAny->apiPostIsolateIsland($this->Outbound->data['Outbound']);
            } else {
                $res = $this->Outbound->apiPost($this->Outbound->toArray());
            }

            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'add_amazon_pay']);
            }

            if ($this->PointUse->verifyCallPointUse()) {
                // ポイント消費
                $res = $this->PointUse->apiPost($this->PointUse->toArray());
                if (!empty($res->error_message)) {
                    $this->Flash->set($res->error_message);
                    return $this->redirect(['action' => 'add_amazon_pay']);
                }
            }

            // 取り出しリストクリア
            OutboundList::delete();
            (new InfoBox())->deleteCache();
            (new InfoItem())->deleteCache();
            (new Announcement())->deleteCache();
        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add_amazon_pay']);
        }
    }
}
