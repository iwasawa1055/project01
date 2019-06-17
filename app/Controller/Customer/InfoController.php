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

        if ($back) {
            $data = CakeSession::read(self::MODEL_NAME);
            $this->request->data = [self::MODEL_NAME => $data];
            CakeSession::delete(self::MODEL_NAME);
        } elseif ($step === 'complete') {
            $data = CakeSession::read(self::MODEL_NAME);
            $this->request->data = [self::MODEL_NAME => $data];
            // 生年月日が未入力の場合はデフォルトを入れる
            if ($this->request->data[self::MODEL_NAME]['birth_year'] == '' && $this->request->data[self::MODEL_NAME]['birth_month'] == '' && $this->request->data[self::MODEL_NAME]['birth_day'] == '') {
                $this->request->data[self::MODEL_NAME]['birth']       = CUSTOMER_DEFAULT_BIRTH;
                $this->request->data[self::MODEL_NAME]['birth_year']  = CUSTOMER_DEFAULT_BIRTH_YEAR;
                $this->request->data[self::MODEL_NAME]['birth_month'] = CUSTOMER_DEFAULT_BIRTH_MONTH;
                $this->request->data[self::MODEL_NAME]['birth_day']   = CUSTOMER_DEFAULT_BIRTH_DAY;
            }
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
                    // 生年月日の必須を外していた時のユーザーの場合
                    if ($data['birth'] == "1900-01-01") {
                        $this->request->data[self::MODEL_NAME]['birth_year'] = "";
                        $this->request->data[self::MODEL_NAME]['birth_month'] = "";
                        $this->request->data[self::MODEL_NAME]['birth_day'] = "";
                    } else {
                        $ymd = explode('-', $data['birth']);
                        $this->request->data[self::MODEL_NAME]['birth_year'] = $ymd[0];
                        $this->request->data[self::MODEL_NAME]['birth_month'] = $ymd[1];
                        $this->request->data[self::MODEL_NAME]['birth_day'] = $ymd[2];
                    }
                }
            }
        } elseif ($this->action === 'customer_add' && empty($step)) {
            // create 仮登録情報をセット
            $this->request->data[self::MODEL_NAME]['newsletter'] = $this->Customer->getInfo()['newsletter'];
        }
    }

    /**
     * 仮登録後のユーザー情報登録
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
            $data['gender'] = CUSTOMER_DEFAULT_GENDER;
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

                // ご利用中サービスの集計
                $this->set('product_summary', []);
                if (!$this->Customer->isEntry()) {
                    $summary = $this->InfoBox->getProductSummary(false);
                    $this->set('product_summary', $summary);
                    // 出庫済み含めた利用
                    $summary_all = $this->InfoBox->getProductSummary(true, 'summary_all');
                    $this->set('summary_all', $summary_all);
                }

                return $this->render('customer_add_complete');
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
                $customerInfo = $this->Customer->getInfo();
                $data['gender'] = $customerInfo['gender'];
            }
            $model = $this->Customer->getInfoPatchModel($data);

            // 生年月日が1900-01-01で、今回の更新でも生年月日を入力されない場合は生年月日をバリデーションから外す
            if ($this->Customer->isPrivateCustomer()) {
                if ($this->Customer->getInfo()['birth'] == CUSTOMER_DEFAULT_BIRTH && $data['birth_year'] == '' && $data['birth_month'] == '' && $data['birth_day'] == '') {
                    unset($model->validate['birth']);
                }
            }

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
