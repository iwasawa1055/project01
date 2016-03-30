<?php

App::uses('MinikuraController', 'Controller');

class AddressController extends MinikuraController
{
    const MODEL_NAME = 'CustomerAddress';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
        $this->set('action', $this->action);
    }

    protected function isAccessDeny()
    {
        if ($this->Customer->isEntry()) {
            // 個人(仮登録)：アクセス不可
            return true;
        }
        return false;
    }

    public function customer_index()
    {
        $res = $this->CustomerAddress->apiGetResults();
        $this->set('addressList', $res);
    }


    private function setRequestDataFromSession()
    {
        $step = Hash::get($this->request->params, 'step');
        $back = Hash::get($this->request->query, 'back');

        if ($back || $step === 'complete') {
            $data = CakeSession::read(self::MODEL_NAME);
            $this->request->data[self::MODEL_NAME] = $data;
            CakeSession::delete(self::MODEL_NAME);
        } elseif ($this->action === 'customer_edit' && empty($step)) {
            // address_idパラメータから復元
            $addressId = Hash::get($this->request->query, 'address_id');
            $data = $this->CustomerAddress->apiGetResultsFind([], ['address_id' => $addressId]);
            $this->request->data[self::MODEL_NAME] = $data;
        } elseif ($this->action === 'customer_delete' && $step === 'confirm') {
            // address_idパラメータから復元
            $addressId = Hash::get($this->request->data, 'address_id');
            $data = $this->CustomerAddress->apiGetResultsFind([], ['address_id' => $addressId]);
            $this->request->data[self::MODEL_NAME] = $data;
        }
    }

    /**
     *
     */
    public function customer_add()
    {
        $this->setRequestDataFromSession();
        $step = Hash::get($this->request->params, 'step');
        $returnTo = Hash::get($this->request->query, 'return');

        if ($this->request->is('get')) {
            return $this->render('customer_add');
        } elseif ($this->request->is('post')) {
            // validates
            $this->CustomerAddress->set($this->request->data);
            if (!$this->CustomerAddress->validates()) {
                return $this->render('customer_add');
            }

            if ($step === 'confirm') {
                CakeSession::write(self::MODEL_NAME, $this->CustomerAddress->toArray());
                return $this->render('customer_confirm');
            } elseif ($step === 'complete') {
                // create
                $this->CustomerAddress->apiPost($this->CustomerAddress->toArray());

                if ($returnTo === 'order') {
                    return $this->redirect([
                        'controller' => 'order', 'action' => 'add', 'customer' => false,
                        '?' => ['back' => 'true']
                    ]);
                } else if ($returnTo === 'inboundbox') {
                    return $this->redirect([
                        'controller' => 'InboundBox', 'action' => 'add', 'customer' => false,
                        '?' => ['back' => 'true']
                    ]);
                } else if ($returnTo === 'outbound') {
                    return $this->redirect([
                        'controller' => 'outbound', 'action' => 'index', 'customer' => false,
                        '?' => ['back' => 'true']
                    ]);
                }


                return $this->render('customer_complete');
            }
        }
    }

    /**
     *
     */
    public function customer_edit()
    {
        $this->setRequestDataFromSession();
        $step = Hash::get($this->request->params, 'step');

        if (empty($this->request->data[self::MODEL_NAME])) {
            $this->Flash->set(__d('validation', 'select', __('address')));
            return $this->redirect('/customer/address/');
        }

        $this->set('address_id', $this->request->data[self::MODEL_NAME]['address_id']);

        if ($this->request->is('get')) {

            // data from session
            $this->CustomerAddress->set($this->request->data);
            return $this->render('customer_add');
        } elseif ($this->request->is('post')) {

            // validates
            $this->CustomerAddress->set($this->request->data);
            if (!$this->CustomerAddress->validates()) {
                return $this->render('customer_add');
            }

            if ($step === 'confirm') {
                CakeSession::write(self::MODEL_NAME, $this->CustomerAddress->toArray());
                return $this->render('customer_confirm');
            } elseif ($step === 'complete') {
                // update
                $this->CustomerAddress->apiPut($this->CustomerAddress->toArray());
                return $this->render('customer_complete');
            }
        }
    }

    /**
     *
     */
    public function customer_delete()
    {
        $this->setRequestDataFromSession();
        $step = Hash::get($this->request->params, 'step');

        if (empty($this->request->data[self::MODEL_NAME])) {
            $this->Flash->set(__d('validation', 'select', __('address')));
            return $this->redirect('/customer/address/');
        }

        if ($this->request->is('post')) {
            $this->CustomerAddress->set($this->request->data);
            if ($step === 'confirm') {
                CakeSession::write(self::MODEL_NAME, $this->CustomerAddress->toArray());
                return $this->render('customer_confirm');
            } elseif ($step === 'complete') {
                // delete
                $res = $this->CustomerAddress->apiDelete($this->CustomerAddress->toArray());
                if (!empty($res->error_message)) {
                    $this->Flash->set($res->error_message);
                    return $this->redirect(['action' => 'customer_index']);
                }
                return $this->render('customer_complete');
            }
        }
    }
}
