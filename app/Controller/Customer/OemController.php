<?php

App::uses('MinikuraController', 'Controller');

class OemController extends MinikuraController
{
    protected $checkLogined = false;
    public $layout = 'oem';
    public $modelName = null;
    const MODEL_NAME_CUSTOMER_REGIST_INFO = 'CustomerRegistInfo';
    const MODEL_NAME_EMAIL = 'Email';

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME_CUSTOMER_REGIST_INFO);
    }

    public function customer_add()
    {
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        //紹介コードの取得
        $alliance_cd = '';
        if (isset($_GET['alliance_cd'])) {
            $alliance_cd = $_GET['alliance_cd'];
        } elseif (CakeSession::read('app.data.alliance_cd')) {
            $alliance_cd = CakeSession::read('app.data.alliance_cd');
        }

        //紹介コードの確認
        if ($alliance_cd === '') {
            return $this->redirect(Configure::read('site.static_content_url') . '/oem?coupon_error=1#coupon');
        }

        CakeSession::Write('app.data.alliance_cd', $alliance_cd);

        // 初期表示
        if ($this->request->is('get')) {
            // セッションから入力値を取得
            $data = CakeSession::read(self::MODEL_NAME_CUSTOMER_REGIST_INFO);
            $this->request->data = [self::MODEL_NAME_CUSTOMER_REGIST_INFO => $data];

            if (empty($this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO])) {
                $this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO] = [
                    'alliance_cd' => $alliance_cd,
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
            return $this->redirect('/customer/oem/confirm');
        }
    }

    public function customer_confirm()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
            'Oem/customer_add',
            'Oem/customer_confirm',
            ], true) === false ) {
            $this->redirect(['controller' => 'oem', 'action' => 'customer_add']);
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // セッションから入力値を取得しviewに渡す
        $this->set(self::MODEL_NAME_CUSTOMER_REGIST_INFO, CakeSession::read(self::MODEL_NAME_CUSTOMER_REGIST_INFO));
    }

    public function customer_complete()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
            'Oem/customer_confirm',
            ], true) === false ) {
            $this->redirect(['controller' => 'oem', 'action' => 'customer_add']);
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $data = CakeSession::read(self::MODEL_NAME_CUSTOMER_REGIST_INFO);
        $this->CustomerRegistInfo->set($data);

        // 本登録
        $res = $this->CustomerRegistInfo->regist();

        if (!empty($res->error_message)) {
            $this->Flash->validation($res->error_message, ['key' => 'complete_error']);
            return $this->redirect(['controller' => 'oem', 'action' => 'customer_add']);
        }

        // ログイン
        $this->loadModel('CustomerLogin');
        $this->CustomerLogin->data['CustomerLogin']['email'] = $this->CustomerRegistInfo->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['email'];
        $this->CustomerLogin->data['CustomerLogin']['password'] = $this->CustomerRegistInfo->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO]['password'];
        $res = $this->CustomerLogin->login();

        // カスタマー情報を取得しセッションに保存
        $this->Customer->setTokenAndSave($res->results[0]);
        $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);
        $this->Customer->getInfo();

        CakeSession::delete(self::MODEL_NAME_CUSTOMER_REGIST_INFO);
        CakeSession::delete('app.data.alliance_cd');
    }
}
