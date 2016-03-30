<?php

App::uses('MinikuraController', 'Controller');
App::uses('DatePrivate', 'Model');
App::uses('TimePrivate', 'Model');
App::uses('InboundSelectedBox', 'Model');

class InboundBoxController extends MinikuraController
{
    const MODEL_NAME = 'Inbound';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->Inbound = $this->Components->load('Inbound');

        $list = $this->InfoBox->getListForInbound();
        $this->set('boxList', $list);

        $this->set('addressList', $this->Address->get());
        $this->set('dateList', []);
        $this->set('timeList', []);
    }

    /**
     * アクセス拒否
     */
    protected function isAccessDeny()
    {
        return !$this->Customer->canInbound();
    }

    /**
     *
     */
    public function getInboundDatetime()
    {
        $this->autoRender = false;
        if (!$this->request->is('ajax')) {
            return false;
        }
        $this->Inbound->init(Hash::get($this->request->data, self::MODEL_NAME));
        $result['date'] = $this->Inbound->date();
        $result['time'] = $this->Inbound->time();
        $status = !empty($result);
        return json_encode(compact('status', 'result'));
    }

    /**
     *
     */
    public function add()
    {
        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $data = CakeSession::read(self::MODEL_NAME . 'FORM');
            // 前回追加選択は最後のお届け先を選択
            if (Hash::get($data[self::MODEL_NAME], 'address_id') === AddressComponent::CREATE_NEW_ADDRESS_ID) {
                $data[self::MODEL_NAME]['address_id'] = Hash::get($this->Address->last(), 'address_id', '');
            }
            $this->request->data = $data;
            $this->Inbound->init(Hash::get($this->request->data, self::MODEL_NAME));
            $this->set('dateList', $this->Inbound->date());
            $this->set('timeList', $this->Inbound->time());
        }
        CakeSession::delete(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME . 'FORM');
    }

    /**
     *
     */
    public function confirm()
    {
        $data = Hash::get($this->request->data, self::MODEL_NAME);
        if (empty($data)) {
            return $this->render('add');
        }

        // 届け先追加を選択の場合は追加画面へ遷移
        if (Hash::get($data, 'address_id') === AddressComponent::CREATE_NEW_ADDRESS_ID) {
            CakeSession::write(self::MODEL_NAME . 'FORM', $this->request->data);
            return $this->redirect([
                'controller' => 'address', 'action' => 'add', 'customer' => true,
                '?' => ['return' => 'inboundbox']
            ]);
        }

        $dataBoxList = $data['box_list'];
        unset($data['box_list']);

        $validErrors = [];

        // 選択されたボックスを処理
        $selectedList = [];
        foreach ($dataBoxList as $item) {
            if (!empty($item['checkbox'])) {
                $boxModel = new InboundSelectedBox();
                $boxModel->set([$boxModel->getModelName() => $item]);
                if (!$boxModel->validates()) {
                    $validErrors['box_list'][$item['box_id']] = $boxModel->validationErrors;
                }
                $selectedList[] = InboundComponent::createBoxParam($item);
            }
        }
        // 選択なしはエラー表示
        if (empty($selectedList)) {
            $validErrors['Inbound']['box'] = __d('validation', 'select', __d('validation', 'box'));
        }
        $data['box'] = implode(',', $selectedList);

        // 預け入れ方法入力チェック
        if (empty($data['delivery_carrier'])) {
            $validErrors['Inbound']['delivery_carrier'] = __d('validation', 'notBlank', __d('validation', 'inbound_delivery_carrier'));
        } else {

            $this->Inbound->init($data);
            $this->set('dateList', $this->Inbound->date());
            $this->set('timeList', $this->Inbound->time());

            // モデル取得
            $data = $this->Address->merge($data['address_id'], $data);
            $model = $this->Inbound->model($data);
            if (empty($model)) {
                $this->Flash->set(__('empty_session_data'));
                return $this->redirect(['action' => 'add']);
            }

            if ($model->validates()) {
                CakeSession::write(self::MODEL_NAME, $model->data);
                CakeSession::write(self::MODEL_NAME . 'FORM', $this->request->data);
            } else {
                $validErrors['Inbound'] = $model->validationErrors;
            }
        }

        if (!empty($validErrors)) {
            $this->set('validErrors', $validErrors);
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
        CakeSession::delete(self::MODEL_NAME . 'FORM');

        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }

        $data = current($data);
        $this->Inbound->init($data);
        $model = $this->Inbound->model($data);
        if (!empty($model) && $model->validates()) {
            // api
            $res = $model->apiPost($model->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'add']);
            }
        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }
    }
}
