<?php

App::uses('AppController', 'Controller');
App::uses('CustomerKitPrice', 'Model');

class OrderController extends AppController
{
    const MODEL_NAME = 'OrderKit';
    const MODEL_NAME_CARD = 'PaymentGMOCard';
    const MODEL_NAME_DATETIME = 'DatetimeDeliveryKit';

    public $default_payment = null;

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
        if (!$this->customer->isEntry()) {
            // 配送先
            $this->set('address', $this->Address->get($this->customer->isPrivateCustomer()));

        }
        $this->set('default_payment', $this->customer->getDefaultCard());
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
        $res_datetime = [];
        if ($isBack) {
            $data = CakeSession::read(self::MODEL_NAME);
            if (!array_key_exists('address_id', $data)){
                $data['address_id'] = '';
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
        $model = $this->Order->model($this->request->data[self::MODEL_NAME]);
        $paymentModelName = $model->getModelName();

        // キット
        $dataKeyNum = [
            KIT_CD_MONO => 'mono_num',
            KIT_CD_HAKO => 'hako_num',
            KIT_CD_CLEANING_PACK => 'cleaning_num',
        ];

        $kitList = [];
        foreach ($dataKeyNum as $kitCd => $dataKey) {
            $kitList[$kitCd] = ['num' => 0];
            $num = Hash::get($this->request->data, self::MODEL_NAME . '.' . $dataKey);
            if (!empty($num)) {
                $kitList[$kitCd]['num'] = $num;
                $kitList[$kitCd]['kit'] = $kitCd . ':' . $num;
            }
        }
        // 料金
        $kitPrice = new CustomerKitPrice();
        $total = ['num' => 0, 'price' => 0];
        foreach ($dataKeyNum as $kitCd => $dataKey) {
            $kitList[$kitCd]['price'] = 0;
            if (!empty($kitList[$kitCd]['kit'])) {
                $r = $kitPrice->apiGet([
                    'kit' => $kitList[$kitCd]['kit']
                ]);
                if ($r->isSuccess()) {
                    $kitList[$kitCd]['price'] = $r->results[0]['total_price'] * 1;
                    $total['num'] += $kitList[$kitCd]['num'] ;
                    $total['price'] += $kitList[$kitCd]['price'];
                }
            }
        }
        $this->set('kitList', $kitList);
        $this->set('total', $total);

        // 仮登録ユーザーの場合
        if ($this->customer->isEntry()) {
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

        $model->data[$paymentModelName]['kit'] = implode(Hash::extract($kitList, '{n}.kit'), ',');

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

            $model->data[$paymentModelName]['view_data_kitList'] = serialize($kitList);
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
        $data = CakeSession::read(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME);
        if (empty($data)) {
            // TODO:
            $this->Flash->set('try again');
            return $this->redirect(['action' => 'add']);
        }
        $model = $this->Order->model($data);
        if ($model->validates()) {
            // api
            $res = $model->apiPost($model->toArray());
            if (!$res->isSuccess()) {
                // TODO:
                $this->Flash->set('try again');
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

            // 料金
            $this->set('kitList', unserialize($data['view_data_kitList']));
            $this->set('total', unserialize($data['view_data_total']));

        } else {
            // TODO:
            $this->Flash->set('try again');
            return $this->redirect(['action' => 'add']);
        }
    }
}
