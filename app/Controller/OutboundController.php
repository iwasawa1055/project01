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
App::uses('OutboundAmazonPay', 'Model');
App::uses('OutboundAmazonPayYumail', 'Model');
App::uses('OutboundCreditCard', 'Model');
App::uses('OutboundCreditCardYumail', 'Model');

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

    public function getAddressDatetimeByPostal()
    {
        $this->autoRender = false;
        if (!$this->request->is('ajax')) {
            return false;
        }

        $postal = $this->request->data['postal'];
        $result = $this->getDatetime($postal);
        $status = !empty($result);
        return json_encode(compact('status', 'result'));
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

        $amazon_order_reference_id = $this->request->data['amazon_order_reference_id'];

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
        $amazon_order_reference_id = $this->request->data['amazon_order_reference_id'];

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
            $this->Flash->set(POINT_BALANCE_ERROR);
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
            $this->Flash->set(POINT_BALANCE_ERROR);
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
                $this->Flash->set(POINT_BALANCE_ERROR);
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
                $this->Flash->set(POINT_BALANCE_ERROR);
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
        // $pointUse = CakeSession::read(self::MODEL_NAME_POINT_USE);
        CakeSession::delete(self::MODEL_NAME_POINT_USE);

        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }

        $this->Outbound->set($data);
        // 利用ポイント
        // $this->PointUse->set($pointUse);

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
        // $validPointUse = $this->PointUse->validates();
        // if ($this->Outbound->validates()) {
        if ($validOutbound) {
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

            // if ($this->PointUse->verifyCallPointUse()) {
            //     // ポイント消費
            //     $res = $this->PointUse->apiPost($this->PointUse->toArray());
            //     if (!empty($res->error_message)) {
            //         $this->Flash->set($res->error_message);
            //         return $this->redirect(['action' => 'index']);
            //     }
            // }

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

    public function library_select_item()
    {
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->layout = '';

        // 初期表示
        if ($this->request->is('get')) {
            $item_id = CakeSession::read('app.data.library.item_id');
            $box_id = CakeSession::read('app.data.library.box_id');

            // アイテム詳細からの遷移
            if (isset($_GET['item_id'])) {
                $item_id = [$_GET['item_id']];
            }

            // ボックス詳細からの遷移
            if (isset($_GET['box_id'])) {
                $box_id = [$_GET['box_id']];
            }

            // 完了画面からエラーでの遷移
            if (isset($_GET['error'])) {
                $this->set('complete_error', true);
            }

            $this->set('item_id', is_null($item_id) ? '' : implode(',', $item_id));
            $this->set('box_id', is_null($box_id) ? '' : implode(',', $box_id));

        // 確認へ遷移する場合
        } elseif ($this->request->is('post')) {
            // セッションに格納されたitem, boxを削除
            CakeSession::delete('app.data.library.item_id');
            CakeSession::delete('app.data.library.box_id');

            $item_id = isset($_POST['item_id']) ? $_POST['item_id'] : null;
            $box_id = isset($_POST['box_id']) ? $_POST['box_id'] : null;
            $this->set('item_id', is_null($item_id) ? '' : implode(',', $item_id));
            $this->set('box_id', is_null($box_id) ? '' : implode(',', $box_id));

            // 選択したアイテムの確認
            if (isset($_POST['select-deposit']) && $_POST['select-deposit'] == 'item') {
                CakeSession::Write('app.data.library.select-deposit', $_POST['select-deposit']);

                // item_idの確認
                if (isset($_POST['item_id']) && !empty($_POST['item_id'])) {
                    CakeSession::Write('app.data.library.item_id', $_POST['item_id']);
                } else {
                    // 選択されたアイテムが存在しない
                    $this->set('no_select_item_error', true);
                    return $this->render('library_select_item');
                }
            } elseif (isset($_POST['select-deposit']) && $_POST['select-deposit'] == 'box') {
                CakeSession::Write('app.data.library.select-deposit', $_POST['select-deposit']);

                // box_idの確認
                if (isset($_POST['box_id']) && !empty($_POST['box_id'])) {
                    CakeSession::Write('app.data.library.box_id', $_POST['box_id']);
                } else {
                    // 選択されたボックスが存在しない
                    $this->set('no_select_item_error', true);
                    return $this->render('library_select_item');
                }
            } else {
                $this->set('no_select_item_error', true);
                return $this->render('library_select_item');
            }

            // 選択したアイテムが300以上ないか確認
            if (count($this->_getLibraryOutboundItemList()) > 300) {
                $this->set('over_select_item_error', true);
                return $this->render('library_select_item');
            }

            if ($this->Customer->isAmazonPay()) {
                return $this->redirect('/outbound/library_input_address_amazon_pay');
            } else {
                return $this->redirect('/outbound/library_input_address');
            }
        }
    }

    public function library_input_address()
    {
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->layout = '';

        // メール便の確認 itemが1個の場合
        $item_id = CakeSession::read('app.data.library.item_id');
        $box_id = CakeSession::read('app.data.library.box_id');

        if (is_array($item_id) && (count($item_id) == 1)) {
            $yumail = true;
        } elseif (is_array($box_id) && (count($box_id) == 1)) {
            // 選択したboxが1個でitemが1個しかない場合
            $items = $this->_getLibraryItemByBoxId($box_id[0]);
            if (is_array($items) && (count($items) == 1)) {
                $yumail = true;
            } else {
                $yumail = false;
            }
        } else {
            $yumail = false;
        }
        $this->set('yumail', $yumail);

        // デフォルトのクレカを取得
        $this->loadModel('PaymentGMOCreditCard');
        $default_card = $this->PaymentGMOCreditCard->apiGetDefaultCard();
        $this->set('default_card', $default_card);

        if ($this->request->is('get')) {
            if (CakeSession::Read('app.data.library.datetime_cd')) {
                $this->set('datetime_cd', CakeSession::Read('app.data.library.datetime_cd'));
            }
        } elseif ($this->request->is('post')) {
            CakeSession::delete('app.data.library.address');
            CakeSession::delete('app.data.library.datetime_cd');

            $error = false;

            $this->loadModel('CustomerAddress');
            // 配送先を指定している場合
            if (isset($_POST['address']) && $_POST['address'] == 'add') {
                // 電話番号の全角許容
                $this->request->data['CustomerAddress']['tel1'] = self::_wrapConvertKana($this->request->data['CustomerAddress']['tel1']);

                $this->CustomerAddress->set($this->request->data);
                // バリデーションエラー確認
                if ($this->CustomerAddress->validates() === false) {
                    $error = true;
                }

                // セッションに入力値を保存
                CakeSession::write('CustomerAddress', $this->CustomerAddress->toArray());
            }

            CakeSession::Write('app.data.library.address', $_POST['address']);

            // クレジットカードの確認
            if (isset($_POST['resister_credit']) && $_POST['resister_credit'] == '1') {
                if (empty(filter_input(INPUT_POST, 'gmo_token'))) {
                    $this->set('credit_error', 'クレジットカード情報の取得に失敗しました');
                    $error = true;
                }

                $this->loadModel('PaymentGMOCreditCardCheck');
                $res = $this->PaymentGMOCreditCardCheck->getCreditCardCheck(['gmo_token' => filter_input(INPUT_POST, 'gmo_token_for_check')]);

                if (!empty($res->error_message)) {
                    $this->set('credit_error', 'クレジットカードエラーが発生しました。　エラーコード:' . $res->message);
                    $error = true;
                }

                $this->loadModel('PaymentGMOCreditCard');

                // 新規カード登録
                if (is_null($default_card)) {
                    $this->PaymentGMOCreditCard->set(['PaymentGMOCreditCard' => ['gmo_token' => filter_input(INPUT_POST, 'gmo_token')]]);
                    $res = $this->PaymentGMOCreditCard->apiPost($this->PaymentGMOCreditCard->toArray());
                // カード変更
                } else {
                    $this->PaymentGMOCreditCard->set(['PaymentGMOCreditCard' => ['gmo_token' => filter_input(INPUT_POST, 'gmo_token')]]);
                    $res = $this->PaymentGMOCreditCard->apiPut($this->PaymentGMOCreditCard->toArray());
                }

                if (!empty($res->error_message)) {
                    $this->set('credit_error', 'クレジットカードエラーが発生しました。　エラーコード:' . $res->message);
                    $error = true;
                }
            }

            //　クレジットの登録に問わずsecurity_cdは格納する
            //  if (isset($_POST['security_cd']) && $_POST['security_cd'] == '') {
            //      $this->set('credit_error', 'クレジットカードのセキュリティコードを入力してください。');
            //      $error = true;
            //  }
            //  CakeSession::Write('app.data.library.security_cd', $_POST['security_cd']);

            // 配送日時の確認
            if ($yumail == false) {
                if (isset($_POST['datetime_cd']) && $_POST['datetime_cd'] == '0000-00-00') {
                    $this->set('datetime_cd_error', 'お届け希望日時を選択してください。');
                    $error = true;
                }
                $this->set('datetime_cd', $_POST['datetime_cd']);
                CakeSession::Write('app.data.library.datetime_cd', $_POST['datetime_cd']);
            }

            if ($error == true) {
                return $this->render('library_input_address');
            }

            return $this->redirect('/outbound/library_confirm');
        }
    }

    public function library_confirm()
    {
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->layout = '';

        $this->_setLibraryPriceAndItem();

        // 配送先
        if (CakeSession::Read('app.data.library.address') == 'add') {
            // 新規登録
            $address = CakeSession::Read('CustomerAddress');
        } else {
            // アドレスリストから選択
            foreach ($this->Address->get() as $a) {
                if ($a['address_id'] == CakeSession::Read('app.data.library.address')) {
                    $address = $a;
                }
            }
        }
        $this->set('address', $address);

        // 配送時間
        if (CakeSession::Read('app.data.library.datetime_cd')) {
            $this->set('datetime_cd', CakeSession::Read('app.data.library.datetime_cd'));
        }

        // デフォルトのクレカを取得
        $this->loadModel('PaymentGMOCreditCard');
        $default_card = $this->PaymentGMOCreditCard->apiGetDefaultCard();
        $this->set('default_card', $default_card);
    }

    public function library_complete()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
            'Outbound/library_confirm',
            ], true) === false ) {
            $this->redirect('/outbound/library_select_item');
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->layout = '';

        $price = $this->_setLibraryPriceAndItem();

        // 配送先
        if (CakeSession::Read('app.data.library.address') == 'add') {
            // 新規登録
            $address = CakeSession::Read('CustomerAddress');
        } else {
            // アドレスリストから選択
            foreach ($this->Address->get() as $a) {
                if ($a['address_id'] == CakeSession::Read('app.data.library.address')) {
                    $address = $a;
                }
            }
        }
        $this->set('address', $address);

        // 配送時間
        $this->set('datetime_cd', CakeSession::Read('app.data.library.datetime_cd'));

        // デフォルトのクレカを取得
        $this->loadModel('PaymentGMOCreditCard');
        $default_card = $this->PaymentGMOCreditCard->apiGetDefaultCard();
        $this->set('default_card', $default_card);

        // 配送先を登録
        if (isset($address['register_address_book'])) {
            $this->loadModel('CustomerAddress');
            $res = $this->CustomerAddress->apiPost($address);
            if (!empty($res->error_message)) {
                $this->Flash->validation($res->error_message, ['key' => 'complete_error']);
                $this->redirect('/outbound/library_select_item?error=1');
            }
        }

        // 決済処理
        $item_list = $this->_getLibraryOutboundItemList();

        // product作成
        $product_list = [];
        foreach ($item_list as $v) {
            $product_list[] = $v["box"]["product_cd"] . ':' . $v["box"]["box_id"] . ':' . $v["item_id"];
        }

        // 1個の場合はゆうメール便
        if (count($product_list) == 1) {
            $this->loadModel('OutboundCreditCardYumail');
            $request_params = [];
            $request_params['OutboundCreditCardYumail'] = [
                'price'=>$price,
                'delivery_type'=>'11',
                'product'=>implode(',', $product_list),
                'name'=>$address['lastname'] . $address['firstname'],
                'pref'=>$address['pref'],
                'address1'=>$address['address1'],
                'address2'=>$address['address2'],
                'address3'=>$address['address3'],
                'postal'=>$address['postal'],
            ];
            $this->OutboundCreditCardYumail->set($request_params);
            $res = $this->OutboundCreditCardYumail->apiPost($this->OutboundCreditCardYumail->toArray());

            if (!empty($res->error_message)) {
                $this->Flash->validation($res->error_message, ['key' => 'complete_error']);
                $this->redirect('/outbound/library_select_item?error=1');
            }
        } else {
            $this->loadModel('OutboundCreditCard');
            $request_params = [];
            $request_params['OutboundCreditCard'] = [
                'price'=>$price,
                'product'=>implode(',', $product_list),
                'lastname'=>$address['lastname'],
                'firstname'=>$address['firstname'],
                'pref'=>$address['pref'],
                'address1'=>$address['address1'],
                'address2'=>$address['address2'],
                'address3'=>$address['address3'],
                'postal'=>$address['postal'],
                'tel1'=>$address['tel1'],
            ];
            $this->OutboundCreditCard->set($request_params);
            $res = $this->OutboundCreditCard->apiPost($this->OutboundCreditCard->toArray());

            if (!empty($res->error_message)) {
                $this->Flash->validation($res->error_message, ['key' => 'complete_error']);
                $this->redirect('/outbound/library_select_item?error=1');
            }
        }
        CakeSession::delete('app.data.library');
    }


    public function library_input_address_amazon_pay()
    {
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->layout = '';

        // メール便の確認 itemが1個の場合
        $item_id = CakeSession::read('app.data.library.item_id');
        $box_id = CakeSession::read('app.data.library.box_id');

        if (is_array($item_id) && (count($item_id) == 1)) {
            $yumail = true;
        } elseif (is_array($box_id) && (count($box_id == 1))) {
            // 選択したboxが1個でitemが1個しかない場合
            $items = $this->_getLibraryItemByBoxId($box_id[0]);
            if (is_array($items) && (count($items) == 1)) {
                $yumail = true;
            } else {
                $yumail = false;
            }
        } else {
            $yumail = false;
        }
        $this->set('yumail', $yumail);

        if ($this->request->is('get')) {
            if (CakeSession::Read('app.data.library.datetime_cd')) {
                $this->set('datetime_cd', CakeSession::Read('app.data.library.datetime_cd'));
            }
        } elseif ($this->request->is('post')) {
            CakeSession::delete('app.data.library.address');
            CakeSession::delete('app.data.library.datetime_cd');

            $error = false;

            // amazon_order_reference_idの確認
            if (isset($_POST['amazon_order_reference_id']) == false) {
                $this->set('amazon_order_reference_id_error', 'Amazon Payの情報を取得できませんでした。');
                $error = true;
            }
            CakeSession::Write('app.data.library.amazon_order_reference_id', $_POST['amazon_order_reference_id']);

            // amazonから配送情報の取得
            $address = $this->_getAddressByAmazonOrderReferenceId($_POST['amazon_order_reference_id']);
            if ($address == false) {
                $this->set('amazon_order_reference_id_error', 'Amazon Payの情報を取得できませんでした。');
                $error = true;
            }

            // 配送日時の確認
            if ($yumail == false) {
                if (isset($_POST['datetime_cd']) && $_POST['datetime_cd'] == '0000-00-00') {
                    $this->set('datetime_cd_error', 'お届け希望日時を選択してください。');
                    $error = true;
                }
                $this->set('datetime_cd', $_POST['datetime_cd']);
                CakeSession::Write('app.data.library.datetime_cd', $_POST['datetime_cd']);
            }

            if ($error == true) {
                return $this->render('library_input_address_amazon_pay');
            }

            return $this->redirect('/outbound/library_confirm_amazon_pay');
        }
    }

    public function library_confirm_amazon_pay()
    {
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->layout = '';

        $this->_setLibraryPriceAndItem();

        // 配送先
        $address = $this->_getAddressByAmazonOrderReferenceId(CakeSession::Read('app.data.library.amazon_order_reference_id'));
        $this->set('address', $address);

        // 配送時間
        if (CakeSession::Read('app.data.library.datetime_cd')) {
            $this->set('datetime_cd', CakeSession::Read('app.data.library.datetime_cd'));
        }
    }

    public function library_complete_amazon_pay()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
            'Outbound/library_confirm_amazon_pay',
            ], true) === false ) {
            $this->redirect('/outbound/library_select_item');
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->layout = '';

        $price = $this->_setLibraryPriceAndItem();

        // 配送先
        $address = $this->_getAddressByAmazonOrderReferenceId(CakeSession::Read('app.data.library.amazon_order_reference_id'));
        $this->set('address', $address);

        // 配送時間
        $this->set('datetime_cd', CakeSession::Read('app.data.library.datetime_cd'));

        // 決済処理
        $item_list = $this->_getLibraryOutboundItemList();

        // product作成
        $product_list = [];
        foreach ($item_list as $v) {
            $product_list[] = $v["box"]["product_cd"] . ':' . $v["box"]["box_id"] . ':' . $v["item_id"];
        }

        // 1個の場合はゆうメール便
        if (count($product_list) == 1) {
            $this->loadModel('OutboundAmazonPayYumail');
            $request_params = [];
            $request_params['OutboundAmazonPayYumail'] = [
                'amazon_order_reference_id'=>CakeSession::Read('app.data.library.amazon_order_reference_id'),
                'price'=>$price,
                'delivery_type'=>'11',
                'product'=>implode(',', $product_list),
                'name'=>$address['lastname'] . $address['firstname'],
                'pref'=>$address['pref'],
                'address1'=>$address['address1'],
                'address2'=>$address['address2'],
                'address3'=>$address['address3'],
                'postal'=>$address['postal'],
            ];
            $this->OutboundAmazonPayYumail->set($request_params);
            $res = $this->OutboundAmazonPayYumail->apiPost($this->OutboundAmazonPayYumail->toArray());

            if (!empty($res->error_message)) {
                $this->Flash->validation($res->error_message, ['key' => 'complete_error']);
                $this->redirect('/outbound/library_select_item?error=1');
            }
        } else {
            $this->loadModel('OutboundAmazonPay');
            $request_params = [];
            $request_params['OutboundAmazonPay'] = [
                'amazon_order_reference_id'=>CakeSession::Read('app.data.library.amazon_order_reference_id'),
                'price'=>$price,
                'product'=>implode(',', $product_list),
                'lastname'=>$address['lastname'],
                'firstname'=>$address['firstname'],
                'pref'=>$address['pref'],
                'address1'=>$address['address1'],
                'address2'=>$address['address2'],
                'address3'=>$address['address3'],
                'postal'=>$address['postal'],
                'tel1'=>$address['tel1'],
            ];
            $this->OutboundAmazonPay->set($request_params);
            $res = $this->OutboundAmazonPay->apiPost($this->OutboundAmazonPay->toArray());

            if (!empty($res->error_message)) {
                $this->Flash->validation($res->error_message, ['key' => 'complete_error']);
                $this->redirect('/outbound/library_select_item?error=1');
            }
        }
        CakeSession::delete('app.data.library');
    }

    public function as_get_library_box()
    {
        $this->autoRender = false;

        // 対象ボックス一覧
        $where = [
            'box_status' => [BOXITEM_STATUS_INBOUND_DONE],
            'product_cd' => [PRODUCT_CD_LIBRARY],
        ];
        $list = $this->InfoBox->apiGetResultsWhere([], $where);
        return json_encode($list);
    }

    public function as_get_library_item()
    {
        $this->autoRender = false;

        // 対象アイテム一覧
        $where = [
            'item_status' => [BOXITEM_STATUS_INBOUND_DONE],
            'box.product_cd' => [
                PRODUCT_CD_LIBRARY,
            ]
        ];
        $list = $this->InfoItem->apiGetResultsWhere([], $where);
        return json_encode($list);
    }

    private function _getLibraryOutboundItemList()
    {
        $item_id = CakeSession::read('app.data.library.item_id');
        $box_id = CakeSession::read('app.data.library.box_id');

        // 保持しているアイテムすべての配列を作成
        $where = [
            'item_status' => [BOXITEM_STATUS_INBOUND_DONE],
            'box.product_cd' => [
                PRODUCT_CD_LIBRARY,
            ]
        ];
        $info_item_list = $this->InfoItem->apiGetResultsWhere([], $where);
        $item_list = [];
        foreach ($info_item_list as $item) {
            $item_list[$item['box']['box_id']][$item['item_id']] = $item;
        }

        $outbound_item_list = [];
        if ($item_id) {
            foreach ($item_list as $k => $v) {
                foreach ($v as $kk => $vv) {
                    if (in_array($kk, $item_id)) {
                        $outbound_item_list[] = $vv;
                    }
                }
            }
        }

        if ($box_id) {
            foreach ($item_list as $k => $v) {
                if (in_array($k, $box_id)) {
                    foreach ($v as $kk => $vv) {
                        $outbound_item_list[] = $vv;
                    }
                }
            }
        }

        return $outbound_item_list;
    }

    private function _setLibraryPriceAndItem()
    {
        $item_id = CakeSession::read('app.data.library.item_id');
        $box_id = CakeSession::read('app.data.library.box_id');

        // 保持しているアイテムすべての配列を作成
        $where = [
            'item_status' => [BOXITEM_STATUS_INBOUND_DONE],
            'box.product_cd' => [
                PRODUCT_CD_LIBRARY,
            ]
        ];
        $info_item_list = $this->InfoItem->apiGetResultsWhere([], $where);
        $item_list = [];
        foreach ($info_item_list as $item) {
            $item_list[$item['box']['box_id']][$item['item_id']] = $item;
        }

        // 金額を計算
        $outbound_item_list = [];
        $outbound_item_price = 0;
        if ($item_id) {
            // アイテムIDからアイテムを取得
            foreach ($info_item_list as $item) {
                if (in_array($item['item_id'], $item_id)) {
                    $outbound_item_list[] = $item;
                }
            }

            $outbound_box_id = [];
            $tmp_item_list = $item_list;
            foreach ($outbound_item_list as $oil) {
                $outbound_box_id[] = $oil['box']['box_id'];
                unset($tmp_item_list[$oil['box']['box_id']][$oil['item_id']]);
            }

            // 出庫するアイテムのbox_id
            $outbound_box_id = array_unique($outbound_box_id);

            // 全量出庫したboxを割り出す
            $all_outbound_box_id = [];
            foreach ($item_list as $ilk => $ilv) {
                if (count($tmp_item_list[$ilk]) == 0) {
                    $all_outbound_box_id[] = $ilk;
                }
            }

            // 全量出庫したboxがあればbox_idに格納する
            if (!empty($all_outbound_box_id)) {
                $box_id = $all_outbound_box_id;

                // 全量出庫するアイテムは$outbound_item_listから削除する
                foreach ($outbound_item_list as $oilk => $oilv) {
                    foreach ($box_id as $bi) {
                        if ($oilv['box']['box_id'] == $bi) {
                            unset($outbound_item_list[$oilk]);
                        }
                    }
                }
            }
            // 歯抜けのキーを詰める
            $outbound_item_list = array_values($outbound_item_list);

            if (count($outbound_item_list) > 0) {
                $outbound_item_price = (count($outbound_item_list) * LIBRARY_OUTBOUND_PER_ITEM_PRICE) + LIBRARY_OUTBOUND_BASIC_PRICE;
                $this->set('outbound_item_price', $outbound_item_price);
                $this->set('outbound_item_list', $outbound_item_list);
            }
        }

        $outbound_box_list = [];
        $outbound_box_price = 0;
        if ($box_id) {
            foreach ($box_id as $bi) {
                $outbound_box_list[$bi]['item'] = $item_list[$bi];
                $box = $this->_getLibraryBoxByBoxId($bi);
                $outbound_box_list[$bi]['box'] = $box[0];
                if (date('Y-m-d', strtotime('16 month ago')) > $box[0]['last_inbound_date']) {
                    $outbound_box_list[$bi]['price'] = 0;
                } else {
                    $outbound_box_list[$bi]['price'] = LIBRARY_OUTBOUND_CANCELLATION_PRICE;
                    $outbound_box_price += LIBRARY_OUTBOUND_CANCELLATION_PRICE;
                }
            }
            // 課金対象になるボックスを算出
            $this->set('outbound_box_price', $outbound_box_price);
            $this->set('outbound_box_list', $outbound_box_list);
        }

        // 総計
        $outbound_total_price = $outbound_item_price + $outbound_box_price;
        $this->set('outbound_total_price', $outbound_total_price);

        return $outbound_total_price;
    }

    private function _getLibraryBoxByBoxId($box_id)
    {
        // 対象アイテム一覧
        $where = [
            'product_cd' => [PRODUCT_CD_LIBRARY],
            'box_id' => $box_id,
        ];
        return $this->InfoBox->apiGetResultsWhere([], $where);
    }

    private function _getLibraryItemByBoxId($box_id)
    {
        // 対象アイテム一覧
        $where = [
            'item_status' => [BOXITEM_STATUS_INBOUND_DONE],
            'box.product_cd' => [
                PRODUCT_CD_LIBRARY,
            ],
            'box.box_id' => $box_id,
        ];
        return $this->InfoItem->apiGetResultsWhere([], $where);
    }

    private function _getAddressByAmazonOrderReferenceId($amazon_order_reference_id)
    {
        $this->loadModel('AmazonPayModel');
        $set_param = array();
        $set_param['amazon_order_reference_id'] = $amazon_order_reference_id;
        $set_param['address_consent_token'] = $this->Customer->getAmazonPayAccessKey();
        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->getOrderReferenceDetails($set_param);
        // GetOrderReferenceDetails
        if ($res['ResponseStatus'] != '200') {
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));
            return false;
        }

        // 住所に関する箇所を取得
        $physicaldestination = $res['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination']['PhysicalDestination'];
        $physicaldestination = $this->AmazonPayModel->wrapPhysicalDestination($physicaldestination);

        $PostalCode = $this->_editPostalFormat($physicaldestination['PostalCode']);
        $address = [];
        $address['postal']      = $PostalCode;
        $address['pref']        = $physicaldestination['StateOrRegion'];

        $address['address1'] = $physicaldestination['AddressLine1'];
        $address['address2'] = $physicaldestination['AddressLine2'];
        $address['address3'] = $physicaldestination['AddressLine3'];
        $address['tel1']        = $physicaldestination['Phone'];

        // 名前を取得
        $res = $this->AmazonPayModel->getOrderReferenceDetails($set_param);
        // 住所に関する箇所を取得
        $physicaldestination = $res['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination']['PhysicalDestination'];
        $name = array();
        $name = $this->AmazonPayModel->devideUserName($physicaldestination['Name']);
        if (empty($name['lastname'])||empty($name['firstname'])) {
            return false;
        }
        $address['lastname']  = $name['lastname'];
        $address['firstname'] = $name['firstname'];

        return $address;
    }
}
