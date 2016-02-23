<?php

App::uses('AppController', 'Controller');

class OrderController extends AppController
{
    // const MODEL_NAME = 'PaymentGMOKitCard';
    // const MODEL_NAME_ACCOUNT = 'PaymentAccountTransferKit';
    const MODEL_NAME = 'OrderKit';
    const MODEL_NAME_CARD = 'PaymentGMOCard';
    const MODEL_NAME_DATETIME = 'DatetimeDeliveryKit';

    public $default_payment = null;
    // public $address = null;
    // public $datetime = null;

    public $components = ['Address', 'Order'];

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        // $this->loadModel(self::MODEL_NAME);
        $this->Order->init($this->customer->token['division']);
        $this->loadModel(self::MODEL_NAME_CARD);
        $this->loadModel(self::MODEL_NAME_DATETIME);
        $this->set('validErrors', []);

        // 仮登録か
        $this->set('isEntry', $this->customer->isEntry());
        if (!$this->customer->isEntry()) {
            if ($this->customer->isPrivateCustomer() || empty($this->customer->getCorporatePayment())) {
                // クレジットカード
                $this->default_payment = $this->PaymentGMOCard->apiGetDefaultCard();
                $this->set('default_payment', $this->default_payment);
            }
            // 配送先
            $this->set('address', $this->Address->get($this->customer->isPrivateCustomer()));
        }
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

    private function getDatetimeDeliveryKit($address_id) {
        $address = $this->Address->find($address_id);
        $data = $this->DatetimeDeliveryKit->apiGet([
          'postal' => $address['postal'],
        ]);
        return $data->results;
    }

    private function getDatetime($address_id, $datetime_cd) {
        $result = $this->getDatetimeDeliveryKit($address_id);
        foreach ($result as $datetime) {
            if ($datetime['datetime_cd'] === $datetime_cd) {
                return $datetime;
            }
        }
    }

    /**
     *
     */
    public function add()
    {
        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = CakeSession::read(self::MODEL_NAME);
            if (!$this->customer->isEntry()) {
                $res_datetime = $this->getDatetimeDeliveryKit($this->request->data[self::MODEL_NAME]['address_id']);
                $this->set('datetime', $res_datetime);
            }
        } else {
            $this->set('datetime', []);
        }
        CakeSession::delete(self::MODEL_NAME);
    }

    /**
     *
     */
    public function confirm()
    {
        // $this->PaymentGMOKitCard->set($this->request->data);
        $model = $this->Order->model($this->request->data[self::MODEL_NAME]);
        $paymentModelName = $model->getModelName();

        // 仮登録ユーザーの場合
        if ($this->customer->isEntry()) {
            if ($model->validates(['fieldList' => ['mono_num', 'hako_num', 'cleaning_num']])) {
                CakeSession::write(self::MODEL_NAME, $model->data);
                return $this->render('confirm');
            } else {
                return $this->render('add');
            }
        }

        // 本登録ユーザーの場合
        // address_id
        $address_id = $this->request->data[self::MODEL_NAME]['address_id'];
        $address = $this->Address->find($address_id);

        // // お届け先情報
        // $model->data[self::MODEL_NAME]['name'] = "{$address['lastname']}　{$address['firstname']}";
        // $model->data[self::MODEL_NAME]['tel1'] = $address['tel1'];
        // $model->data[self::MODEL_NAME]['postal'] = $address['postal'];
        // $model->data[self::MODEL_NAME]['address'] = "{$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}";
        $model = $this->Order->setAddress($model->data[$paymentModelName], $address);

        // キット
        $kit = '';
        $mono_num = $this->request->data[self::MODEL_NAME]['mono_num'];
        $kit .= empty($mono_num) ? '' : '66:' . $mono_num;
        $hako_num = $this->request->data[self::MODEL_NAME]['hako_num'];
        $kit .= empty($hako_num) ? '' : '64:' . $hako_num;
        $cleaning_num = $this->request->data[self::MODEL_NAME]['cleaning_num'];
        $kit .= empty($cleaning_num) ? '' : '75:' . $cleaning_num;

        // $model->data[self::MODEL_NAME]['kit'] = rtrim($kit, ',');
        $model->data[$paymentModelName]['kit'] = rtrim($kit, ',');

        if ($model->validates()) {
            if ($this->customer->isPrivateCustomer() || empty($this->customer->getCorporatePayment())) {
                // カード
                $this->set('default_payment_text', "{$this->default_payment['card_no']}　{$this->default_payment['holder_name']}");
            }
            // お届け先
            $this->set('address_text', "〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}");
            // お届け希望日時
            $datetime = $this->getDatetime($address_id, $this->request->data[self::MODEL_NAME]['datetime_cd']);
            $this->set('datetime', $datetime['text']);

            // CakeSession::write(self::MODEL_NAME, $model->data);
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
        $data = CakeSession::read(self::MODEL_NAME);
        // CakeSession::delete(self::MODEL_NAME);
        if (empty($data)) {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'add']);
        }
        // $this->PaymentGMOKitCard->set($data);
        $model = $this->Order->model($data);
        if ($model->validates()) {
            // api
            $res = $model->apiPost($model->toArray());
            if (!$res->isSuccess()) {
                // TODO:
                $this->Session->setFlash('try again');
                return $this->redirect(['action' => 'add']);
            }

            $address_id = $data['address_id'];
            $address = $this->Address->find($address_id);
            $this->set('data', $data);

            if ($this->customer->isPrivateCustomer() || empty($this->customer->getCorporatePayment())) {
                // カード
                $this->set('default_payment_text', "{$this->default_payment['card_no']}　{$this->default_payment['holder_name']}");
            }
            // お届け先
            $this->set('address_text', "〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}");
            // お届け希望日時
            $datetime = $this->getDatetime($address_id, $data['datetime_cd']);
            $this->set('datetime', $datetime['text']);

        } else {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'add']);
        }
    }
}
