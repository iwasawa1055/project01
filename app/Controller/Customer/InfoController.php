<?php

App::uses('MinikuraController', 'Controller');

class InfoController extends MinikuraController
{
    const MODEL_NAME = 'CustomerInfo';
    public $modelName = null;

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('action', $this->action);
    }

    protected function isAccessDeny()
    {
        if ($this->Customer->isEntry() && $this->action === 'customer_edit') {
            // 個人(仮登録)：変更不可
            return true;
        } elseif (!$this->Customer->isEntry() && $this->action === 'customer_add') {
            // 本登録：登録不可
            return true;
        }
        return false;
    }

    private function setRequestDataFromSession()
    {
        $step = Hash::get($this->request->params, 'step');
        $back = Hash::get($this->request->query, 'back');

        if ($back || $step === 'complete') {
            $data = CakeSession::read(self::MODEL_NAME);
            $this->request->data = [self::MODEL_NAME => $data];
            CakeSession::delete(self::MODEL_NAME);
        } elseif ($this->action === 'customer_edit' && empty($step)) {
            // edit 初期表示データ取得
            $model = $this->Customer->getInfoGetModel();
            $res = $model->apiGet();
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
            } else {
                $data = $res->results[0];
                $this->request->data[self::MODEL_NAME] = $data;
                if ($this->Customer->isPrivateCustomer()) {
                    $ymd = explode('-', $data['birth']);
                    $this->request->data[self::MODEL_NAME]['birth_year'] = $ymd[0];
                    $this->request->data[self::MODEL_NAME]['birth_month'] = $ymd[1];
                    $this->request->data[self::MODEL_NAME]['birth_day'] = $ymd[2];
                }
            }
        } elseif ($this->action === 'customer_add' && empty($step)) {
            // create 仮登録情報をセット
            $this->request->data[self::MODEL_NAME]['newsletter'] = $this->Customer->getInfo()['newsletter'];
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
            $data = $this->request->data[self::MODEL_NAME];
            $birth = [];
            $birth[0] = $data['birth_year'];
            $birth[1] = $data['birth_month'];
            $birth[2] = $data['birth_day'];
            $data['birth'] = implode('-', $birth);
            $model = $this->Customer->getInfoPostModel($data);

            if (!$model->validates()) {
                $this->set('validErrors', $model->validationErrors);
                return $this->render('customer_add');
            }

            if ($step === 'confirm') {
                CakeSession::write(self::MODEL_NAME, $model->toArray());
                return $this->render('customer_confirm');
            } elseif ($step === 'complete') {
                // create
                $res = $model->apiPost($model->toArray());
                if (!empty($res->error_message)) {
                    $this->Flash->set($res->error_message);
                    return $this->render('customer_add');
                }

                $this->Customer->switchEntryToCustomer();

                // TODO: 紹介コードありはキット購入へ

                return $this->redirect(['controller' => 'order', 'action' => 'add', 'customer' => false, '?' => ['back' => 'true']]);
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
            $data = $this->request->data[self::MODEL_NAME];
            if ($this->Customer->isPrivateCustomer()) {
                $birth = [];
                $birth[0] = $data['birth_year'];
                $birth[1] = $data['birth_month'];
                $birth[2] = $data['birth_day'];
                $data['birth'] = implode('-', $birth);
            }
            $model = $this->Customer->getInfoPatchModel($data);

            if (!$model->validates()) {
                $this->set('validErrors', $model->validationErrors);
                return $this->render('customer_edit');
            }

            if ($step === 'confirm') {
                CakeSession::write(self::MODEL_NAME, $model->toArray());
                return $this->render('customer_confirm');
            } elseif ($step === 'complete') {
                // update
                $res = $model->apiPatch($model->toArray());
                if (!empty($res->error_message)) {
                    $this->Flash->set($res->error_message);
                    return $this->render('customer_edit');
                }
                return $this->render('customer_complete');
            }
        }
    }
}
