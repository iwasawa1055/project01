<?php

App::uses('MinikuraController', 'Controller');

class EmailController extends MinikuraController
{
    const MODEL_NAME = 'CustomerEmail';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
        $this->loadModel(self::MODEL_NAME_INFO);

        $this->set('current_email', $this->Customer->getInfo()['email']);
    }

    /**
     * 入力
     */
    public function customer_edit()
    {
        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = [self::MODEL_NAME => CakeSession::read(self::MODEL_NAME)];
        }
        CakeSession::delete(self::MODEL_NAME);
    }

    /**
     * 確認
     */
    public function customer_confirm()
    {
        $model = $this->Customer->getEmailModel($this->request->data[self::MODEL_NAME]);
        if ($model->validates()) {
            CakeSession::write(self::MODEL_NAME, $model->toArray());
        } else {
            $this->set('validErrors', $model->validationErrors);
            return $this->render('customer_edit');
        }
    }

    /**
     * 完了
     */
    public function customer_complete()
    {
        $data = CakeSession::read(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME);

        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'edit']);
        }

        $model = $this->Customer->getEmailModel($data);
        if ($model->validates()) {
            // api
            $res = $model->apiPatch($model->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'edit']);
            }

            $this->Customer->reloadInfo();
            $this->set('email', $model->toArray()['email']);
        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'edit']);
        }
    }
}
