<?php

App::uses('AppController', 'Controller');
App::uses('ApiCachedModel', 'Model');
App::uses('OutboundList', 'Model');

class LoginController extends AppController
{
    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        // ログイン不要なページ
        $this->checkLogined = false;
        parent::beforeFilter();
    }

    /**
     * ルートインデックス.
     */
    public function index()
    {
        if ($this->request->is('post')) {
            $this->loadModel('CustomerLogin');
            $this->CustomerLogin->set($this->request->data);

            if ($this->CustomerLogin->validates()) {

                $res = $this->CustomerLogin->login();
                if (!empty($res->error_message)) {
                    // TODO: 例外処理
                    $this->request->data['CustomerLogin']['password'] = '';
                    $this->Session->setFlash($res->error_message);
                    return $this->render('index');
                }

                // カスタマー情報を取得しセッションに保存
                // token
                $this->customer->setTokenAndSave($res->results[0]);

                if ($this->customer->isPrivateCustomer()) {
                    // 個人
                    if ($this->customer->isEntry()) {
                        // 仮登録情報取得
                        $this->loadModel('CustomerEntry');
                        $res = $this->CustomerEntry->apiGet();
                    } else {
                        // 本登録情報取得
                        $this->loadModel('CustomerInfo');
                        $res = $this->CustomerInfo->apiGet();
                    }
                } else {
                    // 法人
                    // 本登録情報取得
                    $this->loadModel('CorporateInfo');
                    $res = $this->CorporateInfo->apiGet();
                }
                $this->customer->setInfoAndSave($res->results[0]);

                // 債務ユーザーの場合
                if ($this->customer->isPaymentNG()) {
                    return $this->redirect(['controller' => 'credit_card', 'action' => 'edit', 'paymentng' => true]);
                }

                return $this->redirect('/');

            } else {
                $this->request->data['CustomerLogin']['password'] = '';
                return $this->render('index');
            }
        }
    }

    public function logout()
    {
        $this->loadModel('CustomerLogin');
        $this->CustomerLogin->logout();

        // セッション値をクリア
        ApiCachedModel::deleteAllCache();
        OutboundList::delete();
        CustomerData::delete();

        return $this->redirect('/login');
    }
}
