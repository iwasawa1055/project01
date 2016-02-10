<?php

App::uses('AppController', 'Controller');

class ContactUsController extends AppController
{
    const MODEL_NAME = 'ContactUs';

    /**
     * 制御前段処理
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel($this::MODEL_NAME);
    }

    /**
     * 
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
        $this->ContactUs->set($this->request->data);
        if ($this->ContactUs->validates()) {
            CakeSession::write($this::MODEL_NAME, $this->ContactUs->data);
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

        $this->ContactUs->set($data);
        if ($this->ContactUs->validates()) {
            $res = $this->ContactUs->apiPost($this->ContactUs->toArray());
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
