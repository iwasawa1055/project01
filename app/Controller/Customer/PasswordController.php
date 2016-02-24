<?php

App::uses('AppController', 'Controller');

class PasswordController extends AppController
{
    const MODEL_NAME = 'CustomerPassword';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
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
        $this->CustomerPassword->set($this->request->data);
        if ($this->CustomerPassword->validates()) {
            // api
            $res = $this->CustomerPassword->apiPatch($this->CustomerPassword->toArray());
            if (!empty($res->error_message)) {
                // TODO:　モデル
                $this->Flash->set('パスワードを変更できませんでした。現在のパスワードが正しいかご確認ください。');
                return $this->redirect(['action' => 'edit']);
            }
        } else {
            return $this->render('customer_edit');
        }
    }
}
