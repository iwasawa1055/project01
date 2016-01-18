<?php

App::uses('AppController', 'Controller');

class AddressController extends AppController
{
    const MODEL_NAME = 'CustomerAddress';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel($this::MODEL_NAME);
    }

    public function index()
    {
        $res = $this->CustomerAddress->apiGet();
        $this->set('address', $res->results);
        pr($res->results);
    }

    /**
     *
     */
    public function add()
    {
        $data = CakeSession::read($this::MODEL_NAME);
        CakeSession::delete($this::MODEL_NAME);

        if ($this->request->is('get')) {
            // restore
            if (!empty(Hash::get($this->request->query, 'back'))) {
                $this->request->data = $data;
            }
            return $this->render('add');
        } elseif ($this->request->is('post')) {

            // validates
            $this->CustomerAddress->set($this->request->data);

            if (!$this->CustomerAddress->validates()) {
                return $this->render('add');
            }

            $step = $this->request->params['step'];
            if ($step === 'confirm') {
                CakeSession::write($this::MODEL_NAME, $this->CustomerAddress->data);
                return $this->render('confirm');
            } elseif ($step === 'complete') {
                // create
                $res = $this->CustomerAddress->apiPost($this->CustomerAddress->data);
                if (!$res->isSuccess()) {
                    // TODO:
                    $this->Session->setFlash('try again');
                    return $this->redirect(['action' => 'add']);
                }

                return $this->render('complete');
            }
        }
    }

    /**
     *
     */
    public function edit()
    {
    }

    private function _back()
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
    public function delete()
    {
        $this->render('/Customer/Address/confirm');
    }

    /**
     *
     */
    public function confirm()
    {
        // $this->CustomerPasswordReset->set($this->request->data);
        // if ($this->CustomerPasswordReset->validates()) {
        //     CakeSession::write($this::MODEL_NAME, $this->CustomerPasswordReset->data);
        // } else {
        //     return $this->render('add');
        // }
    }

    /**
     *
     */
    public function complete()
    {
    }
}
