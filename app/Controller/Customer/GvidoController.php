<?php

App::uses('MinikuraController', 'Controller');

class GvidoController extends MinikuraController
{
    protected $checkLogined = false;
    public $layout = 'gvido';
    public $alliance_cd = 'gvido';
    public $modelName = null;
    const MODEL_NAME_CUSTOMER_REGIST_INFO = 'CustomerRegistInfo';
    const MODEL_NAME_EMAIL = 'Email';
    const MODEL_NAME_PAYMENT_GMO_CREDIT_CARD_CHECK = 'PaymentGMOCreditCardCheck';

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME_CUSTOMER_REGIST_INFO);
    }

    // ここでLPからのアクセスを制御する
    // protected function isAccessDeny()
    // {
    //     if ($this->Customer->isEntry() && $this->action === 'customer_edit') {
    //         // 個人(仮登録)：変更不可
    //         return true;
    //     } elseif (!$this->Customer->isEntry() && $this->action === 'customer_add') {
    //         // 本登録：登録不可
    //         return true;
    //     }
    //     return false;
    // }

    public function customer_delete_session()
    {
        $this->autoRender = false;
        CakeSession::delete(self::MODEL_NAME_CUSTOMER_REGIST_INFO);
        echo "セッションを削除しました。";
    }

    public function customer_add()
    {
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // 初期表示
        if ($this->request->is('get')) {
            // セッションから入力値を取得
            $data = CakeSession::read(self::MODEL_NAME_CUSTOMER_REGIST_INFO);
            $this->request->data = [self::MODEL_NAME_CUSTOMER_REGIST_INFO => $data];

            if (empty($this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO])) {
                $this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO] = [
                    'alliance_cd' => $this->alliance_cd,
                ];
            }
        // 確認へ遷移する場合
        } elseif ($this->request->is('post')) {
            // 生年月日を結合
            $this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['birth'] = implode('-', [
                $this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['birth_year'],
                // ゼロ埋め
                sprintf('%02d', $this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['birth_month']),
                // ゼロ埋め
                sprintf('%02d', $this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['birth_day']),
            ]);

            // 電話番号を半角変換
            $this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['tel1'] = self::_wrapConvertKana($this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['tel1']);

            // モデルに入力値をセット
            $this->CustomerRegistInfo->set($this->request->data);

            // セッションに入力値を保存
            CakeSession::write(self::MODEL_NAME_CUSTOMER_REGIST_INFO, $this->CustomerRegistInfo->toArray());

            // バリデーションエラー確認
            if ($this->CustomerRegistInfo->validates() === false) {
                return $this->render('customer_add');
            }

            // 既存ユーザチェック
            $this->loadModel(self::MODEL_NAME_EMAIL);
            $result = $this->Email->getEmail(array('email' => $this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['email']));
            if ($result->status === "0") {
                $this->CustomerRegistInfo->validationErrors['email'][0] = '登録済みのメールアドレスです';
                return $this->render('customer_add');
            }

            // エラーがない場合は確認画面にリダイレクト
            return $this->redirect('/customer/gvido/confirm');
        }
    }

    public function customer_confirm()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
            'Gvido/customer_add',
            'Gvido/customer_confirm',
            ], true) === false ) {
            $this->redirect(['controller' => 'gvido', 'action' => 'customer_add']);
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // セッションから入力値を取得しviewに渡す
        $this->set(self::MODEL_NAME_CUSTOMER_REGIST_INFO, CakeSession::read(self::MODEL_NAME_CUSTOMER_REGIST_INFO));
    }

    public function customer_card()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
            'Gvido/customer_confirm',
            'Gvido/customer_card',
            ], true) === false ) {
            $this->redirect(['controller' => 'gvido', 'action' => 'customer_add']);
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        if ($this->request->is('post')) {
            $params = [
                'security_cd' => filter_input(INPUT_POST, 'securitycode'),
                'gmo_token' => filter_input(INPUT_POST, 'gmo_token'),
            ];

            if (empty($params['gmo_token'])) {
                $this->Flash->validation('クレジットカード情報を再度入力してください。', ['key' => 'gmo_token']);
            }

            $this->loadModel(self::MODEL_NAME_PAYMENT_GMO_CREDIT_CARD_CHECK);
            $res = $this->PaymentGMOCreditCardCheck->getCreditCardCheck(['gmo_token' => filter_input(INPUT_POST, 'gmo_token_for_check')]);

            if (!empty($res->error_message)) {
                $this->set('error_message', $res->error_message . 'エラーコード:' . $res->message);
                return $this->render('customer_card');
            }

            // エラーがない場合は確認画面にリダイレクト
            return $this->redirect('/customer/gvido/complete');
        }
    }

    public function customer_complete()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
            'Gvido/customer_card',
            ], true) === false ) {
            $this->redirect(['controller' => 'gvido', 'action' => 'customer_add']);
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        if ($this->request->is('post')) {
            // エラーがない場合は確認画面にリダイレクト
            return $this->redirect('/customer/gvido/confirm');
        }
    }
}
