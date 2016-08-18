<?php

App::uses('MinikuraController', 'Controller');
App::uses('ApiCachedModel', 'Model');
App::uses('OutboundList', 'Model');
App::uses('CustomerEnvAuthed', 'Model');

class PurchaseController extends MinikuraController
{
    const MODEL_NAME = 'Purchase';
    const MODEL_NAME_DATETIME = 'DatetimeDeliveryKit';

    public function beforeFilter ()
    {
        // index のみログイン不要なページ
        if ($this->action === 'index') {
            $this->checkLogined = false;
        }

        parent::beforeFilter();

        if ($this->action !== 'index') {
            $this->set('current_email', $this->Customer->getInfo()['email']);
            $this->set('address', $this->Address->get());
            $this->set('default_payment', $this->Customer->getDefaultCard());
        }

        // id
        $this->set('sales_id', $this->params['id']);

        // Layouts
        $this->layout = 'market';

        $this->loadModel(self::MODEL_NAME);
        $this->loadModel(self::MODEL_NAME_DATETIME);
    }

    public function index()
    {
        $sales_id = $this->params['id'];
        $this->set('sales_id', $sales_id);

        if ($this->request->is('post')) {
            // login
            $this->loadModel('CustomerLogin');
            $this->CustomerLogin->set($this->request->data);

            if ($this->CustomerLogin->validates()) {

                $res = $this->CustomerLogin->login();
                if (!empty($res->error_message)) {
                    // パスワード不正など
                    $this->request->data['CustomerLogin']['password'] = '';
                    $this->Flash->set($res->error_message);
                    return $this->render('index');
                }

                // セッション値をクリア
                ApiCachedModel::deleteAllCache();
                OutboundList::delete();
                CustomerData::delete();
                // セッションID変更
                CakeSession::renew();

                // カスタマー情報を取得しセッションに保存
                $this->Customer->setTokenAndSave($res->results[0]);
                $this->Customer->setPassword($this->request->data['CustomerLogin']['password']);
                $this->Customer->getInfo();

                // ユーザー環境値登録
                $this->Customer->postEnvAuthed();

                return $this->redirect(['controller' => 'purchase', 'action' => 'input', 'id' => $sales_id]);

            } else {
                $this->request->data['CustomerLogin']['password'] = '';
                return $this->render('index');
            }
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

    public function input()
    {
        $isBack = Hash::get($this->request->query, 'back');
        $res_datetime = [];
        $data = CakeSession::read(self::MODEL_NAME);
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

    public function confirm()
    {
        $sales_id = $this->params['id'];

        $data = Hash::get($this->request->data, self::MODEL_NAME);
        if (empty($data)) {
            return $this->render('input');
        }

        $data['sales_id'] = $sales_id;
        $this->Purchase->data[self::MODEL_NAME] = $data;

        // 配送先
        $address_id = $data['address_id'];
        $address = $this->Address->find($address_id);
        $data = $this->Purchase->setAddress($data, $address);

        // model data
        $this->Purchase->data[self::MODEL_NAME] = $data;

        // 届け先追加を選択の場合は追加画面へ遷移
        if (Hash::get($data, 'address_id') === AddressComponent::CREATE_NEW_ADDRESS_ID) {
            CakeSession::write(self::MODEL_NAME, $data);
            return $this->redirect([
                'controller' => 'address', 'action' => 'add', 'customer' => true,
                '?' => ['return' => 'purchase']
            ]);
        }

        if ($this->Purchase->validates()) {

            // お届け先表示
            $this->set('address', $address);
            // お届け希望日時表示
            $datetime = $this->getDatetime($address_id, $this->request->data[self::MODEL_NAME]['datetime_cd']);
            $this->set('datetime', $datetime['text']);

            CakeSession::write(self::MODEL_NAME, $this->Purchase->data[self::MODEL_NAME]);

        } else {
            // 配送日時
            $res_datetime = [];
            if (!empty($address_id)) {
                $res_datetime = $this->getDatetimeDeliveryKit($address_id);
            }
            $this->set('datetime', $res_datetime);

            return $this->render('input');
        }
    }

    public function complete()
    {
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);
        CakeLog::write(DEBUG_LOG, $id);
    }

}
