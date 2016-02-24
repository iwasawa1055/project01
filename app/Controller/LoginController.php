<?php

App::uses('AppController', 'Controller');
App::uses('ApiCachedModel', 'Model');
App::uses('OutboundList', 'Model');
App::uses('CustomerEnvAuthed', 'Model');

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
                    $this->request->data['CustomerLogin']['password'] = '';
                    $this->Flash->set($res->error_message);
                    return $this->render('index');
                }

                // カスタマー情報を取得しセッションに保存
                // token
                $this->customer->setTokenAndSave($res->results[0]);


                // ユーザー環境値登録
                $env = new CustomerEnvAuthed();
                $env->apiPostEnv($this->customer->getInfo()['email']);

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
