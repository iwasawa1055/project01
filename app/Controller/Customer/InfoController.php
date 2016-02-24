<?php

App::uses('AppController', 'Controller');

class InfoController extends AppController
{
    const MODEL_NAME_CUSTOMER = 'CustomerInfoV3';
    const MODEL_NAME = 'CustomerInfo';
    public $modelName = null;

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();

        if ($this->action === 'customer_edit') {
            $this->modelName = self::MODEL_NAME_CUSTOMER;
        } else {
            $this->modelName = self::MODEL_NAME;
        }
        $this->loadModel($this->modelName);
        $this->set('model', $this->modelName);
        $this->set('action', $this->action);
    }

    private function setRequestDataFromSession() {
        $step = Hash::get($this->request->params, 'step');
        $back = Hash::get($this->request->query, 'back');

        if ($back || $step === 'complete') {
            $data = CakeSession::read($this->modelName);
            $this->request->data = $data;
            CakeSession::delete($this->modelName);
        } elseif ($this->action === 'customer_edit' && empty($step)) {
            // edit 初期表示データ取得
            // 個人
            $data = $this->CustomerInfoV3->apiGetResults();
            $this->request->data[self::MODEL_NAME_CUSTOMER] = $data[0];
            $ymd = explode('-', $data[0]['birth']);
            $this->request->data[self::MODEL_NAME_CUSTOMER]['birth_year'] = $ymd[0];
            $this->request->data[self::MODEL_NAME_CUSTOMER]['birth_month'] = $ymd[1];
            $this->request->data[self::MODEL_NAME_CUSTOMER]['birth_day'] = $ymd[2];
        } elseif ($this->action === 'customer_add' && empty($step)) {
            // create 仮登録情報をセット
            $this->request->data[self::MODEL_NAME]['newsletter'] = $this->customer->info['newsletter'];
        }
    }

    /**
     * ユーザー情報登録
     */
    public function customer_add()
    {
        $this->setRequestDataFromSession();
        $step = Hash::get($this->request->params, 'step');

        if ($this->request->is('get')) {
            return $this->render('customer_add');
        } elseif ($this->request->is('post')) {
            // validates
            $this->CustomerInfo->set($this->request->data);
            $birth = [];
            $birth[0] = $this->request->data[self::MODEL_NAME]['birth_year'];
            $birth[1] = $this->request->data[self::MODEL_NAME]['birth_month'];
            $birth[2] = $this->request->data[self::MODEL_NAME]['birth_day'];
            $this->CustomerInfo->data[self::MODEL_NAME]['birth'] = implode('-', $birth);

            if (!$this->CustomerInfo->validates()) {
                return $this->render('customer_add');
            }

            if ($step === 'confirm') {
                CakeSession::write(self::MODEL_NAME, $this->CustomerInfo->data);
                return $this->render('customer_confirm');
            } elseif ($step === 'complete') {
                // create
                $res = $this->CustomerInfo->apiPost($this->CustomerInfo->toArray());
                if (!empty($res->error_message)) {
                    // TODO: 例外処理
                    $this->Flash->set($res->error_message);
                    return $this->redirect(['action' => 'add']);
                }

                $this->customer->token['regist_level'] = CUSTOMER_REGIST_LEVEL_CUSTOMER;

                $res = $this->CustomerInfo->apiGet();
                $this->customer->setInfoAndSave($res->results[0]);

                return $this->redirect(['controller' => 'order', 'action' => 'add', 'customer' => false]);
            }
        }
    }

    /**
     * ユーザー情報変更
     */
    public function customer_edit()
    {
        $this->setRequestDataFromSession();
        $step = Hash::get($this->request->params, 'step');

        if ($this->request->is('get')) {
            return $this->render('customer_edit');
        } elseif ($this->request->is('post')) {
            // validates
            $this->CustomerInfoV3->set($this->request->data);
            $birth = [];
            $birth[0] = $this->request->data[self::MODEL_NAME_CUSTOMER]['birth_year'];
            $birth[1] = $this->request->data[self::MODEL_NAME_CUSTOMER]['birth_month'];
            $birth[2] = $this->request->data[self::MODEL_NAME_CUSTOMER]['birth_day'];
            $this->CustomerInfoV3->data[self::MODEL_NAME_CUSTOMER]['birth'] = implode('-', $birth);

            if (!$this->CustomerInfoV3->validates()) {
                return $this->render('customer_edit');
            }

            if ($step === 'confirm') {
                CakeSession::write(self::MODEL_NAME_CUSTOMER, $this->CustomerInfoV3->data);
                return $this->render('customer_confirm');
            } elseif ($step === 'complete') {
                // update
                $res = $this->CustomerInfoV3->apiPatchResults($this->CustomerInfoV3->toArray());

                return $this->render('customer_complete');
            }
        }
    }
}
