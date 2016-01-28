<?php

App::uses('AppController', 'Controller');

class InfoController extends AppController
{
    const MODEL_NAME_CUSTOMER = 'Customer';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel($this::MODEL_NAME_CUSTOMER);
        $this->set('action', $this->action);
    }

    private function setRequestDataFromSession() {
        $step = Hash::get($this->request->params, 'step');
        $back = Hash::get($this->request->query, 'back');
        if ($back || $step === 'complete') {
            $data = CakeSession::read($this::MODEL_NAME_CUSTOMER);
            $this->request->data = $data;
            CakeSession::delete($this::MODEL_NAME_CUSTOMER);
        } elseif (empty($back) && empty($step)) {
            // 初期表示 edit
            // 個人
            $data = $this->Customer->apiGetResults();
            $this->request->data[$this::MODEL_NAME_CUSTOMER] = $data[0];
            $ymd = explode('-', $data[0]['birth']);
            $this->request->data[$this::MODEL_NAME_CUSTOMER]['birth_year'] = $ymd[0];
            $this->request->data[$this::MODEL_NAME_CUSTOMER]['birth_month'] = $ymd[1];
            $this->request->data[$this::MODEL_NAME_CUSTOMER]['birth_day'] = $ymd[2];
        }
    }

    /**
     *
     */
    public function edit()
    {
        $this->setRequestDataFromSession();
        $step = Hash::get($this->request->params, 'step');

        if ($this->request->is('get')) {
            // // 個人
            // $data = $this->Customer->apiGetResults();
            // $this->request->data[$this::MODEL_NAME_CUSTOMER] = $data[0];
            // $ymd = explode('-', $data[0]['birth']);
            // $this->request->data[$this::MODEL_NAME_CUSTOMER]['birth_year'] = $ymd[0];
            // $this->request->data[$this::MODEL_NAME_CUSTOMER]['birth_month'] = $ymd[1];
            // $this->request->data[$this::MODEL_NAME_CUSTOMER]['birth_day'] = $ymd[2];

            return $this->render('edit');
        } elseif ($this->request->is('post')) {
pr($this->request->data);

            // validates
            $this->Customer->set($this->request->data);
            $birth = [];
            $birth[0] = $this->request->data[$this::MODEL_NAME_CUSTOMER]['birth_year'];
            $birth[1] = $this->request->data[$this::MODEL_NAME_CUSTOMER]['birth_month'];
            $birth[2] = $this->request->data[$this::MODEL_NAME_CUSTOMER]['birth_day'];
            $this->Customer->data[$this::MODEL_NAME_CUSTOMER]['birth'] = implode('-', $birth);
pr($this->Customer->data);

            if (!$this->Customer->validates()) {
                return $this->render('edit');
            }
pr($this->Customer->validationErrors);

            if ($step === 'confirm') {
                CakeSession::write($this::MODEL_NAME_CUSTOMER, $this->Customer->data);
                return $this->render('confirm');
            } elseif ($step === 'complete') {
            //     // update
            //     $res = $this->CustomerAddress->apiPut($this->CustomerAddress->data);
            //     if (!$res->isSuccess()) {
            //         // TODO:
            //         $this->Session->setFlash('try again');
            //         return $this->redirect(['action' => 'add']);
            //     }
            // 
            //     return $this->render('complete');
            }
        }
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
