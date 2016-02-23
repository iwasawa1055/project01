<?php

App::uses('AppController', 'Controller');
App::uses('DatePrivate', 'Model');
App::uses('TimePrivate', 'Model');

class InboundBoxController extends AppController
{
    public $components = array('Address', 'Inbound');

    const MODEL_NAME = 'Inbound';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();

        $this->loadModel('InboundPrivate');

        $list = $this->InfoBox->getListForInbound();
        $this->set('boxList', $list);

        $this->set('addressList', $this->Address->get());
        $this->set('dateList', []);
        $this->set('timeList', []);
    }

    /**
     *
     */
    public function getInboundDatetime()
    {
        $this->autoRender = false;
        if (!$this->request->is('ajax')) {
            return false;
        }
        $this->Inbound->init(Hash::get($this->request->data, self::MODEL_NAME));
        $result['date'] = $this->Inbound->date();
        $result['time'] = $this->Inbound->time();
        $status = !empty($result);
        return json_encode(compact('status', 'result'));
    }

    /**
     *
     */
    public function add()
    {
        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = CakeSession::read(self::MODEL_NAME . 'FORM');
            $this->Inbound->init(Hash::get($this->request->data, self::MODEL_NAME));
            $this->set('dateList', $this->Inbound->date());
            $this->set('timeList', $this->Inbound->time());
        }
        CakeSession::delete(self::MODEL_NAME . 'FORM');
    }

    /**
     *
     */
    public function confirm()
    {
        $data = Hash::get($this->request->data, self::MODEL_NAME);
        if (empty($data)) {
            return $this->render('add');
        }

        // TODO: box_listをチェック
        $box = '';
        foreach ($data['box_list'] as $item) {
            if (!empty($item['checkbox'])) {
                $box .= InboundBase::createBoxParam($item) . ',';
            }
        }
        $data['box'] = rtrim($box, ',');
        unset($data['box_list']);

        // お届け先情報
        $data = $this->Address->merge($data['address_id'], $data);
        $this->Inbound->init($data);
        $model = $this->Inbound->model($data);

        if (empty($model)) {
            return $this->render('add');
        }
        if ($model->validates()) {
            CakeSession::write(self::MODEL_NAME, $model->data);
            CakeSession::write(self::MODEL_NAME . 'FORM', $this->request->data);
            $this->set('dateList', $this->Inbound->date());
            $this->set('timeList', $this->Inbound->time());
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
        CakeSession::delete(self::MODEL_NAME . 'FORM');

        if (empty($data)) {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'add']);
        }

        $data = current($data);
        $this->Inbound->init($data);
        $model = $this->Inbound->model($data);
        if (empty($model)) {
            return $this->render('add');
        }
        if ($model->validates()) {
            // api
            $r = $model->apiPost($model->toArray());
            if (!$r->isSuccess()) {
                // TODO: 例外処理
            }
        } else {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'add']);
        }
    }
}
