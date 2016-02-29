<?php

App::uses('MinikuraController', 'Controller');

class PasswordController extends MinikuraController
{
    // const MODEL_NAME = 'CustomerPassword';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        // $this->loadModel(self::MODEL_NAME);
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
        $formName = 'CustomerPassword';
        $model = $this->Customer->getPasswordModel();
// pr($model->getModelName());
// pr($this->request->data[$formName]);
        $model->set([$model->getModelName() => $this->request->data[$formName]]);

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
