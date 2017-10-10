<?php

App::uses('MinikuraController', 'Controller');
App::uses('ApiCachedModel', 'Model');
App::uses('OutboundList', 'Model');
App::uses('CustomerEnvAuthed', 'Model');

class PurchaseEntryRegisterController extends MinikuraController
{
    // ログイン不要なページ
    protected $checkLogined = false;

    const MODEL_NAME = 'PaymentGMOPurchase';
    const MODEL_CUSTOMER_ENTRY = 'CustomerEntry';
    //const MODEL_CUSTOMER_INFO = 'CustomerRegistInfo';
    //* エントリーからの登録時は、CustomerInfo
    const MODEL_CUSTOMER_INFO = 'CustomerInfo';
    const MODEL_DATETIME_DELIVERY = 'DatetimeDeliveryOutboundV4';
    const MODEL_PAYMENT_CARD = 'PaymentGMOSecurityCard';
    const MODEL_NAME_SALES = 'Sales';
    const MODEL_CUSTOMER_LOGIN = 'CustomerLogin';

    public function beforeFilter ()
    {
        parent::beforeFilter();
        // Layouts
        $this->layout = 'trade';

        $this->loadModel(self::MODEL_NAME);
        $this->loadModel(self::MODEL_DATETIME_DELIVERY);
        $this->loadModel(self::MODEL_NAME_SALES);
        //* エントリーユーザー用 PurchaseEntryRegister 
        $data = CakeSession::read('PurchaseEntryRegister');
        $purchase = Hash::get($data, self::MODEL_NAME);
        if (empty($data) || empty($purchase['sales_id'])) {
            return $this->redirect('/');
        }

        $sale = $this->Sales->apiGetSale(['sales_id' => $purchase['sales_id']]);
        if (empty($sale)) {
            new AppTerminalCritical(__('access_deny'), 404);
            return;
        }

        if ($sale[0]['sales_status'] !== SALES_STATUS_ON_SALE) {
            return $this->redirect(Configure::read('site.trade.url') . $purchase['sales_id']);
        }

        $this->set('sales', $sale[0]);
    }

