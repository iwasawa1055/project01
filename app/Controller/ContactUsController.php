<?php

App::uses('MinikuraController', 'Controller');
App::uses('ZedeskModel', 'Model');
App::uses('ZedeskContactUs', 'Model');
App::uses('Announcement', 'Model');
App::uses('Billing', 'Model');

class ContactUsController extends MinikuraController
{
    const MODEL_NAME_ZENDESK = 'ZendeskModel';
    const MODEL_NAME_ZENDESK_CONTACT_US = 'ZendeskContactUs';
    const MODEL_NAME_ANNOUNCEMENT = 'Announcement';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME_ZENDESK);
        $this->loadModel(self::MODEL_NAME_ZENDESK_CONTACT_US);
        $this->loadModel(self::MODEL_NAME_ANNOUNCEMENT);
    }


    /**
     * index
     *     お問い合わせ一覧
     *     (ログイン済みユーザー)
     */
    public function index()
    {
        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $customer_data = $this->Customer->getInfo();
        $zendesk_user = !empty(CakeSession::read('app.data.contact_us.zendesk_user')) ? CakeSession::read('app.data.contact_us.zendesk_user') : $this->ZendeskModel->getUser($customer_data);

        $ticket_list = [];
        if (!empty($zendesk_user)) {
            CakeSession::write('app.data.contact_us.zendesk_user', $zendesk_user);

            // チケット取得
            $ticket_param = [
                'zendesk_user_id' => $zendesk_user['id'],
            ];
            $response = $this->ZendeskModel->getTicketList($ticket_param);

            if (!empty($response['tickets'])) {
                $ticket_list['tickets'] = $this->paginate($response['tickets']);
                $ticket_list['count'] = $response['count'];
            } else {
                $ticket_list['tickets'] = [];
                $ticket_list['count'] = 0;
            }
        } else {
            $ticket_list['count'] = 0;
        }
        $this->set('ticket_list', $ticket_list);
    }


   /**
     * detail
     *     お問い合わせ詳細
     */
    public function detail()
    {
        if (in_array(CakeSession::read('app.data.session_referer'), ['ContactUs/index', 'ContactUs/detail', 'ContactUs/add'], true) === false) {
            $this->redirect('/');
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $ticket_params = [
           'ticket_id' => $this->request->is('get') ? $this->request->query('ticket_id') : $this->request->data['ZendeskContactUs']['ticket_id'],
        ];
        $ticket_data = $this->ZendeskModel->getTicketByTicketId($ticket_params);

        //zenndeskユーザーいるか確認
        $zendesk_user = CakeSession::read('app.data.contact_us.zendesk_user');
        // 自身のチケットかチェック
        if (empty($ticket_data) || $ticket_data['requester_id'] !== $zendesk_user['id']) {
            $this->Flash->set(__(' 該当するお問い合わせ詳細がありません'));
            return $this->redirect(['action' => 'index']);
        }
        // チケットコメント取得
        $comment_data = $this->ZendeskModel->getTicketCommentByTicketId($ticket_params);

        $this->set('ticket_data', $ticket_data);
        $this->set('comment_data', $comment_data);

        // メッセージ送信処理
        if ($this->request->is('post')) {
            $post_params = $this->request->data;

            // お問い合わせステータスが終了の場合はエラーでdetailへ戻す
            if ($ticket_data['status'] === 'closed') {
                $this->Flash->validation("このお問い合わせは有効期限が切れております。<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;お問い合わせに関してご質問があるお客様は、こちらの「新規お問い合わせ」よりお問い合わせください。", ['key' => 'zendesk_error']);
                $post_params['ZendeskContactUs']['division'] = array_search($ticket_data['tags'][0], CONTACTUS_DIVISION);
                CakeSession::write(self::MODEL_NAME_ZENDESK_CONTACT_US, $post_params);
                $this->redirect('/contact_us/add?ticket_id=' . $ticket_params['ticket_id'] . '&back=true');
            }

            $this->ZendeskContactUs->set($post_params);
            // remove validation required
            $this->ZendeskContactUs->validator()->remove('division');
            // validation
            if ($this->ZendeskContactUs->validates() === false) {
                return $this->render('detail');
            }
            $comment_params = [
                'zendesk_user_id' => $zendesk_user['id'],
                'ticket_id' => $post_params['ZendeskContactUs']['ticket_id'],
                'comment' => $post_params['ZendeskContactUs']['comment'],
            ];
            // チケット更新API
            $response = $this->ZendeskModel->putTicketComment($comment_params);
            if (empty($response)) {
                new AppInternalCritical(AppE::FUNC . ' putTicketComment Failed', 500);
            }
            $this->redirect('/contact_us/detail?ticket_id=' . $ticket_params['ticket_id']);
        }
    }


   /**
     * add
     *     新規お問い合わせ作成
     */
    public function add()
    {
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = CakeSession::read(self::MODEL_NAME_ZENDESK_CONTACT_US);
        } else {
            CakeSession::delete(self::MODEL_NAME_ZENDESK_CONTACT_US);
        }

        // お知らせからの場合は内容を取得
        $id = $this->params['id'];
        $this->set('id', $id);
        $data = $this->getAnnouncement($id);
        $this->set('announcement', $data);

        // クローズしたチケットを元にした新規問い合わせ対応
        if (!empty($this->request->query['ticket_id'])) {
            $ticket_id = $this->request->query['ticket_id'];
            $this->set('ticket_id', $ticket_id);
        }

    }


   /**
     * confirm
     *     お問い合わせ確認
     */
    public function confirm()
    {
        if (in_array(CakeSession::read('app.data.session_referer'), ['ContactUs/index', 'ContactUs/detail', 'ContactUs/add', 'ContactUs/confirm', 'Announcement/detail'], true) === false) {
            $this->redirect('/');
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // お知らせからの場合は内容を取得
        $id = $this->params['id'];
        $this->set('id', $id);
        $announcement_data = $this->getAnnouncement($id);
        if (!empty($announcement_data)) {
            CakeSession::write('app.data.contact_us.announcement_data', $announcement_data);
        }
        $this->set('announcement', $announcement_data);

        // クローズしたチケットを元にした新規問い合わせ対応
        if (!empty($this->request->data['ZendeskContactUs']['ticket_id'])) {
            $ticket_params = [
               'ticket_id' => $this->request->data['ZendeskContactUs']['ticket_id'],
            ];
            $ticket_data = $this->ZendeskModel->getTicketByTicketId($ticket_params);

            $customer_data = $this->Customer->getInfo();
            // 参照チケットが本人のものか確認
            $zendesk_user = !empty(CakeSession::read('app.data.contact_us.zendesk_user')) ? CakeSession::read('app.data.contact_us.zendesk_user') : $this->ZendeskModel->getUser($customer_data);
            if (empty($ticket_data) || $ticket_data['requester_id'] !== $zendesk_user['id']) {
                $this->Flash->set(__(' 該当するお問い合わせ詳細がありません'));
                return $this->redirect(['action' => 'index']);
            }
        }

        // 入力フォーム内容を取得
        $contact_us_params = $this->request->data;
        $this->ZendeskContactUs->set($contact_us_params);
        CakeSession::write(self::MODEL_NAME_ZENDESK_CONTACT_US, $contact_us_params);

        // validation
        if ($this->ZendeskContactUs->validates() === false) {
            $this->set('validErrors', $this->ZendeskContactUs->validationErrors);
            return $this->render('add');
        }

        // Entryユーザー処理
        if ($this->Customer->isEntry()) {
            //Entry用validation項目追加処理
            $this->addEntryValidationRule();
            if ($this->ZendeskContactUs->validates() === false) {
                $this->set('validErrors', $this->ZendeskContactUs->validationErrors);
                return $this->render('add');
            }
        }
    }


    /**
     * complete
     *     お問い合わせ・zendeskユーザー作成完了
     */
    public function complete()
    {
        if (in_array(CakeSession::read('app.data.session_referer'), ['ContactUs/confirm'], true) === false) {
            $this->redirect('/');
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // お問い合わせ内容
        $contact_us_params = [];
        $contact_us_params = CakeSession::read(self::MODEL_NAME_ZENDESK_CONTACT_US)['ZendeskContactUs'];
        if (empty($contact_us_params)) {
            $this->redirect(['action' => 'add']);
        }

        $customer_data = $this->Customer->getInfo();
        $zendesk_user = !empty(CakeSession::read('app.data.contact_us.zendesk_user')) ? CakeSession::read('app.data.contact_us.zendesk_user') : $this->ZendeskModel->getUser($customer_data);

        $customer_params = [];
        $customer_params = [
            'email' => $customer_data['email'],
            'customer_id' => $customer_data['customer_id'],
            'customer_cd' => $customer_data['customer_cd']
        ];
        // 個人ユーザーの姓名set
        if ($this->Customer->isPrivateCustomer()) {
            $customer_params['name'] = $customer_data['lastname'].' '.$customer_data['firstname'];
        }
        // Entryユーザーの姓名set
        if ($this->Customer->isEntry()) {
            $customer_params['name'] = $contact_us_params['lastname'] .' '. $contact_us_params['firstname'];
        }
        // 法人ユーザーの姓名set
        if ($this->Customer->isCorporateCustomer()) {
            $customer_params['name'] = $customer_data['company_name'] . ':' .  $customer_data['staff_name'];
        }

        // zendesk_userがいなければ作成(external_idが無いユーザー)
        if (empty($zendesk_user)) {
            // 同一メールアドレスチェック（非会員時問い合わせユーザー処理）
            $zendesk_user_data = $this->ZendeskModel->getUserByEmail([
                'email' => $customer_params['email'],
            ]);

            // 既存zendeskユーザーがいれば更新
            if (!empty($zendesk_user_data)) {
                $put_user_params = [
                    'zendesk_user_id' => $zendesk_user_data['id'],
                    'email' => $customer_params['email'],
                    'customer_id' => $customer_params['customer_id'],
                    'customer_cd' => $customer_params['customer_cd'],
                ];
                $user_response = $this->ZendeskModel->putUser($put_user_params);
                if (empty($user_response)) {
                    new AppInternalCritical(AppE::FUNC . ' putUser Failed', 500);
                }
            // 新規zendeskユーザー作成
            } else {
                $user_response = $this->ZendeskModel->postUser($customer_params);
                if (empty($user_response)) {
                    new AppInternalCritical(AppE::FUNC . ' postUser Failed', 500);
                }
            }
            $zendesk_user = $user_response;
        }

        // 不具合情報の場合 内容マージ
        $contact_us_params = $this->ZendeskContactUs->editContactUsComment($contact_us_params);

        // お知らせ内容 取得・マージ
        $announcement_params = CakeSession::read('app.data.contact_us.announcement_data');
        if (!empty($announcement_params)) {
            $contact_us_params['comment'] .= $this->ZendeskContactUs->editAnnouncementText($announcement_params);
        }

        // ログインユーザー識別コメント
        $contact_us_params['comment'] .= "\n\n"."※ マイページからのお問い合わせです。"."\n";

        // クローズチケットの場合 参照元のチケットIDをコメントに追加
        if (!empty($contact_us_params['ticket_id'])) {
            $ticket_params = [
               'ticket_id' => $contact_us_params['ticket_id']
            ];
            $ticket_data = $this->ZendeskModel->getTicketByTicketId($ticket_params);
            $follow_up_text = "以前いただいたリクエストNo.#{$ticket_data['id']}「{$ticket_data['subject']}」に対する補足コメントです";
            $contact_us_params['comment'] = $follow_up_text . "\n\n" . $contact_us_params['comment'];
        }

        $ticket_params = [
            'subject' => $customer_params['name'] . '様(' . $customer_data['customer_cd'] . ')からのお問い合わせ',
            'body' => $contact_us_params['comment'],
            'tags' => CONTACTUS_DIVISION[$contact_us_params['division']],
            'zendesk_user_id' => $zendesk_user['id'],
        ];

        // zendeskチケット作成
        // クローズ済みチケットから作成の場合
        if (!empty($contact_us_params['ticket_id'])) {
            $ticket_params['ticket_id'] = $contact_us_params['ticket_id'];
            $results = $this->ZendeskModel->postTicketWithClosedTicketId($ticket_params);
        // それ以外
        } else {
            $results = $this->ZendeskModel->postTicket($ticket_params);
        }
        if ($results === false) {
            $this->Flash->set(__('ケース作成に失敗しました'));
            return $this->redirect(['action' => 'index']);
        }
        CakeSession::delete('app.data.contact_us');
        CakeSession::delete(self::MODEL_NAME_ZENDESK_CONTACT_US);
    }

    public function as_get_contact_help()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        $this->autoRender = false;

        $result = [];

        $contact_message = $this->request->data['contact_message'];
        if (empty($contact_message)) {
            return json_encode(compact('result'));
        }

        preg_match_all('/[一-龠]+|[ァ-ヴー]+|[a-zA-Z0-9]+|[ａ-ｚＡ-Ｚ０-９]+/u', $contact_message, $matches);

        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' $matches ' . print_r($matches, true));

        if (empty($matches[0])) {
            return json_encode(compact('result'));
        }

        $search_param = array(
            'message' => implode(',', $matches[0])
        );
        $result = $this->ZendeskModel->getSearchHelpCenter($search_param);

        return json_encode(compact('result'));
    }

    /**
     * getAnnouncement
     *     メッセージ内容取得
     *     @param $string メッセージID
     *     @return $array
     */
    private function getAnnouncement($id)
    {
        if (empty($id)) {
            return [];
        }
        $o = new Announcement();
        $data = $o->apiGetResultsFind([], ['announcement_id' => $id]);
        // お知らせが請求情報の場合
        if ($data['category_id'] === ANNOUNCEMENT_CATEGORY_ID_BILLING) {
            $billing = new Billing();
            $res = $billing->apiGet([
                'announcement_id' => $id,
                'category_id' => $data['category_id']
            ]);
            if ($res->isSuccess()) {
                $data['billing'] = $res->results;
            }
        }
        return $data;
    }


    /**
     * addEntryValidationRule
     *     エントリーユーザー用お問い合わせ追加validation
     */
    private function addEntryValidationRule()
    {
        // 姓
        $this->ZendeskContactUs->validator()->add('lastname', array(
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'lastname']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'lastname', 29]
            ],
            'isNotOnlySpace' => [
                'rule' => 'isNotOnlySpace',
                'message' => ['notBlank', 'lastname']
            ],
        ));

        // 姓カナ
        $this->ZendeskContactUs->validator()->add('lastname_kana', array(
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'lastname_kana']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'lastname_kana', 29]
            ],
            'isFwKana' => [
                'rule' => 'isFwKana',
                'message' => ['isFwKana', 'lastname_kana']
            ],
        ));

        // 名
        $this->ZendeskContactUs->validator()->add('firstname', array(
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'firstname']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'firstname', 29]
            ],
            'isNotOnlySpace' => [
                'rule' => 'isNotOnlySpace',
                'message' => ['notBlank', 'firstname']
            ],
        ));

        // 名カナ
        $this->ZendeskContactUs->validator()->add('firstname_kana', array(
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'firstname_kana']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' =>  ['maxLength', 'firstname_kana', 29]
            ],
            'isFwKana' => [
                'rule' => 'isFwKana',
                'message' => ['isFwKana', 'firstname_kana']
            ],
        ));

        return;
    }
}
