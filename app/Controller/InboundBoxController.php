<?php

App::uses('AppController', 'Controller');

class InboundBoxController extends AppController
{
    const MODEL_NAME = 'Inbound';
    const MODEL_NAME_PRIVATE = 'InboundPrivate';
    const MODEL_NAME_MANUAL = 'InboundManual';

    public $address = null;

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();

        $this->loadModel('InboundPrivate');
        $this->loadModel('CustomerAddress');
        $this->loadModel('DatePickup');
        $this->loadModel('TimePickup');

        // $this->set('modelName', 'InboundPrivate');

        $list = $this->InfoBox->getListForInbound();
        $this->set('boxList', $list);

        // 配送先
        // TODO 契約情報も取得する
        $addressList = $this->CustomerAddress->apiGetResults();
        $this->address = $addressList;
        $this->set('address', $addressList);

        // TODO: 都度取得？
        $datePickupList = $this->DatePickup->apiGetResults();
        $this->set('dateList', $datePickupList);
        $timePickupList = $this->TimePickup->apiGetResults();
        $this->set('timeList', $timePickupList);
    }

    private function getAddress($address_id)
    {
        foreach ($this->address as $address) {
            if ($address['address_id'] === $address_id) {
                return $address;
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

        // box_listをチェック

        // TODO: 配送方法で切り替え

        $model = $this->getInbondModel($data[$this::MODEL_NAME]);

        // その他の値チェック

        if ($model->validates()) {
            CakeSession::write($this::MODEL_NAME, $this->request->data);
        } else {
            return $this->render('add');
        }
    }

    private function getInbondModel($data)
    {
        $model = new InboundPrivate();
        $model->set([$model->model_name => $data]);
        return $model;
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

        $datav = $data;
        unset($datav[$this::MODEL_NAME]['box_list']);
        $model = $this->getInbondModel($datav[$this::MODEL_NAME]);
        if ($model->validates()) {
            // api
            $inbound = $this->createInboundData($data[$this::MODEL_NAME]);
            $model->apiPostResults($inbound);
        } else {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'add']);
        }
    }

    private function createInboundData($data)
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

         // TODO: パターンコード？
        return $data;
    }

    private function createInboundBoxParam($item)
    {
        // TODO: product_cdはここで作成
        $productCd = $this->getDefualt($item, 'product_cd');
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
