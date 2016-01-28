<?php

App::uses('AppController', 'Controller');

class InfoController extends AppController
{
    const MODEL_NAME_CUSTOMER = 'CustomerInfo';

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
            // edit 初期表示データ取得
            // 個人
            $data = $this->CustomerInfo->apiGetResults();
            $this->request->data[$this::MODEL_NAME_CUSTOMER] = $data[0];
            $ymd = explode('-', $data[0]['birth']);
            $this->request->data[$this::MODEL_NAME_CUSTOMER]['birth_year'] = $ymd[0];
            $this->request->data[$this::MODEL_NAME_CUSTOMER]['birth_month'] = $ymd[1];
            $this->request->data[$this::MODEL_NAME_CUSTOMER]['birth_day'] = $ymd[2];
        }
    }

    /**
     * ユーザー情報変更
     */
    public function edit()
    {
        $this->setRequestDataFromSession();
        $step = Hash::get($this->request->params, 'step');

        if ($this->request->is('get')) {
            return $this->render('edit');
        } elseif ($this->request->is('post')) {
            // validates
            $this->CustomerInfo->set($this->request->data);
            $birth = [];
            $birth[0] = $this->request->data[$this::MODEL_NAME_CUSTOMER]['birth_year'];
            $birth[1] = $this->request->data[$this::MODEL_NAME_CUSTOMER]['birth_month'];
            $birth[2] = $this->request->data[$this::MODEL_NAME_CUSTOMER]['birth_day'];
            $this->CustomerInfo->data[$this::MODEL_NAME_CUSTOMER]['birth'] = implode('-', $birth);

            if (!$this->CustomerInfo->validates()) {
                return $this->render('edit');
            }

            if ($step === 'confirm') {
                CakeSession::write($this::MODEL_NAME_CUSTOMER, $this->CustomerInfo->data);
                return $this->render('confirm');
            } elseif ($step === 'complete') {
                // update
                $res = $this->CustomerInfo->apiPatchResults($this->CustomerInfo->toArray());

                return $this->render('complete');
            }
        }
    }
}
