<?php

App::uses('MinikuraController', 'Controller');

class PurchaseRegisterController extends MinikuraController
{
    // ログイン不要なページ
    protected $checkLogined = false;

    const MODEL_NAME = 'PaymentGMOPurchase';
    const MODEL_CUSTOMER_ENTRY = 'CustomerEntry';
    const MODEL_CUSTOMER_INFO = 'CustomerRegistInfo';
    const MODEL_DATETIME_DELIVERY = 'DatetimeDeliveryOutboundV4';
    const MODEL_PAYMENT_CARD = 'PaymentGMOSecurityCard';

    public function beforeFilter ()
    {
        parent::beforeFilter();
        // Layouts
        $this->layout = 'market';

        $this->loadModel(self::MODEL_NAME);
        $this->loadModel(self::MODEL_DATETIME_DELIVERY);
    }

    public function address()
    {
        $data = CakeSession::read('PurchaseRegister');
        // $purchase = Hash::get($data, self::MODEL_NAME);
        if (empty($data)) {
            return $this->redirect('/');
        }

        $res_datetime = [];
        if ($this->request->is('get')) {
            // 登録系フローからの戻り時
            $data = CakeSession::read('PurchaseRegister');
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
            $customerEntry = $data[self::MODEL_CUSTOMER_ENTRY];

            $birth = [];
            $birth[0] = $customerInfo['birth_year'];
            $birth[1] = $customerInfo['birth_month'];
            $birth[2] = $customerInfo['birth_day'];
            $customerInfo['birth'] = implode('-', $birth);
            $customerInfo['email'] = $customerEntry['email'];
            $customerInfo['password'] = $customerEntry['password'];
            $customerInfo['password_confirm'] = $customerEntry['password_confirm'];
            $customerInfo['newsletter'] = $customerEntry['newsletter'];

            $this->loadModel(self::MODEL_CUSTOMER_INFO);
            $this->CustomerRegistInfo->set($customerInfo);

            $this->CustomerRegistInfo->validator()->add('datetime_cd',
                'notBlank', ['rule' => 'notBlank', 'required' => true, 'message' => ['notBlank', 'kit_datetime']]
            )->add('datetime_cd',
                'isDatetimeDelivery', ['rule' => 'isDatetimeDelivery', 'message' => ['format', 'kit_datetime']]
            );

            if ($this->CustomerRegistInfo->validates()) {
                // session
                // $data[self::MODEL_CUSTOMER_INFO] = $customerInfo;
                CakeSession::write('PurchaseRegister.CustomerRegistInfo', $customerInfo);

                return $this->redirect(['controller' => 'PurchaseRegister', 'action' => 'credit']);
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

    public function credit()
    {
        $data = CakeSession::read('PurchaseRegister');
        // $purchase = Hash::get($data, self::MODEL_NAME);
        if (empty($data)) {
            return $this->redirect('/');
        }

        if ($this->request->is('get')) {
            // 登録系フローからの戻り時
            $data = CakeSession::read('PurchaseRegister');
            if (!empty($data) && !empty($data[self::MODEL_PAYMENT_CARD])) {
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
                CakeSession::write('PurchaseRegister.PaymentGMOSecurityCard', $this->PaymentGMOSecurityCard->data[self::MODEL_PAYMENT_CARD]);

                return $this->redirect(['controller' => 'PurchaseRegister', 'action' => 'confirm']);
            } else {
                return $this->render('credit');
            }
        }
    }

    public function confirm()
    {
        $data = CakeSession::read('PurchaseRegister');
        // $purchase = Hash::get($data, self::MODEL_NAME);
        if (empty($data)) {
            return $this->redirect('/');
        }

        $sales_id = Hash::get($data, 'PaymentGMOPurchase.sales_id');
        $this->set('sales_id', $sales_id);

        $this->set('cutomerEntry', $data[self::MODEL_CUSTOMER_ENTRY]);
        $this->set('customerInfo', $data[self::MODEL_CUSTOMER_INFO]);
        $this->set('paymentCard', $data[self::MODEL_PAYMENT_CARD]);

        if ($this->request->is('post')) {
            // // カード情報
            // $cardInfo = $this->request->data[self::MODEL_PAYMENT_CARD];
            // 
            // $this->loadModel(self::MODEL_PAYMENT_CARD);
            // $this->PaymentGMOSecurityCard->set($this->request->data);
            // 
            // // Expire
            // $this->PaymentGMOSecurityCard->setExpire($this->request->data);
            // // ハイフン削除
            // $this->PaymentGMOSecurityCard->trimHyphenCardNo($this->request->data);
            // 
            // // validates
            // // card_seq 除外
            // $this->PaymentGMOSecurityCard->validator()->remove('card_seq');
            // 
            // if ($this->PaymentGMOSecurityCard->validates()) {
            //     // add session
            //     $data[self::MODEL_PAYMENT_CARD] = $cardInfo;
            //     CakeSession::write('PurchaseRegister', $data);
            // 
            //     return $this->redirect(['controller' => 'PurchaseRegister', 'action' => 'confirm']);
            // } else {
            //     return $this->render('credit');
            // }
        }
    }

    public function complete()
    {
        // $sales_id = $this->params['id'];
        CakeLog::write(DEBUG_LOG, get_class($this) . __METHOD__);
        // CakeLog::write(DEBUG_LOG, $sales_id);
    }

}
