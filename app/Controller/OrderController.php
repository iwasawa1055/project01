<?php

App::uses('AppController', 'Controller');

class OrderController extends AppController
{
    const MODEL_NAME = 'PaymentGMOKitCard';
    const MODEL_NAME_CARD = 'PaymentGMOCard';
    const MODEL_NAME_ADDRESS = 'CustomerAddress';
    const MODEL_NAME_DATETIME = 'DatetimeDeliveryKit';

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


        $payment = $this->PaymentGMOCard->apiGet();
        $this->set('payment_card', $payment->results['contents']);
pr($payment->results['contents']);
pr(is_array($payment->results['contents']) ? 'array' : 'not array');

        $address = $this->CustomerAddress->apiGet();
        $this->set('address', $address->results);
// pr($address->results['contents']);
pr($address->results);

        if (is_array($address->results)) {
            $datetime = $this->DatetimeDeliveryKit->apiGet([
              'postal' => $address->results[0]['postal'],
            ]);
            $this->set('datetime', $datetime->results);
// pr($datetime->results);
        }
    }

    /**
     *
     */
    public function add()
    {
    }

    /**
     *
     */
    public function confirm()
    {
    }

    /**
     *
     */
    public function complete()
    {
    }
}
