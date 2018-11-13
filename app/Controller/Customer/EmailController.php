<?php

App::uses('MinikuraController', 'Controller');
App::uses('ZedeskModel', 'Model');

class EmailController extends MinikuraController
{
    const MODEL_NAME = 'CustomerEmail';
    const MODEL_NAME_ZENDESK = 'ZendeskModel';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);

        $this->set('current_email', $this->Customer->getInfo()['email']);
    }

    /**
     * 入力
     */
    public function customer_edit()
    {
        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = [self::MODEL_NAME => CakeSession::read(self::MODEL_NAME)];
        }
        CakeSession::delete(self::MODEL_NAME);
    }

    /**
     * 確認
     */
    public function customer_confirm()
    {
        $model = $this->Customer->getEmailModel($this->request->data[self::MODEL_NAME]);
        if ($model->validates()) {
            CakeSession::write(self::MODEL_NAME, $model->toArray());
        } else {
            $this->set('validErrors', $model->validationErrors);
            return $this->render('customer_edit');
        }
    }

    /**
     * 完了
     * ※ zendeskユーザーがいる場合は更新
     */
    public function customer_complete()
    {
        $data = CakeSession::read(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME);

        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'edit']);
        }

        $model = $this->Customer->getEmailModel($data);
        if ($model->validates()) {
             // api
            $res = $model->apiPatch($model->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'edit']);
            }

            // zendesk
            $original_customer_data = $this->Customer->getInfo();
            $this->loadModel(self::MODEL_NAME_ZENDESK);
            $zendesk_user = $this->ZendeskModel->getUserByEmail([
                'email' => $original_customer_data['email'],
            ]);

            if (!empty($zendesk_user)) {
                $put_user_params = [
                    'zendesk_user_id' => $zendesk_user['id'],
                    'email' => $data['email'],
                ];
                // メールアドレス更新
                $user_response = $this->ZendeskModel->putUserEmail($put_user_params);
                if ($user_response === false) {
                    new AppInternalCritical(AppE::FUNC . ' putUserEmail Failed', 500);
                }
            }
 
            $this->Customer->reloadInfo();
            $this->set('email', $model->toArray()['email']);
        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'edit']);
        }
    }
}
