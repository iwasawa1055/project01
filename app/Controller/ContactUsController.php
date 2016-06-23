<?php

App::uses('MinikuraController', 'Controller');

class ContactUsController extends MinikuraController
{
    const MODEL_NAME = 'ContactUs';
    const MODEL_NAME_ANNOUNCEMENT = 'ContactUs_Announcement';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
    }

    /**
     *
     */
    public function add()
    {
        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = [self::MODEL_NAME => CakeSession::read(self::MODEL_NAME)];
        }
        CakeSession::delete(self::MODEL_NAME);

        // お知らせからの場合は内容を取得
        $id = $this->params['id'];
        $this->set('id', $id);
        $data = $this->getAnnouncement($id);
        $this->set('announcement', $data);
    }

    /**
     *
     */
    public function confirm()
    {
        // お知らせからの場合は内容を取得
        $id = $this->params['id'];
        $this->set('id', $id);
        $data = $this->getAnnouncement($id);
        $this->set('announcement', $data);
        $model = $this->Customer->getContactModel($this->request->data[self::MODEL_NAME]);

        $originalData = $model->toArray();
        // 不具合報告を問い合わせ内容とマージしてチェックする
        $checkData = $this->ContactUs->editText($model->toArray());
        $model->set($checkData);

        if ($model->validates()) {
            // 戻るなどに対応するため、セッションに保存する前に不具合報告のマージを解除する
            $model->set($originalData);
            CakeSession::write(self::MODEL_NAME, $model->toArray());
            CakeSession::write(self::MODEL_NAME_ANNOUNCEMENT, $data);
        } else {
            $this->set('validErrors', $model->validationErrors);
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
        $announcement = CakeSession::read(self::MODEL_NAME_ANNOUNCEMENT);
        CakeSession::delete(self::MODEL_NAME_ANNOUNCEMENT);

        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }

        $data = $this->ContactUs->editText($data);

        // 仮登録ユーザの場合、後ろにカスタマーIDをつける
        if ($this->Customer->isEntry()) {
            $data['text'] .= "\n\nお客様番号: {$this->Customer->getInfo()['customer_id']}\n\n";
            $data['email'] = $this->Customer->getInfo()['email'];
        }

        $model = $this->Customer->getContactModel($data);

        if ($model->validates()) {
            if (!empty($announcement)) {
                // お知らせの内容を追加
                $model->data[$model->getModelName()]['text'] .= $this->setPostText($announcement);
            }

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
            $this->Customer->postEnvAuthed();

        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }
    }

    private function getAnnouncement($id)
    {
        if (empty($id)) {
            return [];
        }

        $o = new Announcement();
        return $o->apiGetResultsFind([], ['announcement_id' => $id]);
    }

    private function setPostText($announcement)
    {
        return $test = <<< EOF


お知らせ内容：
お知らせID：{$announcement['announcement_id']}

EOF;
//         return $test = <<< EOF
// 
// 
// お知らせ内容：
// タイトル：{$announcement['title']}
// 日付：{$announcement['date']}
// 
// 本文：
// {$announcement['text']}
// EOF;
    }
}
