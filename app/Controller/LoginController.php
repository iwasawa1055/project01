<?php

App::uses('MinikuraController', 'Controller');
App::uses('ApiCachedModel', 'Model');
App::uses('OutboundList', 'Model');
App::uses('CustomerEnvAuthed', 'Model');

class LoginController extends MinikuraController
{
    // ログイン不要なページ
    protected $checkLogined = false;

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
                    // パスワード不正など
                    $this->request->data['CustomerLogin']['password'] = '';
                    $this->Flash->set($res->error_message);
                    return $this->render('index');
                }

                // セッション値をクリア
                ApiCachedModel::deleteAllCache();
                OutboundList::delete();
                CustomerData::delete();
                // セッションID変更
                CakeSession::renew();

                // カスタマー情報を取得しセッションに保存
                $this->Customer->setTokenAndSave($res->results[0]);
                $this->Customer->setPassword($this->request->data['CustomerLogin']['password']);
                $this->Customer->getInfo();

                // ユーザー環境値登録
                $this->Customer->postEnvAuthed();

                return $this->redirect(['controller' => 'MyPage', 'action' => 'index']);

            } else {
                $this->request->data['CustomerLogin']['password'] = '';
                return $this->render('index');
            }
        } else if ($this->Customer->isLogined()) {
            // ログイン済
            return $this->redirect(['controller' => 'MyPage', 'action' => 'index']);
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

        return $this->redirect(['controller' => 'login', 'action' => 'index']);
    }
}
