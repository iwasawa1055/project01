<?php

App::uses('AppController', 'Controller');

class ContactUsController extends AppController
{
    const MODEL_NAME = 'ContactUs';
    const MODEL_NAME_ANNOUNCEMENT = 'Announcement';

    /**
     * 制御前段処理
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
        $this->loadModel($this::MODEL_NAME);
        $this->loadModel($this::MODEL_NAME_ANNOUNCEMENT);
    }

    /**
     * 
     */
    public function add()
    {
        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = CakeSession::read($this::MODEL_NAME);
        }
        CakeSession::delete($this::MODEL_NAME);

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

        $this->ContactUs->set($this->request->data);
        if ($this->ContactUs->validates()) {
            CakeSession::write($this::MODEL_NAME, $this->ContactUs->data);
            CakeSession::write($this::MODEL_NAME_ANNOUNCEMENT, $data);
        } else {
            return $this->render('add');
        }
    }

    /**
     * 
     */
    public function complete()
    {
        $data = CakeSession::read($this::MODEL_NAME);
        CakeSession::delete($this::MODEL_NAME);
        $announcement = CakeSession::read($this::MODEL_NAME_ANNOUNCEMENT);
        CakeSession::delete($this::MODEL_NAME_ANNOUNCEMENT);

        if (empty($data)) {
            // TODO:
            $this->Session->setFlash('try again');
            return $this->redirect(['action' => 'add']);
        }

        $this->ContactUs->set($data);
        if ($this->ContactUs->validates()) {

            if (!empty($announcement)) {
                // お知らせの内容を追加
                $this->ContactUs->data[$this::MODEL_NAME]['text'] .= $this->setPostText($announcement);
            }

            $res = $this->ContactUs->apiPost($this->ContactUs->toArray());
            if (!empty($res->error_message)) {
                // TODO:
                $this->Session->setFlash('try again');
                return $this->redirect(['action' => 'add']);
            }
        } else {
            // TODO:
            $this->Session->setFlash('try again');
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
