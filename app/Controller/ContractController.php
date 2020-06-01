<?php

App::uses('MinikuraController', 'Controller');

class ContractController extends MinikuraController
{
    /** model */
    const MODEL_NAME_REGIST = 'CustomerRegistInfo';

    /**
     *
     */
    public function index()
    {
        $data = [];
        $model = $this->Customer->getInfoGetModel();
        $res = $model->apiGet();
        if (!empty($res->error_message)) {
            $this->Flash->set($res->error_message);
        } else {
            $data = $res->results[0];
        }

        // SNS連携
        $data['facebook_flg'] = false;
        if ($this->Customer->isFacebook()) {
            $data['facebook_flg'] = true;
        }

        $data['google_flg'] = false;
        if ($this->Customer->isGoogle()) {
            $data['google_flg'] = true;
        }

        $this->set('data', $data);
    }

    /**
     * SNS連携
     */
    public function register_facebook()
    {

        // facebook情報
        $data = $this->request->data['FacebookUser'];

        // FB連携
        $this->loadModel('CustomerFacebook');
        $this->CustomerFacebook->set(['facebook_user_id' => $data['facebook_user_id']]);
        $res = $this->CustomerFacebook->regist();

        if ($res->status == 0) {
            if ($res->message == 'Record Already - facebook_user_id') {
                $this->Flash->validation('お客様のFacebookアカウントは既に連携が登録されています。', ['key' => 'facebook_error']);
            } elseif ($res->message == 'Record Already - token') {
                $this->Flash->validation('お客様のMinikuraアカウントは既にFacebook連携登録されています。', ['key' => 'facebook_error']);
            } else {
                $this->Flash->validation('Facebook連携に失敗しました。', ['key' => 'facebook_error']);
            }
        } else {
            // Facebook用access_tokenを保存
            CakeSession::write(CustomerLogin::SESSION_FACEBOOK_ACCESS_KEY, $data['access_token']);
        }

        return $this->redirect(['controller' => 'contract', 'action' => 'index']);
    }

    /**
     * SNS連携解除
     */
    public function unregister_facebook()
    {
        $this->loadModel('CustomerFacebook');
        $res = $this->CustomerFacebook->apiGet();

        if (isset($res->results[0])) {
            $this->CustomerFacebook->apiDelete(['facebook_user_id' => $res->results[0]['facebook_user_id']]);
        }

        return $this->redirect(['controller' => 'contract', 'action' => 'index']);
    }

    /**
     * SNS連携(google)
     */
    public function register_google()
    {
        // google情報
        $data = $this->request->data;

        $this->loadModel('GoogleModel');
        $data = $this->GoogleModel->getUserInfo_login($data);

        // Google連携
        $this->loadModel('CustomerGoogle');
        $this->CustomerGoogle->set(['google_user_id' => $data['CustomerLoginGoogle']['google_user_id']]);
        $res = $this->CustomerGoogle->regist();

        if ($res->status == 0) {
            if ($res->message == 'Record Already - google_user_id') {
                $this->Flash->validation('お客様のGoogleアカウントは既に連携が登録されています。', ['key' => 'google_error']);
            } elseif ($res->message == 'Record Already - token') {
                $this->Flash->validation('お客様のMinikuraアカウントは既にGoogle連携登録されています。', ['key' => 'google_error']);
            } else {
                $this->Flash->validation('Google連携に失敗しました。', ['key' => 'google_error']);
            }
        } else {
            // Google用access_tokenを保存
            CakeSession::write(CustomerLogin::SESSION_GOOGLE_ACCESS_KEY, $data['CustomerLoginGoogle']['access_token']);
        }

        return $this->redirect(['controller' => 'contract', 'action' => 'index']);
    }

    /**
     * SNS連携解除(google)
     */
    public function unregister_google()
    {
        $this->loadModel('CustomerGoogle');
        $res = $this->CustomerGoogle->apiGet();

        if (isset($res->results[0])) {
            $this->CustomerGoogle->apiDelete(['google_user_id' => $res->results[0]['google_user_id']]);
        }

        return $this->redirect(['controller' => 'contract', 'action' => 'index']);
    }
}
