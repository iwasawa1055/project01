<?php

App::uses('AppController', 'Controller');

class PasswordResetController extends AppController
{
    const MODEL_NAME = 'CustomerPasswordReset';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        // ログイン不要なページ
        $this->checkLogined = false;
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
        $this->CustomerPasswordReset->set($this->request->data);
        if ($this->CustomerPasswordReset->validates()) {
            CakeSession::write($this::MODEL_NAME, $this->CustomerPasswordReset->data);
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

      $this->CustomerPasswordReset->set($data);
      if ($this->CustomerPasswordReset->validates()) {
          // api
          $res = $this->CustomerPasswordReset->apiPut($this->CustomerPasswordReset->data);
          if (!$res->isSuccess()) {
              // TODO:
              $this->Session->setFlash('try again');
              return $this->redirect(['action' => 'add']);
          }
          $this->set('email', $this->CustomerPasswordReset->toArray()['email']);
      } else {
          // TODO:
          $this->Session->setFlash('try again');
          return $this->redirect(['action' => 'add']);
      }
    }
}
