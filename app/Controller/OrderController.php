<?php

App::uses('AppController', 'Controller');

class OrderController extends AppController
{
    const MODEL_NAME = 'PaymentGMOKitCard';
    const MODEL_NAME_CARD = 'PaymentGMOCard';
    const MODEL_NAME_ADDRESS = 'CustomerAddress';
    const MODEL_NAME_DATETIME = 'DatetimeDeliveryKit';

    public $payment = null;
    public $address = null;
    public $datetime = null;

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel($this::MODEL_NAME);
        $this->loadModel($this::MODEL_NAME_CARD);
        $this->loadModel($this::MODEL_NAME_ADDRESS);
        $this->loadModel($this::MODEL_NAME_DATETIME);

        // クレジットカード
        $this->default_payment = $this->PaymentGMOCard->apiGetDefaultCard();
        $this->set('default_payment', $this->default_payment);

        // 配送先
        // TODO 契約情報も取得する
        $res_address = $this->CustomerAddress->apiGet();
        $this->address = $res_address->results;
        $this->set('address', $this->address);

        // // 配送日時
        // $res_datetime = $this->getDatetimeDeliveryKit($this->address[0]['address_id']);
        // $this->set('datetime', $res_datetime);
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
        $address = $this->getAddress($address_id);
        $data = $this->DatetimeDeliveryKit->apiGet([
          'postal' => $address['postal'],
        ]);
        return $data->results;
    }

    private function getAddress($address_id) {
        foreach ($this->address as $address) {
            if ($address['address_id'] === $address_id) {
                return $address;
            }
        }
    }

    private function getDatetime($datetime_cd) {
        foreach ($this->datetime as $datetime) {
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
        // 配送日時
        // $res_datetime = $this->DatetimeDeliveryKit->apiGet([
        //     'postal' => $this->address[0]['postal'],
        // ]);
        // $this->datetime = $res_datetime->results;
        // $this->set('datetime', $this->datetime);
        $res_datetime = $this->getDatetimeDeliveryKit($this->address[0]['address_id']);
        $this->set('datetime', $res_datetime);

        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = CakeSession::read($this::MODEL_NAME);
        }
        CakeSession::delete($this::MODEL_NAME);
    }

    /**
     *
     */
    public function confirm()
    {
        $this->PaymentGMOKitCard->set($this->request->data);

        // カード
        $this->set('default_payment', "{$this->default_payment['card_no']}　{$this->default_payment['holder_name']}");

        // お届け先
        $address = $this->getAddress($this->request->data['PaymentGMOKitCard']['address_id']);
        $this->set('address', "〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}");

        // お届け希望日時
        $datetime = $this->getDatetime($this->request->data['PaymentGMOKitCard']['datetime_cd']);
        $this->set('datetime', $datetime['text']);

        // お届け先情報
        $this->PaymentGMOKitCard->data['PaymentGMOKitCard']['name'] = "{$address['lastname']}　{$address['firstname']}";
        $this->PaymentGMOKitCard->data['PaymentGMOKitCard']['tel1'] = $address['tel1'];
        $this->PaymentGMOKitCard->data['PaymentGMOKitCard']['postal'] = $address['postal'];
        $this->PaymentGMOKitCard->data['PaymentGMOKitCard']['address'] = "{$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}";

        // キット
        $this->PaymentGMOKitCard->data['PaymentGMOKitCard']['kit'] = "66:1";

        if ($this->PaymentGMOKitCard->validates()) {
            CakeSession::write($this::MODEL_NAME, $this->PaymentGMOKitCard->data);
        } else {
            return $this->render('add');
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

        $this->PaymentGMOKitCard->set($data);
        if ($this->PaymentGMOKitCard->validates()) {
            // api
            $res = $this->PaymentGMOKitCard->apiPost($this->PaymentGMOKitCard->data);
            if (!$res->isSuccess()) {
                // TODO:
                $this->Session->setFlash('try again');
                return $this->redirect(['action' => 'add']);
            }
            // // complete.ctp echo $email
            // $this->set('email', $this->PaymentGMOKitCard->toArray()['email']);
        } else {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'add']);
        }
    }
}
