<?php

App::uses('AppController', 'Controller');
App::uses('UserAddress', 'Model');

class InquiryController extends AppController
{
    const MODEL_NAME = 'Inquiry';

    /**
     * 制御前段処理
     */
    public function beforeFilter()
    {
        // ログイン不要なページ
        $this->checkLogined = false;
        AppController::beforeFilter();
        $this->loadModel($this::MODEL_NAME);
    }

    /**
     * ルートインデックス.
     */
    public function add()
    {
        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = CakeSession::read($this::MODEL_NAME);
        }
        CakeSession::delete($this::MODEL_NAME);
    }

    /**
     * 
     */
    public function confirm()
    {
        $this->Inquiry->set($this->request->data);
        if ($this->Inquiry->validates()) {
            CakeSession::write($this::MODEL_NAME, $this->Inquiry->data);
        } else {
            return $this->render('add');
        }
    }

    /**
     * 
     */
    public function complete()
    {
        $data = CakeSession::read($this::MODEL_NAME);
        CakeSession::delete($this::MODEL_NAME);
        if (empty($data)) {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'add']);
        }

        $this->Inquiry->set($data);
        if ($this->Inquiry->validates()) {
            $res = $this->Inquiry->apiPost($this->Inquiry->toArray());
            if (!empty($res->error_message)) {
                // TODO:
                $this->Session->setFlash('try again');
                return $this->redirect(['action' => 'add']);
            }
        } else {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'add']);
        }
    }
}
