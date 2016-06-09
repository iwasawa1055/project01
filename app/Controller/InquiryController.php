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
        $model->set($this->request->data);
        if ($model->validates()) {
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

        if ($data['Inquiry']['division'] === CONTACT_DIVISION_BUG) {
            $data['Inquiry']['text'] .= "\n\n\n";
            $data['Inquiry']['text'] .= "==== 不具合発生日時 ====\n";
            $data['Inquiry']['text'] .= $data['Inquiry']['bug_datetime'] . "\n\n";
            $data['Inquiry']['text'] .= "==== 不具合発生 URL ====\n";
            $data['Inquiry']['text'] .= $data['Inquiry']['bug_url'] . "\n\n";
            $data['Inquiry']['text'] .= "==== ご利用環境（OS・ブラウザ）====\n";
            $data['Inquiry']['text'] .= $data['Inquiry']['bug_environment'] . "\n\n";
            $data['Inquiry']['text'] .= "==== 具体的な操作と症状 ====\n";
            $data['Inquiry']['text'] .= $data['Inquiry']['bug_text'] . "\n\n";
            $data['Inquiry']['text'] .= "==== UA ====\n";
            $data['Inquiry']['text'] .= $_SERVER['HTTP_USER_AGENT'] . "\n\n";
            $data['Inquiry']['text'] .= "==== IP アドレス ====\n";
            $data['Inquiry']['text'] .= $_SERVER['REMOTE_ADDR'] . "\n\n";
        }

        unset($data['Inquiry']['bug_datetime']);
        unset($data['Inquiry']['bug_url']);
        unset($data['Inquiry']['bug_environment']);
        unset($data['Inquiry']['bug_text']);

        $model = new Inquiry();
        $model->set($data);
        if ($model->validates()) {
            $res = $model->apiPost($model->toArray());
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
