<?php

App::uses('AppController', 'Controller');
App::uses('InboundSet', 'Model');

class InboundBoxController extends AppController
{
    const MODEL_NAME = 'Inbound';

    public $addressList = null;

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();

        $this->loadModel('InboundPrivate');
        $this->loadModel('CustomerAddress');

        $list = $this->InfoBox->getListForInbound();
        $this->set('boxList', $list);

        // 配送先
        // TODO 契約情報も取得する

        $addressList = $this->CustomerAddress->apiGetResults();
        $this->addressList = $addressList;
        $this->set('address', $addressList);
        $this->set('dateList', []);
        $this->set('timeList', []);
    }

    private function getAddress($address_id)
    {
        foreach ($this->addressList as $address) {
            if ($address['address_id'] === $address_id) {
                return $address;
            }
        }
    }


    /**
     *
     */
    public function getInboundDate()
    {
        $this->autoRender = false;
        if (!$this->request->is('ajax')) {
            return false;
        }
        $set = $this->getInboundSet($this->request->data);
        $result = $set->getDate();
        $status = !empty($datetime);
        return json_encode(compact('status', 'result'));
    }
    public function getInboundTime()
    {
        $this->autoRender = false;
        if (!$this->request->is('ajax')) {
            return false;
        }
        $set = $this->getInboundSet($this->request->data);
        $result = $set->getTime();
        $status = !empty($datetime);
        return json_encode(compact('status', 'result'));
    }
    /**
     *
     */
    public function add()
    {
        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = CakeSession::read(self::MODEL_NAME);
            $set = $this->getInboundSet($this->request->data[self::MODEL_NAME]);
            $datePickupList = $set->getDate();
            $this->set('dateList', $datePickupList);
            $timePickupList = $set->getTime();
            $this->set('timeList', $timePickupList);
        }
        CakeSession::delete($this::MODEL_NAME);
    }

    /**
     *
     */
    public function confirm()
    {
        $data = $this->request->data;
        unset($data[$this::MODEL_NAME]['box_list']);

        // TODO: box_listをチェック

        $model = $this->getInboundModel($data[self::MODEL_NAME]);
        $set = $this->getInboundSet($data[self::MODEL_NAME]);
        $datePickupList = $set->getDate();
        $this->set('dateList', $datePickupList);
        $timePickupList = $set->getTime();
        $this->set('timeList', $timePickupList);


        if (empty($model)) {
            return $this->render('add');
        }
        if ($model->validates()) {
            CakeSession::write(self::MODEL_NAME, $this->request->data);
        } else {
            return $this->render('add');
        }
    }

    private function getInboundModel($data = [])
    {
        $set = $this->getInboundSet($data);
        $model = $set->getModel($data);
        if (!empty($model)) {
            $model->set([$model->getModelName() => $data]);
        }
        return $model;
    }
    private function getInboundSet($data = [])
    {
        $set = null;
        $data = $this->convertData($data);
        $carrierCd = $data['carrier_cd'];
        $deliveryType = $data['delivery_type'];
        return InboundSet::create($carrierCd, $deliveryType);
    }

    private function convertData($data = [])
    {
        // pr($data);
        $a = explode('_', $data['delivery_carrier']);
        $carrierCd = $a[0];
        $deliveryType = $a[1];
        $data['delivery_type'] = $deliveryType;
        $data['carrier_cd'] = $carrierCd;
        return $data;
    }

    /**
     *
     */
    public function complete()
    {
        $data = CakeSession::read($this::MODEL_NAME);
        // CakeSession::delete($this::MODEL_NAME);
        if (empty($data)) {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'add']);
        }

        $datav = $data;
        unset($datav[$this::MODEL_NAME]['box_list']);

        $model = $this->getInboundModel($datav[self::MODEL_NAME]);
        if (empty($model)) {
            return $this->render('add');
        }
        if ($model->validates()) {

            // api
            $inbound = $this->createInboundSet($data[self::MODEL_NAME]);
            // $model->apiPostResults($inbound);
            pr($inbound);

        } else {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'add']);
        }
    }

    private function createInboundSet($data)
    {
        // ボックス
        $box = '';
        foreach ($data['box_list'] as $item) {
            if (!empty($item['checkbox'])) {
                $box .= $this->createInboundBoxParam($item) . ',';
            }
        }
        $address_id = $data['address_id'];
        $address = $this->getAddress($address_id);
        $box = rtrim($box, ',');
        $data['box'] = $box;
        unset($data['box_list']);

        unset($data['address_id']);

         // お届け先情報
         $data['lastname'] = $address['lastname'];
        $data['lastname_kana'] = $address['lastname_kana'];
        $data['firstname'] = $address['firstname'];
        $data['firstname_kana'] = $address['firstname_kana'];
        $data['tel1'] = $address['tel1'];
        $data['postal'] = $address['postal'];
        $data['pref'] = $address['pref'];
        $data['address1'] = $address['address1'];
        $data['address2'] = $address['address2'];
        $data['address3'] = $address['address3'];

         // 入庫パターン
         // 配送業者コード
         $a = explode('_', $data['delivery_carrier']);
        $data['delivery_type'] = $a[0];
        $data['carrier_cd'] = $a[1];
        unset($data['delivery_carrier']);

        return $data;
    }

    private function createInboundBoxParam($item)
    {
        $kitCd = $this->getDefualt($item, 'kit_cd');
        $productCd = InfoBox::kitCd2ProductCd($kitCd);
        $boxId = $this->getDefualt($item, 'box_id');
        $title = $this->getDefualt($item, 'title');
        $option = $this->getDefualt($item, 'option');
        return "${productCd}:${boxId}:${title}:${option}";
    }

    private function getDefualt($a, $k, $d = '')
    {
        if (array_key_exists($k, $a)) {
            return $a[$k];
        }
        return $d;
    }
}
