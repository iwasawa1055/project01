<?php

App::uses('AppController', 'Controller');

class CreditCardController extends AppController
{
    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();

        $select_months = [];
        for($i = 1; $i <=12; $i++) {
            $select_months[sprintf('%02d', $i)] = $i . '月';
        }
        $this->set('select_months', $select_months);

        $now_year = intval(date('Y'));
        $select_years = [];
        for($i = 0; $i <=20; $i++) {
            // $select_years[] = ['value' => $now_year + $i - 2000, 'disp' => $now_year + $i];
            $select_years[$now_year + $i - 2000] = ($now_year + $i) . '年';
        }
        $this->set('select_years', $select_years);
    }

    /**
     * 
     */
    public function edit()
    {
print_rh(CakeSession::read('api.token'));

    }

    /**
     * 
     */
    public function confirm()
    {
        $this->loadModel('PaymentGMOSecurityCard');
        $this->PaymentGMOSecurityCard->set($this->request->data);

        $this->PaymentGMOSecurityCard->data['PaymentGMOSecurityCard']['expire'] = $this->request->data['expire_month'] . $this->request->data['expire_year'];

        if ($this->PaymentGMOSecurityCard->validates()) {

            $this->PaymentGMOSecurityCard->data['PaymentGMOSecurityCard']['expire_year_disp'] = $this->request->data['expire_year'] + 2000;

            $this->set('security_card', $this->PaymentGMOSecurityCard->data['PaymentGMOSecurityCard']);
            CakeSession::write('PaymentGMOSecurityCard', $this->PaymentGMOSecurityCard->data);
        } else {

print_rh($this->PaymentGMOSecurityCard->validationErrors);

            $this->set('validerror', $this->PaymentGMOSecurityCard->validationErrors);
            $this->data = $this->request->data;

            return $this->render('edit');
        }
    }

    /**
     * 
     */
    public function complete()
    {
        if (empty(CakeSession::read('PaymentGMOSecurityCard'))) {

            // TODO:
            $this->Session->setFlash('try again');

            return $this->redirect('/customer/credit_card/edit/');
        }

// print_rh($this->request->data);
// print_rh($this->request->data['expire_month']);

        $this->loadModel('PaymentGMOSecurityCard');
        $this->PaymentGMOSecurityCard->set(CakeSession::read('PaymentGMOSecurityCard'));

print_rh(CakeSession::read('PaymentGMOSecurityCard'));
print_rh($this->PaymentGMOSecurityCard->data);

        $this->PaymentGMOSecurityCard->data['PaymentGMOSecurityCard']['request_method'] = 'put';

// print_rh($this->PaymentGMOSecurityCard->data);

        if ($this->PaymentGMOSecurityCard->validates()) {

// $time_start = microtime(true);
            // $res = $this->PaymentGMOSecurityCard->apiPost($this->PaymentGMOSecurityCard->data);

// print_rh($res);
// $time_end = microtime(true);
// print_rh($time_end - $time_start);

// 
//             if ($res->status === '1') {
// print_rh($res);
//                 $this->set('card', $res->results);
//             } else {
// print_rh($res);
//             }
        } else {

print_rh($this->PaymentGMOSecurityCard->validationErrors);

            $this->set('validerror', $this->PaymentGMOSecurityCard->validationErrors);

            return $this->render('edit');
        }
    }
}
