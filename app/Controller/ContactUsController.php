<?php

App::uses('MinikuraController', 'Controller');

class ContactUsController extends MinikuraController
{
    const MODEL_NAME = 'ContactUs';
    const MODEL_NAME_ANNOUNCEMENT = 'Announcement';
    const MODEL_NAME_ENV = 'CustomerEnvAuthed';

    public $components = ['ContactUs'];

    /**
     * 制御前段処理
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->ContactUs->init($this->customer->token['division']);
        $this->loadModel(self::MODEL_NAME_ANNOUNCEMENT);
        $this->loadModel(self::MODEL_NAME_ENV);
    }

    /**
     *
     */
    public function add()
    {
        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = CakeSession::read(self::MODEL_NAME);
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

        $model = $this->ContactUs->model($this->request->data[self::MODEL_NAME]);
        if ($model->validates()) {
            CakeSession::write(self::MODEL_NAME, $model->data[$model->getModelName()]);
            CakeSession::write(self::MODEL_NAME_ANNOUNCEMENT, $data);
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
        $announcement = CakeSession::read(self::MODEL_NAME_ANNOUNCEMENT);
        CakeSession::delete(self::MODEL_NAME_ANNOUNCEMENT);

        if (empty($data)) {
            // TODO:
            $this->Flash->set('try again');
            return $this->redirect(['action' => 'add']);
        }

        $model = $this->ContactUs->model($data);
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
            $this->CustomerEnvAuthed->apiPostEnv($this->customer->getInfo()['email']);

        } else {
            // TODO:
            $this->Flash->set('try again');
            return $this->redirect(['action' => 'add']);
        }
    }

    private function getAnnouncement($id)
    {
        if (empty($id)) {
            return [];
        }

        return $this->Announcement->apiGetResultsFind([], ['announcement_id' => $id]);
    }

    private function setPostText($announcement)
    {
        return $test = <<< EOF


お知らせ内容：
{$announcement['title']}
{$announcement['date']}

お知らせID：{$announcement['announcement_id']}

{$announcement['text']}
EOF;
    }
}
