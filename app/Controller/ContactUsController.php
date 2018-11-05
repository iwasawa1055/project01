<?php

App::uses('MinikuraController', 'Controller');
App::uses('ZedeskModel', 'Model');

class ContactUsController extends MinikuraController
{
    const MODEL_NAME = 'ContactUs';
    const MODEL_NAME_ZENDESK = 'ZendeskModel';
    const MODEL_NAME_ANNOUNCEMENT = 'ContactUs_Announcement';
    //const PER_PAGE_COUNT = 10;

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
        $this->loadModel(self::MODEL_NAME_ZENDESK);
    }


    /**
     * index
     * お問い合わせ一覧
     * (ログイン済みユーザー)
     */
    public function index()
    {
        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        //$current_page = isset($_GET['page']) ? $_GET['page'] : null;
        $customer_data = $this->Customer->getInfo();
//CakeSession::delete('app.data.zendesk_user');
        $zendesk_user = !empty(CakeSession::read('app.data.zendesk_user')) ? CakeSession::read('app.data.zendesk_user') : $this->ZendeskModel->getUser($customer_data);

//debug($zendesk_user);exit;
        if (!empty($zendesk_user)) {
            CakeSession::write('app.data.zendesk_user', $zendesk_user);

            // チケット取得
            $ticket_param = [
                'zendesk_user_id' => $zendesk_user['id'],
                //'per_page' => self::PER_PAGE_COUNT,
                //'page' => $current_page,
            ];
            $response = $this->ZendeskModel->getTicketList($ticket_param);
//debug($response);exit;
            $ticket_list = [];
            $ticket_list['tickets'] = $this->paginate($response['tickets']);
            $ticket_list['count'] = $response['count'];
//debug($ticket_list);exit;
            $this->set('ticket_list', $ticket_list);
        }
//pagenation注意
//セットする
    }


   /**
     * add
     * 新規お問い合わせ作成
     */
    public function add()
    {
        // お知らせからの場合は内容を取得
        $id = $this->params['id'];
        $this->set('id', $id);
        $data = $this->getAnnouncement($id);
        $this->set('announcement', $data);

//debug($this->Customer);exit;
//zendeskユーザー取得
//zendeskチケット取得(ユーザーあれば)
//pagenation注意
//セットする
    }


//    /**
//     *
//     */
//    public function add()
//    {
//        $isBack = Hash::get($this->request->query, 'back');
//        if ($isBack) {
//            $this->request->data = [self::MODEL_NAME => CakeSession::read(self::MODEL_NAME)];
//        }
//        CakeSession::delete(self::MODEL_NAME);
//
//        // お知らせからの場合は内容を取得
//        $id = $this->params['id'];
//        $this->set('id', $id);
//        $data = $this->getAnnouncement($id);
//        $this->set('announcement', $data);
//    }

/** * */
    public function confirm()
    {
        // お知らせからの場合は内容を取得
        $id = $this->params['id'];
        $this->set('id', $id);
        $data = $this->getAnnouncement($id);
        $this->set('announcement', $data);

        // 入力フォーム内容を取得
        $model = $this->Customer->getContactModel($this->request->data[self::MODEL_NAME]);
        $originalData = $model->toArray();
//debug($this->request->data);
//debug($model);exit;
//debug($this->Customer);exit;
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
        $customer_data = $this->Customer->getInfo();
//debug($customer_data);exit;
        $param = [
            'name' => $customer_data['lastname'].$customer_data['firstname'],
            'email' => $customer_data['email'],
            'customers_id' => $customer_data['customer_id'],
            'customers_cd' => $customer_data['customer_cd']
        ];
        $user_data = $this->ZendeskModel->postUser($param);
        if (empty($user_data)) {
            new AppInternalCritical(AppE::FUNC . ' postUser Failed');
        }

debug($user_data);exit;



        $data = CakeSession::read(self::MODEL_NAME);
        $announcement = CakeSession::read(self::MODEL_NAME_ANNOUNCEMENT);

//        CakeSession::delete(self::MODEL_NAME);
//        CakeSession::delete(self::MODEL_NAME_ANNOUNCEMENT);

        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }
//debug($data);
        $data = $this->ContactUs->editText($data);
//debug(CONTACTUS_DIVISION[$data['division']]);
//debug(CONTACTUS_DIVISION);

//        // 仮登録ユーザの場合、後ろにカスタマーIDをつける
//        if ($this->Customer->isEntry()) {
//            $data['text'] .= "\n\nお客様番号: {$this->Customer->getInfo()['customer_id']}\n\n";
//            $data['email'] = $this->Customer->getInfo()['email'];
//        }
//
//        $model = $this->Customer->getContactModel($data);
//
//        if ($model->validates()) {
//            if (!empty($announcement)) {
//                // お知らせの内容を追加
//                $model->data[$model->getModelName()]['text'] .= $this->setPostText($announcement);
//            }
//
//            // リクエスト本体には例外処理を入れる from 2016.6.22
//            try {
//                $res = $model->apiPost($model->toArray());
//            } catch (Exception $e) {
//                $this->Flash->set(__('お問い合わせの送信に失敗しました。'));
//                return $this->redirect(['action' => 'add']);
//            }
//
//            if (!empty($res->error_message)) {
//                $this->Flash->set($res->error_message);
//                return $this->redirect(['action' => 'add']);
//            }
//            
//            // ユーザー環境値登録
//            $this->Customer->postEnvAuthed();
//
//        } else {
//            $this->Flash->set(__('empty_session_data'));
//            return $this->redirect(['action' => 'add']);
//        }
    }

    private function getAnnouncement($id)
    {
        if (empty($id)) {
            return [];
        }
        $o = new Announcement();
        return $o->apiGetResultsFind([], ['announcement_id' => $id]);
    }
//
//    private function setPostText($announcement)
//    {
//        return $test = <<< EOF
//
//
//お知らせ内容：
//お知らせID：{$announcement['announcement_id']}
//
//EOF;
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
//    }
}
