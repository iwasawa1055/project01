<?php

App::uses('AppController', 'Controller');

class OrderController extends AppController
{
    const MODEL_NAME = 'PaymentGMOKitCard';
    const MODEL_NAME_CARD = 'PaymentGMOCard';
    const MODEL_NAME_ADDRESS = 'CustomerAddress';
    const MODEL_NAME_DATETIME = 'DatetimeDeliveryKit';

    public $default_payment = null;
    public $address = null;
    public $datetime = null;

    public $components = ['Address'];

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

        // 仮登録か
        $this->set('isEntry', $this->customer->isEntry());
        if (!$this->customer->isEntry()) {
            // クレジットカード
            $this->default_payment = $this->PaymentGMOCard->apiGetDefaultCard();
            $this->set('default_payment', $this->default_payment);
            // 配送先
            $this->set('address', $this->Address->get());
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
            $this->request->data = CakeSession::read($this::MODEL_NAME);
            if (!$this->customer->isEntry()) {
                $res_datetime = $this->getDatetimeDeliveryKit($this->request->data['PaymentGMOKitCard']['address_id']);
                $this->set('datetime', $res_datetime);
            }
        } else {
            $this->set('datetime', []);
        }
        CakeSession::delete($this::MODEL_NAME);
    }

    /**
     *
     */
    public function confirm()
    {
        $this->PaymentGMOKitCard->set($this->request->data);

        // 仮登録ユーザーの場合
        if ($this->customer->isEntry()) {
            if ($this->PaymentGMOKitCard->validates(['fieldList' => ['mono_num', 'hako_num', 'cleaning_num']])) {
                CakeSession::write($this::MODEL_NAME, $this->PaymentGMOKitCard->data);
                return $this->render('confirm');
            } else {
                return $this->render('add');
            }
        }

        // 本登録ユーザーの場合
        // address_id
        $address_id = $this->request->data['PaymentGMOKitCard']['address_id'];
        $address = $this->Address->find($address_id);

        // お届け先情報
        $this->PaymentGMOKitCard->data['PaymentGMOKitCard']['name'] = "{$address['lastname']}　{$address['firstname']}";
        $this->PaymentGMOKitCard->data['PaymentGMOKitCard']['tel1'] = $address['tel1'];
        $this->PaymentGMOKitCard->data['PaymentGMOKitCard']['postal'] = $address['postal'];
        $this->PaymentGMOKitCard->data['PaymentGMOKitCard']['address'] = "{$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}";

        // キット
        $kit = '';
        $mono_num = $this->request->data['PaymentGMOKitCard']['mono_num'];
        $kit .= empty($mono_num) ? '' : '66:' . $mono_num;
        $hako_num = $this->request->data['PaymentGMOKitCard']['hako_num'];
        $kit .= empty($hako_num) ? '' : '64:' . $hako_num;
        $cleaning_num = $this->request->data['PaymentGMOKitCard']['cleaning_num'];
        $kit .= empty($cleaning_num) ? '' : '75:' . $cleaning_num;

        $this->PaymentGMOKitCard->data['PaymentGMOKitCard']['kit'] = rtrim($kit, ',');

        if ($this->PaymentGMOKitCard->validates()) {
            // カード
            $this->set('default_payment_text', "{$this->default_payment['card_no']}　{$this->default_payment['holder_name']}");
            // お届け先
            $this->set('address_text', "〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}");
            // お届け希望日時
            $datetime = $this->getDatetime($address_id, $this->request->data['PaymentGMOKitCard']['datetime_cd']);
            $this->set('datetime', $datetime['text']);

            CakeSession::write($this::MODEL_NAME, $this->PaymentGMOKitCard->data);
        } else {
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
            $res = $this->PaymentGMOKitCard->apiPost($this->PaymentGMOKitCard->toArray());
            if (!$res->isSuccess()) {
                // TODO:
                $this->Session->setFlash('try again');
                return $this->redirect(['action' => 'add']);
            }

            $address_id = $data['PaymentGMOKitCard']['address_id'];
            $address = $this->Address->find($address_id);
            $this->set('data', $data);

            // カード
            $this->set('default_payment_text', "{$this->default_payment['card_no']}　{$this->default_payment['holder_name']}");
            // お届け先
            $this->set('address_text', "〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}");
            // お届け希望日時
            $datetime = $this->getDatetime($address_id, $data['PaymentGMOKitCard']['datetime_cd']);
            $this->set('datetime', $datetime['text']);

        } else {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'add']);
        }
    }
}
