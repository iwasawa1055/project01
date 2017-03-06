<?php

App::uses('MinikuraController', 'Controller');
App::uses('CustomerKitPrice', 'Model');

class OrderController extends MinikuraController
{
    const MODEL_NAME = 'OrderKit';
    const MODEL_NAME_CARD = 'PaymentGMOCard';
    const MODEL_NAME_DATETIME = 'DatetimeDeliveryKit';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        // 法人口座未登録用遷移
        $actionCannot = 'cannot';
        if ($this->action !== $actionCannot && !$this->Customer->isEntry() && !$this->Customer->canOrderKit()) {
            return $this->redirect(['action' => $actionCannot]);
        }

        $this->Order = $this->Components->load('Order');
        $this->Order->init($this->Customer->getToken()['division'], $this->Customer->hasCreditCard());
        $this->loadModel(self::MODEL_NAME_CARD);
        $this->loadModel(self::MODEL_NAME_DATETIME);
        $this->set('validErrors', []);

        // 配送先
        $this->set('address', $this->Address->get());
        $this->set('default_payment', $this->Customer->getDefaultCard());
    }

    /**
     * アクセス拒否
     */
    protected function isAccessDeny()
    {
        if (!$this->Customer->canOrderKit() && $this->action === 'complete') {
            return true;
        }
        return false;
    }

    /**
     *
     */
    public function add()
    {
        if ($this->Customer->getInfo()['oem_cd'] === OEM_CD_LIST['sneakers']) {
            return $this->setAction('add_sneakers');
        }

        return $this->setAction('input');
    }

    /**
     *
     */
    public function input()
    {
        // スニーカー判定
        if ($this->Customer->getInfo()['oem_cd'] === OEM_CD_LIST['sneakers']) {
            return $this->setAction('add_sneakers');
        }

        $isBack = Hash::get($this->request->query, 'back');
        $res_datetime = [];
        $data = CakeSession::read(self::MODEL_NAME);
		CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'.print_r($data, true));
        if ($isBack && !empty($data)) {
            if (!array_key_exists('address_id', $data)) {
                $data['address_id'] = '';
            }
            // 前回追加選択は最後のお届け先を選択
            if ($data['address_id'] === AddressComponent::CREATE_NEW_ADDRESS_ID) {
                $data['address_id'] = Hash::get($this->Address->last(), 'address_id', '');
                $data['datetime_cd'] = '';
            }
            $this->request->data[self::MODEL_NAME] = $data;
            if (!empty($data['address_id'])) {
                $res_datetime = $this->getDatetimeDeliveryKit($data['address_id']);
            }
        }

        $this->set('datetime', $res_datetime);

        CakeSession::delete(self::MODEL_NAME);
    }

    /**
     *
     */
    public function confirm()
    {
        if($this->Customer->getInfo()['oem_cd'] === OEM_CD_LIST['sneakers']) {
            return $this->setAction('confirm_sneakers');
        }

        $data = Hash::get($this->request->data, self::MODEL_NAME);
        if (empty($data)) {
            return $this->render('add');
        }
        $model = $this->Order->model($data);
        $paymentModelName = $model->getModelName();

        // 届け先追加を選択の場合は追加画面へ遷移
        if (Hash::get($model->toArray(), 'address_id') === AddressComponent::CREATE_NEW_ADDRESS_ID) {
            CakeSession::write(self::MODEL_NAME, $model->toArray());
            return $this->redirect([
                'controller' => 'address', 'action' => 'add', 'customer' => true,
                '?' => ['return' => 'order']
            ]);
        }

        // キットPOSTデータキー
        $dataKeyNum = [
            KIT_CD_MONO => 'mono_num',
            KIT_CD_MONO_APPAREL => 'mono_appa_num',
            KIT_CD_MONO_BOOK => 'mono_book_num',
            KIT_CD_HAKO => 'hako_num',
            KIT_CD_HAKO_APPAREL => 'hako_appa_num',
            KIT_CD_HAKO_BOOK => 'hako_book_num',
            KIT_CD_CLEANING_PACK => 'cleaning_num',
        ];

        // 料金（サービス（商品）ごと）集計
        $kitPrice = new CustomerKitPrice();
        $total = ['num' => 0, 'price' => 0];
        $productKitList = [
            PRODUCT_CD_MONO => [
                'kitList' => [KIT_CD_MONO => 0, KIT_CD_MONO_APPAREL => 0, KIT_CD_MONO_BOOK => 0],
                'subtotal' => ['num' => 0, 'price' => 0]
            ],
            PRODUCT_CD_HAKO => [
                'kitList' => [KIT_CD_HAKO => 0, KIT_CD_HAKO_APPAREL => 0, KIT_CD_HAKO_BOOK => 0],
                'subtotal' => ['num' => 0, 'price' => 0]
            ],
            PRODUCT_CD_CLEANING_PACK => [
                'kitList' => [KIT_CD_CLEANING_PACK => 0],
                'subtotal' => ['num' => 0, 'price' => 0]
            ],
        ];
        foreach ($productKitList as $productCd => &$product) {
            $product['pramaKit'] = [];
            // 個数集計
            foreach ($product['kitList'] as $kitCd => $d) {
                $num = Hash::get($this->request->data, self::MODEL_NAME . '.' . $dataKeyNum[$kitCd]);
                if (!empty($num)) {
                    $product['kitList'][$kitCd] = $num;
                    $product['subtotal']['num'] += $num;
                    $total['num'] += $num;
                    $product['pramaKit'][] = $kitCd . ':' . $num;
                }
            }
            // 金額取得
            if (!empty($product['pramaKit'])) {
                $r = $kitPrice->apiGet([
                    'kit' => implode(',', $product['pramaKit'])
                ]);
                if ($r->isSuccess()) {
                    $price = $r->results[0]['total_price'] * 1;
                    $product['subtotal']['price'] = $price;
                    $total['price'] += $price;
                }
            }
        }
        $this->set('productKitList', $productKitList);
        $this->set('total', $total);

        // 仮登録ユーザーの場合 or カード登録なし本登録(個人、法人)
        if ($this->Customer->isEntry() || $this->Customer->isCustomerCreditCardUnregist() || $this->Customer->isCorprateCreditCardUnregist()) {
            if ($model->validates(['fieldList' => ['mono_num', 'hako_num', 'cleaning_num']])) {
                CakeSession::write(self::MODEL_NAME, $model->data[$paymentModelName]);
                return $this->render('confirm');
            } else {
                $this->set('validErrors', $model->validationErrors);
                return $this->render('add');
            }
        }
        // 本登録ユーザーの場合
        $address_id = $this->request->data[self::MODEL_NAME]['address_id'];
        $address = $this->Address->find($address_id);

        // お届け先情報
        $model = $this->Order->setAddress($model->data[$paymentModelName], $address);
        $model->data[$paymentModelName]['kit'] = implode(Hash::extract($productKitList, '{n}.pramaKit.{n}'), ',');

        if ($model->validates()) {
            if ($this->Customer->isPrivateCustomer() || empty($this->Customer->getCorporatePayment())) {
                // カード
                $default_payment = $this->Customer->getDefaultCard();
                $this->set('default_payment_text', "{$default_payment['card_no']}　{$default_payment['holder_name']}");
            }
            // お届け先
            $this->set('address_text', "〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}");
            // お届け希望日時
            $datetime = $this->getDatetime($address_id, $this->request->data[self::MODEL_NAME]['datetime_cd']);
            $this->set('datetime', $datetime['text']);

            $model->data[$paymentModelName]['view_data_productKitList'] = serialize($productKitList);
            $model->data[$paymentModelName]['view_data_total'] = serialize($total);
            CakeSession::write(self::MODEL_NAME, $model->data[$paymentModelName]);
        } else {
            $this->set('validErrors', $model->validationErrors);

            // 配送日時
            $res_datetime = [];
            if (!empty($address_id)) {
                $res_datetime = $this->getDatetimeDeliveryKit($address_id);
            }
            $this->set('datetime', $res_datetime);

            return $this->render('add');
        }
    }

    /**
     *
     */
    public function complete()
    {
        if($this->Customer->getInfo()['oem_cd'] === OEM_CD_LIST['sneakers']) {
            return $this->setAction('complete_sneakers');
        }

        $data = CakeSession::read(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME);
        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }
        $model = $this->Order->model($data);

        if ($model->validates()) {
            // api
            $res = $model->apiPost($model->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'add']);
            }

            $address_id = $data['address_id'];
            $address = $this->Address->find($address_id);
            $this->set('data', $data);

            if ($this->Customer->isPrivateCustomer() || empty($this->Customer->getCorporatePayment())) {
                // カード
                $default_payment = $this->Customer->getDefaultCard();
                $this->set('default_payment_text', "{$default_payment['card_no']}　{$default_payment['holder_name']}");
            }
            // お届け先
            $this->set('address_text', "〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}");
            // お届け希望日時
            $datetime = $this->getDatetime($address_id, $data['datetime_cd']);
            $this->set('datetime', $datetime['text']);

            // 料金
            $this->set('productKitList', unserialize($data['view_data_productKitList']));
            $this->set('total', unserialize($data['view_data_total']));
        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }
    }

    public function add_sneakers()
    {
        $isBack = Hash::get($this->request->query, 'back');
        $res_datetime = [];
        $data = CakeSession::read(self::MODEL_NAME);
        CakeLog::write(DEBUG_LOG, __METHOD__.'('.__LINE__.')'.print_r($data, true));
        if ($isBack && !empty($data)) {
            if (!array_key_exists('address_id', $data)) {
                $data['address_id'] = '';
            }
            // 前回追加選択は最後のお届け先を選択
            if ($data['address_id'] === AddressComponent::CREATE_NEW_ADDRESS_ID) {
                $data['address_id'] = Hash::get($this->Address->last(), 'address_id', '');
                $data['datetime_cd'] = '';
            }
            $this->request->data[self::MODEL_NAME] = $data;
            if (!empty($data['address_id'])) {
                $res_datetime = $this->getDatetimeDeliveryKit($data['address_id']);
            }
        }

        $this->set('datetime', $res_datetime);

        $this->render('add_sneakers');

        CakeSession::delete(self::MODEL_NAME);
    }

    public function confirm_sneakers()
    {

        $data = Hash::get($this->request->data, self::MODEL_NAME);
        if (empty($data)) {
            return $this->render('add');
        }
        $model = $this->Order->model($data);
        $paymentModelName = $model->getModelName();

        // 届け先追加を選択の場合は追加画面へ遷移
        if (Hash::get($model->toArray(), 'address_id') === AddressComponent::CREATE_NEW_ADDRESS_ID) {
            CakeSession::write(self::MODEL_NAME, $model->toArray());
            return $this->redirect([
                'controller' => 'address', 'action' => 'add', 'customer' => true,
                '?' => ['return' => 'order']
            ]);
        }

        // キットPOSTデータキー
        $dataKeyNum = [
            KIT_CD_SNEAKERS => 'sneakers_num',
        ];

        // 料金（サービス（商品）ごと）集計
        $kitPrice = new CustomerKitPrice();
        $total = ['num' => 0, 'price' => 0];
        $productKitList = [
            PRODUCT_CD_SNEAKERS => [
                'kitList' => [KIT_CD_SNEAKERS => 0],
                'subtotal' => ['num' => 0, 'price' => 0]
            ],
        ];
        foreach ($productKitList as $productCd => &$product) {
            $product['pramaKit'] = [];

            // 個数集計
            foreach ($product['kitList'] as $kitCd => $d) {
                $num = Hash::get($this->request->data, self::MODEL_NAME . '.' . $dataKeyNum[$kitCd]);
                if (!empty($num)) {
                    $product['kitList'][$kitCd] = $num;
                    $product['subtotal']['num'] += $num;
                    $total['num'] += $num;
                    $product['pramaKit'][] = $kitCd . ':' . $num;
                }
            }
            // 金額取得
            if (!empty($product['pramaKit'])) {

                $r = $kitPrice->apiGet([
                    'kit' => implode(',', $product['pramaKit'])
                ]);
                if ($r->isSuccess()) {
                    $price = $r->results[0]['total_price'] * 1;
                    $product['subtotal']['price'] = $price;
                    $total['price'] += $price;
                }
            }
        }

        $this->set('productKitList', $productKitList);
        $this->set('total', $total);

        // 仮登録ユーザーの場合 or カード登録なし本登録(個人)
        if ($this->Customer->isEntry() || $this->Customer->isCustomerCreditCardUnregist()) {
            if ($model->validates(['fieldList' => ['mono_num', 'hako_num', 'cleaning_num']])) {
                CakeSession::write(self::MODEL_NAME, $model->data[$paymentModelName]);
                return $this->render('confirm');
            } else {
                $this->set('validErrors', $model->validationErrors);
                return $this->render('add');
            }
        }
        // 本登録ユーザーの場合
        $address_id = $this->request->data[self::MODEL_NAME]['address_id'];
        $address = $this->Address->find($address_id);

        // お届け先情報
        $model = $this->Order->setAddress($model->data[$paymentModelName], $address);
        $model->data[$paymentModelName]['kit'] = implode(Hash::extract($productKitList, '{n}.pramaKit.{n}'), ',');

        if ($model->validates()) {
            if ($this->Customer->isPrivateCustomer() || empty($this->Customer->getCorporatePayment())) {
                // カード
                $default_payment = $this->Customer->getDefaultCard();
                $this->set('default_payment_text', "{$default_payment['card_no']}　{$default_payment['holder_name']}");
            }
            // お届け先
            $this->set('address_text', "〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}");
            // お届け希望日時
            $datetime = $this->getDatetime($address_id, $this->request->data[self::MODEL_NAME]['datetime_cd']);
            $this->set('datetime', $datetime['text']);

            $model->data[$paymentModelName]['view_data_productKitList'] = serialize($productKitList);
            $model->data[$paymentModelName]['view_data_total'] = serialize($total);
            CakeSession::write(self::MODEL_NAME, $model->data[$paymentModelName]);
        } else {
            $this->set('validErrors', $model->validationErrors);

            // 配送日時
            $res_datetime = [];
            if (!empty($address_id)) {
                $res_datetime = $this->getDatetimeDeliveryKit($address_id);
            }
            $this->set('datetime', $res_datetime);

            return $this->render('add_sneakers');
        }

        return $this->render('confirm_sneakers');
    }

    /**
     *
     */
    public function complete_sneakers()
    {
        $data = CakeSession::read(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME);
        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }
        $model = $this->Order->model($data);

        if ($model->validates()) {
            // api
            $res = $model->apiPost($model->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'add']);
            }

            $address_id = $data['address_id'];
            $address = $this->Address->find($address_id);
            $this->set('data', $data);

            if ($this->Customer->isPrivateCustomer() || empty($this->Customer->getCorporatePayment())) {
                // カード
                $default_payment = $this->Customer->getDefaultCard();
                $this->set('default_payment_text', "{$default_payment['card_no']}　{$default_payment['holder_name']}");
            }
            // お届け先
            $this->set('address_text', "〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}");
            // お届け希望日時
            $datetime = $this->getDatetime($address_id, $data['datetime_cd']);
            $this->set('datetime', $datetime['text']);

            // 料金
            $this->set('productKitList', unserialize($data['view_data_productKitList']));
            $this->set('total', unserialize($data['view_data_total']));
        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }
    }

    /**
     * 注文不可ユーザ用表示メソッド
     */
    public function cannot()
    {
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

        $address_id = $this->request->data['address_id'];
        $result = $this->getDatetimeDeliveryKit($address_id);
        $status = !empty($result);

        return json_encode(compact('status', 'result'));
    }

    private function getDatetimeDeliveryKit($address_id)
    {
        $address = $this->Address->find($address_id);
        if (!empty($address) && !empty($address['postal'])) {
            $res = $this->DatetimeDeliveryKit->apiGet([
                'postal' => $address['postal'],
            ]);
            if ($res->isSuccess()) {
                return $res->results;
            }
        }
        return [];
    }

    private function getDatetime($address_id, $datetime_cd)
    {
        $data = [];
        $result = $this->getDatetimeDeliveryKit($address_id);
        foreach ($result as $datetime) {
            if ($datetime['datetime_cd'] === $datetime_cd) {
                $data = $datetime;
            }
        }
        return $data;
    }

}
