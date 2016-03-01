<?php

App::uses('MinikuraController', 'Controller');

class InquiryController extends MinikuraController
{
    const MODEL_NAME = 'Inquiry';
    const MODEL_NAME_ENV = 'CustomerEnvUnAuth';

    // ログイン不要なページ
    protected $checkLogined = false;

    /**
     * 制御前段処理
     */
    public function beforeFilter()
    {
        // ログイン不要なページ
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
        $this->loadModel(self::MODEL_NAME_ENV);
    }

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
        $this->Inquiry->set($this->request->data);
        if ($this->Inquiry->validates()) {
            CakeSession::write(self::MODEL_NAME, $this->Inquiry->data);
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

        $this->Inquiry->set($data);
        if ($this->Inquiry->validates()) {
            $res = $this->Inquiry->apiPost($this->Inquiry->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'add']);
            }

            // ユーザー環境値登録
            $this->CustomerEnvUnAuth->apiPostEnv($data[self::MODEL_NAME]['email']);

        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }
    }
}
