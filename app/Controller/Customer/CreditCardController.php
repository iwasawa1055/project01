<?php

App::uses('MinikuraController', 'Controller');

class CreditCardController extends MinikuraController
{
    const MODEL_NAME_SECURITY = 'PaymentGMOSecurityCard';
    const MODEL_NAME_CARD = 'PaymentGMOCard';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME_SECURITY);
        $this->loadModel(self::MODEL_NAME_CARD);
        $this->set('action', $this->action);
    }

    private function setRequestDataFromSession() {
        $step = Hash::get($this->request->params, 'step');
        $back = Hash::get($this->request->query, 'back');

        if ($back || $step === 'complete') {
            $data = CakeSession::read(self::MODEL_NAME_SECURITY);
            $this->request->data = $data;
            CakeSession::delete(self::MODEL_NAME_SECURITY);
        } elseif (($this->action === 'customer_edit' || $this->action === 'paymentng_edit') && empty($step)) {
            // edit 初期表示データ取得
            $default_payment = $this->PaymentGMOCard->apiGetDefaultCard();
            $this->request->data[self::MODEL_NAME_SECURITY] = $default_payment;
        } elseif ($this->action === 'customer_add' && empty($step)) {
            // create カード登録確認
            $default_payment = $this->PaymentGMOCard->apiGetDefaultCard();
            if (!empty($default_payment)) {
                return $this->redirect(['controller' => 'order', 'action' => 'add', 'customer' => false]);
            }
        }
    }

    /**
     * 登録
     */
    public function customer_add()
    {
        $this->setRequestDataFromSession();
        $step = Hash::get($this->request->params, 'step');

        if ($this->request->is('get')) {
            // TODO: カード件数確認

            return $this->render('customer_edit');
        } elseif ($this->request->is('post')) {

            $this->PaymentGMOSecurityCard->set($this->request->data);
            // Expire
            $this->PaymentGMOSecurityCard->setExpire($this->request->data);
            // ハイフン削除
            $this->PaymentGMOSecurityCard->trimHyphenCardNo($this->request->data);

            // validates
            // card_seq 除外
            $this->PaymentGMOSecurityCard->validator()->remove('card_seq');
            if (!$this->PaymentGMOSecurityCard->validates()) {
                return $this->render('customer_edit');
            }

            if ($step === 'confirm') {
                // Expire year 表示用
                $this->PaymentGMOSecurityCard->setDisplayExpire($this->request->data);

                $this->set('security_card', $this->PaymentGMOSecurityCard->data[self::MODEL_NAME_SECURITY]);
                CakeSession::write(self::MODEL_NAME_SECURITY, $this->PaymentGMOSecurityCard->data);

                return $this->render('customer_confirm');
            } elseif ($step === 'complete') {
                // create
                $res = $this->PaymentGMOSecurityCard->apiPost($this->PaymentGMOSecurityCard->toArray());
                if (!empty($res->error_message)) {
                    $this->Flash->set($res->error_message);
                    return $this->redirect(['action' => 'add']);
                }

                if ($this->Customer->isEntry()) {
                    // 契約情報登録
                    return $this->redirect(['controller' => 'info', 'action' => 'add']);
                } else {
                    return $this->redirect(['controller' => 'order', 'action' => 'add', 'customer' => false]);
                }
            }
        }
    }

    /**
     * 修正
     */
    public function customer_edit()
    {
        $this->setRequestDataFromSession();
        $step = Hash::get($this->request->params, 'step');

        if ($this->request->is('get')) {
            return $this->render('customer_edit');
        } elseif ($this->request->is('post')) {

            $this->PaymentGMOSecurityCard->set($this->request->data);
            // Expire
            $this->PaymentGMOSecurityCard->setExpire($this->request->data);
            // ハイフン削除
            $this->PaymentGMOSecurityCard->trimHyphenCardNo($this->request->data);

            // validates
            if (!$this->PaymentGMOSecurityCard->validates()) {
                return $this->render('customer_edit');
            }

            if ($step === 'confirm') {
                // Expire year 表示用
                $this->PaymentGMOSecurityCard->setDisplayExpire($this->request->data);

                $this->set('security_card', $this->PaymentGMOSecurityCard->data[self::MODEL_NAME_SECURITY]);
                CakeSession::write(self::MODEL_NAME_SECURITY, $this->PaymentGMOSecurityCard->data);

                return $this->render('customer_confirm');
            } elseif ($step === 'complete') {
                // update
                $res = $this->PaymentGMOSecurityCard->apiPut($this->PaymentGMOSecurityCard->toArray());
                if (!empty($res->error_message)) {
                    $this->Flash->set($res->error_message);
                    return $this->redirect(['action' => 'edit']);
                }

                return $this->render('customer_complete');
            }
        }
    }

    /**
     * 債務ユーザー
     */
    public function paymentng_edit()
    {
        $this->setRequestDataFromSession();
        $step = Hash::get($this->request->params, 'step');

        if ($this->request->is('get')) {
            $this->Flash->paymentng_card_edit('');
            return $this->render('customer_edit');
        } elseif ($this->request->is('post')) {

            $this->PaymentGMOSecurityCard->set($this->request->data);
            // Expire
            $this->PaymentGMOSecurityCard->setExpire($this->request->data);
            // ハイフン削除
            $this->PaymentGMOSecurityCard->trimHyphenCardNo($this->request->data);

            // validates
            if (!$this->PaymentGMOSecurityCard->validates()) {
                return $this->render('customer_edit');
            }

            if ($step === 'confirm') {
                // Expire year 表示用
                $this->PaymentGMOSecurityCard->setDisplayExpire($this->request->data);

                $this->set('security_card', $this->PaymentGMOSecurityCard->data[self::MODEL_NAME_SECURITY]);
                CakeSession::write(self::MODEL_NAME_SECURITY, $this->PaymentGMOSecurityCard->data);

                return $this->render('customer_confirm');
            } elseif ($step === 'complete') {
                // update
                $res = $this->PaymentGMOSecurityCard->apiPut($this->PaymentGMOSecurityCard->toArray());
                if (!empty($res->error_message)) {
                    $this->Flash->set($res->error_message);
                    return $this->redirect(['action' => 'customer_edit']);
                }
                return $this->redirect(['controller' => 'login', 'action' => 'logout', 'paymentng' => false]);
            }
        }
    }
}
