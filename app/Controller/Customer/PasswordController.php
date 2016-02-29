<?php

App::uses('MinikuraController', 'Controller');

class PasswordController extends MinikuraController
{
    const MODEL_NAME = 'CustomerPassword';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    /**
     *
     */
    public function customer_edit()
    {
    }

    /**
     *
     */
    public function customer_complete()
    {
        $model = $this->Customer->getPasswordModel($this->request->data[self::MODEL_NAME]);
        if ($model->validates()) {
            // api
            $res = $model->apiPatch($model->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'edit']);
            }
        } else {
            $this->set('validErrors', $model->validationErrors);
            return $this->render('customer_edit');
        }
    }
}
