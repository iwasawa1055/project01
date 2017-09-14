<?php

App::uses('MinikuraController', 'Controller');
App::uses('Inquiry', 'Model');
App::uses('CustomerEnvUnAuth', 'Model');

class InquiryController extends MinikuraController
{
    const MODEL_NAME = 'Inquiry';

    // ログイン不要なページ
    protected $checkLogined = false;

    public function beforeFilter()
    {
        parent::beforeFilter();
        // ログイン中は専用フォームへ
        if ($this->Customer->isLogined()) {
            return $this->redirect(['controller' => 'contact_us', 'action' => 'add', 'customer' => false]);
        }
    }

    /**
     * ルートインデックス.
     */
    public function add()
    {
        // #14395 リダイレクトループの対策として以前に発行した「.minikura.com」ドメインのcookie()を削除します。
        // 該当のcookieの最長の有効期限は2018/09/14となるので、それ以降に下の処理の削除をお願いします。
        setcookie("WWWMINIKURACOM", "", time()-60, "", ".minikura.com");
        setcookie("MINIKURACOM", "", time()-60, "", ".minikura.com");

        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = CakeSession::read(self::MODEL_NAME);
        }
        CakeSession::delete(self::MODEL_NAME);
    }

    /**
     *
     */
    public function confirm()
    {
        $model = new Inquiry();

        $originalData = $this->request->data;
        // 不具合報告を問い合わせ内容とマージしてチェックする
        $checkData = $model->editText($this->request->data);
        $model->set($checkData); 
        if ($model->validates()) {
            // 戻るなどに対応するため、セッションに保存する前に不具合報告のマージを解除する
            $model->set($originalData);
            CakeSession::write(self::MODEL_NAME, $model->data);
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
        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }

        $model = new Inquiry();
        $data = $model->editText($data);
        $model->set($data);
        if ($model->validates()) {
            // リクエスト本体には例外処理を入れる from 2016.6.22
            try {
                $res = $model->apiPost($model->toArray());
            } catch (Exception $e) {
                $this->Flash->set(__('お問い合わせの送信に失敗しました。'));
                return $this->redirect(['action' => 'add']);
            }

            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'add']);
            }
            // ユーザー環境値登録
            $env = new CustomerEnvUnAuth();
            $env->apiPostEnv($data[self::MODEL_NAME]['email']);

        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }
    }
}
