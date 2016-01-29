<?php

App::uses('AppController', 'Controller');

class AddressController extends AppController
{
    const MODEL_NAME = 'CustomerAddress';
    const MODEL_NAME_DATA = 'CustomerAddressData';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel($this::MODEL_NAME);
        $this->set('action', $this->action);
    }

    public function index()
    {
        $res = $this->CustomerAddress->apiGet();
        $this->set('address', $res->results);
        CakeSession::write($this::MODEL_NAME_DATA, $res->results);
    }


    private function setRequestDataFromSession() {
        $step = Hash::get($this->request->params, 'step');
        $back = Hash::get($this->request->query, 'back');
        if ($back || $step === 'complete') {
            $data = CakeSession::read($this::MODEL_NAME);
            $this->request->data = $data;
            CakeSession::delete($this::MODEL_NAME);
        }
    }

    private function setRequestDataFromSessionList($keyName) {
        $addressId = $this->CustomerAddress->toArray()[$keyName];
        $list = CakeSession::read($this::MODEL_NAME_DATA);
        foreach ($list as $data) {
            if ($addressId === $data[$keyName]) {
                $this->request->data[$this::MODEL_NAME] = $data;
                break;
            }
        }
        if (empty($this->request->data[$this::MODEL_NAME])) {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'add']);
        }
    }

    /**
     *
     */
    public function add()
    {
        $this->setRequestDataFromSession();
        $step = Hash::get($this->request->params, 'step');

        if ($this->request->is('get')) {
            return $this->render('add');
        } elseif ($this->request->is('post')) {

            // validates
            $this->CustomerAddress->set($this->request->data);
            if (!$this->CustomerAddress->validates()) {
                return $this->render('add');
            }

            if ($step === 'confirm') {
                CakeSession::write($this::MODEL_NAME, $this->CustomerAddress->data);
                return $this->render('confirm');
            } elseif ($step === 'complete') {
                // create
                $this->CustomerAddress->apiPost($this->CustomerAddress->toArray());
                return $this->render('complete');
            }
        }
    }

    /**
     *
     */
    public function edit()
    {
        $this->setRequestDataFromSession();
        $step = Hash::get($this->request->params, 'step');

        if ($this->request->is('get')) {

            // data from session
            $this->CustomerAddress->set($this->request->query);
            $this->setRequestDataFromSessionList('address_id');

            return $this->render('add');
        } elseif ($this->request->is('post')) {

            // validates
            $this->CustomerAddress->set($this->request->data);
            if (!$this->CustomerAddress->validates()) {
                return $this->render('add');
            }

            if ($step === 'confirm') {
                CakeSession::write($this::MODEL_NAME, $this->CustomerAddress->data);
                return $this->render('confirm');
            } elseif ($step === 'complete') {
                // update
                $this->CustomerAddress->apiPut($this->CustomerAddress->toArray());
                return $this->render('complete');
            }
        }
    }

    /**
     *
     */
    public function delete()
    {
        $this->setRequestDataFromSession();
        $step = Hash::get($this->request->params, 'step');

        if ($this->request->is('post')) {

            $this->CustomerAddress->set($this->request->data);

            if ($step === 'confirm') {
                $this->setRequestDataFromSessionList('address_id');
                CakeSession::write($this::MODEL_NAME, $this->CustomerAddress->data);
                return $this->render('confirm');
            } elseif ($step === 'complete') {
                // delete
                $res = $this->CustomerAddress->apiDelete($this->CustomerAddress->data);
                if (!$res->isSuccess()) {
                    // TODO:
                    $this->Session->setFlash('try again');
                    return $this->redirect(['action' => 'add']);
                }
                return $this->render('complete');
            }
        }
    }
}
