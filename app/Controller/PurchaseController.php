<?php

App::uses('MinikuraController', 'Controller');
App::uses('ApiCachedModel', 'Model');
App::uses('OutboundList', 'Model');
App::uses('CustomerEnvAuthed', 'Model');

class PurchaseController extends MinikuraController
{
    const MODEL_NAME = 'PaymentGMOPurchase';
    const MODEL_NAME_DATETIME = 'DatetimeDeliveryOutbound';
    const MODEL_NAME_ENTRY = 'CustomerEntry';
    const MODEL_NAME_SALES = 'Sales';

    public function beforeFilter ()
    {
        // index のみログイン不要なページ
        if ($this->action === 'index' || $this->action === 'register') {
            $this->checkLogined = false;
        }

        parent::beforeFilter();

        if ($this->action !== 'index' && $this->action !== 'register') {
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
        $this->loadModel(self::MODEL_NAME_SALES);

    }

    public function index()
    {
        $sales_id = $this->params['id'];
        $this->set('sales_id', $sales_id);

        $sale = $this->Sales->apiGetSale(['sales_id' => $sales_id]);
        $this->set('sales', $sale[0]);

        // 登録系フローからの戻り時
        if ($this->request->is('get')) {
            $data = CakeSession::read('PurchaseRegister');
            if (!empty($data) && !empty($data[self::MODEL_NAME_ENTRY])) {
                $this->request->data[self::MODEL_NAME_ENTRY] = $data[self::MODEL_NAME_ENTRY];
            }
        }

        // ログイン
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
        $result = $this->getDatetimeDeliveryOutbound($address_id);
        $status = !empty($result);

        return json_encode(compact('status', 'result'));
    }

    private function getDatetimeDeliveryOutbound($address_id)
    {
        $address = $this->Address->find($address_id);
        if (!empty($address) && !empty($address['postal'])) {
            $res = $this->DatetimeDeliveryOutbound->apiGet([
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
        $result = $this->getDatetimeDeliveryOutbound($address_id);
        foreach ($result as $datetime) {
            if ($datetime['datetime_cd'] === $datetime_cd) {
                $data = $datetime;
            }
        }
        return $data;
    }

    public function input()
    {
        $sales_id = $this->params['id'];
        $this->set('sales_id', $sales_id);

        $sale = $this->Sales->apiGetSale(['sales_id' => $sales_id]);
        $this->set('sales', $sale[0]);

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
                $res_datetime = $this->getDatetimeDeliveryOutbound($data['address_id']);
            }
        }

        $this->set('datetime', $res_datetime);

        CakeSession::delete(self::MODEL_NAME);
    }

    public function confirm()
    {
        $sales_id = $this->params['id'];
        $this->set('sales_id', $sales_id);

        $sale = $this->Sales->apiGetSale(['sales_id' => $sales_id]);
        $this->set('sales', $sale[0]);

        $data = Hash::get($this->request->data, self::MODEL_NAME);
        if (empty($data)) {
            $this->set('datetime', []);
            return $this->render('input');
        }

        // 販売id
        $data['sales_id'] = $sales_id;

        // 配送先
        $address_id = $data['address_id'];
        $address = $this->Address->find($address_id);
        $data = $this->PaymentGMOPurchase->setAddress($data, $address);

        // credit
        $credit = $this->Customer->getDefaultCard();
        $data['card_seq'] = $credit['card_seq'];

        // model data
        $this->PaymentGMOPurchase->data[self::MODEL_NAME] = $data;

        // 届け先追加を選択の場合は追加画面へ遷移
        if (Hash::get($data, 'address_id') === AddressComponent::CREATE_NEW_ADDRESS_ID) {
            CakeSession::write(self::MODEL_NAME, $data);
            return $this->redirect([
                'controller' => 'address', 'action' => 'add', 'customer' => true,
                '?' => ['return' => 'purchase']
            ]);
        }

        if ($this->PaymentGMOPurchase->validates()) {

            // お届け先表示
            $this->set('address', $address);
            // お届け希望日時表示
            $datetime = $this->getDatetime($address_id, $this->request->data[self::MODEL_NAME]['datetime_cd']);
            $this->set('datetime', $datetime['text']);

            CakeSession::write(self::MODEL_NAME, $this->PaymentGMOPurchase->data[self::MODEL_NAME]);

        } else {
            // 配送日時
            $res_datetime = [];
            if (!empty($address_id)) {
                $res_datetime = $this->getDatetimeDeliveryOutbound($address_id);
            }
            $this->set('datetime', $res_datetime);

            return $this->render('input');
        }
    }

    public function complete()
    {
        $sales_id = $this->params['id'];
        $this->set('sales_id', $sales_id);

        $sale = $this->Sales->apiGetSale(['sales_id' => $sales_id]);
        $this->set('sales', $sale[0]);

        $data = CakeSession::read(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME);
        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'input', 'id' => $sales_id]);
        }

        // model data
        $this->PaymentGMOPurchase->data[self::MODEL_NAME] = $data;

        if ($this->PaymentGMOPurchase->validates()) {
            // api
            $res = $this->PaymentGMOPurchase->apiPost($this->PaymentGMOPurchase->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'input', 'id' => $sales_id]);
            }

            // 配送先
            $address_id = $data['address_id'];
            $address = $this->Address->find($address_id);
            $this->set('address', $address);

            // お届け希望日時
            $datetime = $this->getDatetime($address_id, $data['datetime_cd']);
            $this->set('datetime', $datetime['text']);

        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'input', 'id' => $sales_id]);
        }
    }

    public function register()
    {
        $sales_id = $this->params['id'];

        if (!$this->request->is('post')) {
            return $this->redirect('/purchase/'. $sales_id);
        }

        $this->loadModel(self::MODEL_NAME_ENTRY);
        $this->CustomerEntry->set($this->request->data[self::MODEL_NAME_ENTRY]);

        if ($this->CustomerEntry->validates()) {
            CakeSession::write('PurchaseRegister.CustomerEntry', $this->request->data['CustomerEntry']);
            return $this->redirect(['controller' => 'PurchaseRegister', 'action' => 'address']);
        } else {
            return $this->render('index');
        }
    }
}
