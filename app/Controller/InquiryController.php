<?php

App::uses('MinikuraController', 'Controller');
App::uses('ZedeskModel', 'Model');
App::uses('ZedeskInquiry', 'Model');

class InquiryController extends MinikuraController
{
    const MODEL_NAME_ZENDESK = 'ZendeskModel';
    const MODEL_NAME_ZENDESK_INQUIRY = 'ZendeskInquiry';

    // ログイン不要なページ
    protected $checkLogined = false;

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        // ログイン中はContactUsへ
        if ($this->Customer->isLogined()) {
            return $this->redirect(['controller' => 'contact_us', 'action' => 'index', 'customer' => false]);
        }
        $this->loadModel(self::MODEL_NAME_ZENDESK);
        $this->loadModel(self::MODEL_NAME_ZENDESK_INQUIRY);
    }


    /**
     * add
     *     新規お問い合わせ作成(ルートインデックス)
     *     (未ログインユーザー)
     */
    public function add()
    {
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $isBack = Hash::get($this->request->query, 'back');
        if ($isBack) {
            $this->request->data = CakeSession::read(self::MODEL_NAME_ZENDESK_INQUIRY);
        }
        CakeSession::delete(self::MODEL_NAME_ZENDESK_INQUIRY);
    }


   /**
     * confirm
     *     お問い合わせ確認
     */
    public function confirm()
    {
        if (in_array(CakeSession::read('app.data.session_referer'), ['Inquiry/add', 'Inquiry/confirm'], true) === false) {
            $this->redirect('/');
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // 入力フォーム内容を取得
        $inquiry_params = $this->request->data;
        $this->ZendeskInquiry->set($inquiry_params);
        CakeSession::write(self::MODEL_NAME_ZENDESK_INQUIRY, $inquiry_params);

        // validation
        if ($this->ZendeskInquiry->validates() === false) {
            $this->set('validErrors', $this->ZendeskInquiry->validationErrors);
            return $this->render('add');
        }
    }


    /**
     * complete
     *     お問い合わせ・zendeskユーザー作成完了
     */
    public function complete()
    {
        if (in_array(CakeSession::read('app.data.session_referer'), ['Inquiry/confirm'], true) === false) {
            $this->redirect('/');
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // お問い合わせ内容
        $inquiry_params = [];
        $inquiry_params = CakeSession::read(self::MODEL_NAME_ZENDESK_INQUIRY)['ZendeskInquiry'];
        if (empty($inquiry_params)) {
            $this->redirect(['action' => 'add']);
        }

        // 同一メールアドレスチェック（非会員時問い合わせユーザー処理）
        $zendesk_user = $this->ZendeskModel->getUserByEmail([
            'email' => $inquiry_params['email'],
        ]);

        $customer_params = [];
        $customer_params = [
            'name' => $inquiry_params['lastname'].' '.$inquiry_params['firstname'],
            'email' => $inquiry_params['email'],
            'customer_id' => '',
            'customer_cd' => ''
        ];

        // 新規zendeskユーザー作成
        if (empty($zendesk_user)) {
            $zendesk_user = $this->ZendeskModel->postUser($customer_params);
            if (empty($zendesk_user)) {
                $error = $this->ZendeskModel->getError();
                if (isset($error["description"]) && ($error["description"] == "Record validation errors")) {
                    if (isset($error["details"])) {
                        $message = "";
                        foreach ($error["details"] as $e) {
                            $message .= $e[0]["description"] . " ";
                        }
                        $this->Flash->set($message);
                        $this->redirect('/inquiry/add?back=true');
                    }
                }
                new AppInternalCritical(AppE::FUNC . ' putUser Failed', 500);
            }
        }

        // 不具合情報の場合 内容マージ
        $inquiry_params = $this->ZendeskInquiry->editContactUsComment($inquiry_params);

        // 未ログインユーザー識別コメント
        $inquiry_params['comment'] .= "\n\n"."※ ログインしていないお客様からのお問い合わせです。"."\n";

        $ticket_params = [
            'subject' => $customer_params['name'] . '様からのお問い合わせ',
            'body' => $inquiry_params['comment'],
            'tags' => INQUIRY_DIVISION[$inquiry_params['division']],
            'zendesk_user_id' => $zendesk_user['id'],
        ];

        // zendeskチケット作成
        $results = $this->ZendeskModel->postTicket($ticket_params);
        if ($results === false) {
            $this->Flash->set(__('ケース作成に失敗しました'));
            return $this->redirect(['action' => 'add']);
        }
        CakeSession::delete(self::MODEL_NAME_ZENDESK_INQUIRY);
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

}
