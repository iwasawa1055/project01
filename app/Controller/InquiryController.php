<?php

App::uses('MinikuraController', 'Controller');
App::uses('Inquiry', 'Model');
App::uses('CustomerEnvUnAuth', 'Model');

class InquiryController extends MinikuraController
{
    const MODEL_NAME = 'Inquiry';

    // ログイン不要なページ
    protected $checkLogined = false;

    /**
     * ルートインデックス.
     */
    public function add()
    {
        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = CakeSession::read(self::MODEL_NAME);
        }
        CakeSession::delete(self::MODEL_NAME);
    }

    /**
     *
     */
    public function confirm()
    {
        $model = new Inquiry();
        $model->set($this->request->data);
        if ($model->validates()) {
            CakeSession::write(self::MODEL_NAME, $model->data);
        } else {
            return $this->render('add');
        }
    }

    /**
     *
     */
    public function complete()
    {
        $data = CakeSession::read(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME);
        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }

        $model = new Inquiry();
        $model->set($data);
        if ($model->validates()) {
            $res = $model->apiPost($model->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'add']);
            }
            // ユーザー環境値登録
            $env = new CustomerEnvUnAuth();
            $env->apiPostEnv($data[self::MODEL_NAME]['email']);

        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }
    }
}
