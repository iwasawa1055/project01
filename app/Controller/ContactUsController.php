<?php

App::uses('MinikuraController', 'Controller');

class ContactUsController extends MinikuraController
{
    const MODEL_NAME = 'ContactUs';
    const MODEL_NAME_ANNOUNCEMENT = 'ContactUs_Announcement';

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
        if ($model->validates()) {
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

        if ($data['division'] === CONTACT_DIVISION_BUG) {
            $data['text'] .= "\n\n\n";
            $data['text'] .= "==== 不具合発生日時 ====\n";
            $data['text'] .= $data['bug_datetime'] . "\n\n";
            $data['text'] .= "==== 不具合発生 URL ====\n";
            $data['text'] .= $data['bug_url'] . "\n\n";
            $data['text'] .= "==== ご利用環境（OS・ブラウザ）====\n";
            $data['text'] .= $data['bug_environment'] . "\n\n";
            $data['text'] .= "==== 具体的な操作と症状 ====\n";
            $data['text'] .= $data['bug_text'] . "\n\n";
            $data['text'] .= "==== UA ====\n";
            $data['text'] .= $_SERVER['HTTP_USER_AGENT'] . "\n\n";
            $data['text'] .= "==== IP アドレス ====\n";
            $data['text'] .= $_SERVER['REMOTE_ADDR'] . "\n\n";
        }

        unset($data['bug_datetime']);
        unset($data['bug_url']);
        unset($data['bug_environment']);
        unset($data['bug_text']);

        $model = $this->Customer->getContactModel($data);
        if ($model->validates()) {
            if (!empty($announcement)) {
                // お知らせの内容を追加
                $model->data[$model->getModelName()]['text'] .= $this->setPostText($announcement);
            }

            $res = $model->apiPost($model->toArray());
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