    public function address()
    {
        $data = CakeSession::read('PurchaseEntryRegister');

        $res_datetime = [];
        if ($this->request->is('get')) {
            // 登録系フローからの戻り時
            $data = CakeSession::read('PurchaseEntryRegister');
            if (!empty($data) && !empty($data[self::MODEL_CUSTOMER_INFO])) {
                $this->request->data[self::MODEL_CUSTOMER_INFO] = $data[self::MODEL_CUSTOMER_INFO];

                // datetime by postal
                if (!empty($data[self::MODEL_CUSTOMER_INFO]['postal'])) {
                    $res_datetime = $this->getDatetimeDeliveryOutbound($data[self::MODEL_CUSTOMER_INFO]['postal']);
                }
            }
        }

        $this->set('datetime', $res_datetime);

        if ($this->request->is('post')) {
            // ユーザー情報
            $customerInfo = $this->request->data[self::MODEL_CUSTOMER_INFO];
            //$customerEntry = $data[self::MODEL_CUSTOMER_ENTRY];
            //* エントリー用 
            $customerEntry = $data[self::MODEL_CUSTOMER_LOGIN];

            $customerInfo['birth'] = CUSTOMER_DEFAULT_BIRTH;
            $customerInfo['gender'] = CUSTOMER_DEFAULT_GENDER;
            $customerInfo['email'] = $customerEntry['email'];
            $customerInfo['password'] = $customerEntry['password'];
            //$customerInfo['password_confirm'] = $customerEntry['password_confirm'];
            //$customerInfo['newsletter'] = $customerEntry['newsletter'];
            //* エントリー用
            $customerInfo['password_confirm'] = $customerEntry['password'];
            $customerInfo['newsletter'] = $this->Customer->getInfo()['newsletter'];

            $this->loadModel(self::MODEL_CUSTOMER_INFO);
            $this->CustomerInfo->set($customerInfo);

            $this->CustomerInfo->validator()->add('datetime_cd',
                'notBlank', ['rule' => 'notBlank', 'required' => true, 'message' => ['notBlank', 'kit_datetime']]
            )->add('datetime_cd',
                'isDatetimeDelivery', ['rule' => 'isDatetimeDelivery', 'message' => ['format', 'kit_datetime']]
            );

            if ($this->CustomerInfo->validates()) {
                // session
                // $data[self::MODEL_CUSTOMER_INFO] = $customerInfo;
                CakeSession::write('PurchaseEntryRegister.CustomerInfo', $customerInfo);

                return $this->redirect(['controller' => 'PurchaseEntryRegister', 'action' => 'credit']);
            } else {
                // 配送日時
                $res_datetime = [];
                if (!empty($customerInfo['postal'])) {
                    $res_datetime = $this->getDatetimeDeliveryOutbound($customerInfo['postal']);
                }
                $this->set('datetime', $res_datetime);

                return $this->render('address');
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

        $postal = $this->request->data['postal'];
        $result = $this->getDatetimeDeliveryOutbound($postal);
        $status = !empty($result);

        return json_encode(compact('status', 'result'));
    }

    private function getDatetimeDeliveryOutbound($postal)
    {
        if (!empty($postal)) {
            $res = $this->DatetimeDeliveryOutboundV4->apiGetDatetime([
                'postal' => $postal,
            ]);
            if ($res->isSuccess()) {
                return $res->results;
            }
        }
        return [];
    }

    private function getDatetime($postal, $datetime_cd)
    {
        $data = [];
        $result = $this->getDatetimeDeliveryOutbound($postal);
        foreach ($result as $datetime) {
            if ($datetime['datetime_cd'] === $datetime_cd) {
                $data = $datetime;
            }
        }
        return $data;
    }

    public function credit()
    {
        $data = CakeSession::read('PurchaseEntryRegister');

        if ($this->request->is('get')) {
            // 登録系フローからの戻り時
            $data = CakeSession::read('PurchaseEntryRegister');
            if (!empty($data) && !empty($data[self::MODEL_PAYMENT_CARD])) {
                $data[self::MODEL_PAYMENT_CARD]['security_cd'] = '';
                $this->request->data[self::MODEL_PAYMENT_CARD] = $data[self::MODEL_PAYMENT_CARD];
            }
        }

        if ($this->request->is('post')) {
            // カード情報

            $this->loadModel(self::MODEL_PAYMENT_CARD);
            $this->PaymentGMOSecurityCard->set($this->request->data);

            // Expire
            $this->PaymentGMOSecurityCard->setExpire($this->request->data);
            // ハイフン削除
            $this->PaymentGMOSecurityCard->trimHyphenCardNo($this->request->data);
            // Expire year 表示用
            $this->PaymentGMOSecurityCard->setDisplayExpire($this->request->data);

            // validates
            // card_seq 除外
            $this->PaymentGMOSecurityCard->validator()->remove('card_seq');

            if ($this->PaymentGMOSecurityCard->validates()) {
                // add session
                // $data[self::MODEL_PAYMENT_CARD] = $this->PaymentGMOSecurityCard->data[self::MODEL_PAYMENT_CARD];
                CakeSession::write('PurchaseEntryRegister.PaymentGMOSecurityCard', $this->PaymentGMOSecurityCard->data[self::MODEL_PAYMENT_CARD]);

                return $this->redirect(['controller' => 'PurchaseEntryRegister', 'action' => 'confirm']);
            } else {
                return $this->render('credit');
            }
        }
    }

    public function confirm()
    {
        $data = CakeSession::read('PurchaseEntryRegister');

        $sales_id = Hash::get($data, 'PaymentGMOPurchase.sales_id');
        $this->set('sales_id', $sales_id);

        //*エントリー状態からの遷移では、CustomeeEntry 不要 あとで消す
        //$this->set('cutomerEntry', $data[self::MODEL_CUSTOMER_ENTRY]);
        $this->set('customerInfo', $data[self::MODEL_CUSTOMER_INFO]);
        $this->set('paymentCard', $data[self::MODEL_PAYMENT_CARD]);

        // 配送日時
        $datetime = $this->getDatetime($data[self::MODEL_CUSTOMER_INFO]['postal'], $data[self::MODEL_CUSTOMER_INFO]['datetime_cd']);
        $this->set('datetime', $datetime['text']);

    }

    public function complete()
    {
        $data = CakeSession::read('PurchaseEntryRegister');

        if ($this->request->is('post')) {

            $sales_id = Hash::get($data, 'PaymentGMOPurchase.sales_id');
            $this->set('sales_id', $sales_id);

            // ユーザー情報
            $this->loadModel(self::MODEL_CUSTOMER_INFO);
            $this->CustomerInfo->set($data[self::MODEL_CUSTOMER_INFO]);
            if (!$this->CustomerInfo->validates()) {
                // メアド入力ページへの導線表示
                $this->set('invalid_CustomerInfo', true);
                return $this->render('complete');
            }

            // カード
            $this->loadModel(self::MODEL_PAYMENT_CARD);
            $this->PaymentGMOSecurityCard->set($data[self::MODEL_PAYMENT_CARD]);
            // card_seq 除外
            $this->PaymentGMOSecurityCard->validator()->remove('card_seq');
            if (!$this->PaymentGMOSecurityCard->validates()) {
                // クレジット入力ページへの導線表示
                $this->set('invalid_CreditCard', true);
                return $this->render('complete');
            }

            // エントリーユーザー => ユーザー本登録
            $res = $this->CustomerInfo->apiPost($data[self::MODEL_CUSTOMER_INFO]);
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect('/purchase/' . $sales_id);
            }
            $this->Customer->switchEntryToCustomer();

            // ログイン
            $this->loadModel('CustomerLogin');
            $this->CustomerLogin->data['CustomerLogin']['email'] = $this->CustomerInfo->data[self::MODEL_CUSTOMER_INFO]['email'];
            $this->CustomerLogin->data['CustomerLogin']['password'] = $this->CustomerInfo->data[self::MODEL_CUSTOMER_INFO]['password'];

            $res = $this->CustomerLogin->login();
            if (!empty($res->error_message)) {
                CakeSession::delete('PurchaseEntryRegister');
                $this->Flash->set($res->error_message);
                return $this->redirect('/purchase/' . $sales_id);
            }

            // カスタマー情報を取得しセッションに保存
            $this->Customer->setTokenAndSave($res->results[0]);
            $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);
            $this->Customer->getInfo();

            // カード登録
            $res = $this->PaymentGMOSecurityCard->apiPost($this->PaymentGMOSecurityCard->toArray());
            if (!empty($res->error_message)) {
                // マイページへの導線表示
                CakeSession::delete('PurchaseEntryRegister');
                $this->Flash->set($res->error_message);
                $this->set('apierror_CreditCard', true);
                return $this->render('complete');
            }

            // カード取得
            $this->loadModel('PaymentGMOCard');
            $default_payment = $this->PaymentGMOCard->apiGetDefaultCard();

            // アイテム購入
            $purchase = $data[self::MODEL_NAME];
            $purchase = $this->PaymentGMOPurchase->setAddress($purchase, $data[self::MODEL_CUSTOMER_INFO]);
            $purchase['datetime_cd'] = $data[self::MODEL_CUSTOMER_INFO]['datetime_cd'];
            $purchase['card_seq'] = $default_payment['card_seq'];
            $purchase['security_cd'] = $data[self::MODEL_PAYMENT_CARD]['security_cd'];

            $this->PaymentGMOPurchase->set($purchase);
            // address_id 除外
            $this->PaymentGMOPurchase->validator()->remove('address_id');
            if (!$this->PaymentGMOPurchase->validates()) {
                CakeSession::delete('PurchaseEntryRegister');

                $this->CustomerLogin->logout();
                // セッション値をクリア
                ApiCachedModel::deleteAllCache();
                OutboundList::delete();
                CustomerData::delete();

                $this->Flash->set('アイテムの購入ができませんでした');
                return $this->redirect('/purchase/' . $sales_id);
            }

            // api
            $res = $this->PaymentGMOPurchase->apiPost($this->PaymentGMOPurchase->toArray());
            if (!empty($res->error_message)) {
                CakeSession::delete('PurchaseEntryRegister');

                $this->CustomerLogin->logout();
                // セッション値をクリア
                ApiCachedModel::deleteAllCache();
                OutboundList::delete();
                CustomerData::delete();

                $this->Flash->set($res->error_message);
                return $this->redirect('/purchase/' . $sales_id);
            }

            $this->set('email', $data[self::MODEL_CUSTOMER_INFO]['email']);
            CakeSession::delete('PurchaseEntryRegister');
        }
    }

}
