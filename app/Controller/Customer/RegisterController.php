<?php

App::uses('MinikuraController', 'Controller');
App::uses('CustomerEmail', 'Model');
App::uses('AppMail', 'Lib');
App::uses('Folder', 'Utility');

class RegisterController extends MinikuraController
{
    /** model */
    const MODEL_NAME        = 'CustomerEntry';
    const MODEL_NAME_EMAIL  = 'Email';
    const MODEL_NAME_REGIST = 'CustomerRegistInfo';

    /** tmp file */
    const REGISTER_EMAIL_FILE_DIR   = TMP . 'register_email';
    const REGISTER_EMAIL_MAIL_LIMIT = 60 * 30 * 24;

    /** layout */
    public $layout = 'register';

    // ログイン不要なページ
    protected $checkLogined = false;

    /**
     * アクセス拒否.
     */
    protected function isAccessDeny()
    {
        if ($this->Customer->isLogined()) {
            return true;
        }

        return false;
    }

    /**
     * 登録種別選択フォーム
     */
    public function customer_add()
    {
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // TODO 後で消す
//        CakeSession::delete(self::MODEL_NAME_REGIST);

        // 初期表示
        if ($this->request->is('get')) {

            // 紹介コード
            $alliance_cd = '';
            if (isset($_GET['alliance_cd'])) {
                $alliance_cd = $_GET['alliance_cd'];
            } elseif (CakeSession::read('app.data.alliance_cd')) {
                $alliance_cd = CakeSession::read('app.data.alliance_cd');
            }
            CakeSession::Write('app.data.alliance_cd', $alliance_cd);

        // 確認へ遷移する場合
        } elseif ($this->request->is('post')) {

            $this->loadModel(self::MODEL_NAME_REGIST);

            // 入力値セット
            $this->CustomerRegistInfo->set($this->request->data);

            // バリデーション
            if ($this->CustomerRegistInfo->validates(['fieldList' => ['email']])) {
                CakeSession::write(self::MODEL_NAME_REGIST, $this->CustomerRegistInfo->toArray());
            } else {
                return $this->render('customer_add');
            }

            // 既存ユーザチェック
            $this->loadModel(self::MODEL_NAME_EMAIL);
            $result = $this->Email->getEmail(array('email' => $this->request->data[self::MODEL_NAME_REGIST]['email']));
            if ($result->status === "0") {
                $this->CustomerRegistInfo->validationErrors['email'][0] = '登録済みのメールアドレスです';
                return $this->render('customer_add');
            }

            // エラーがない場合は確認画面にリダイレクト
            return $this->redirect('/customer/register/complete_email');
        }
    }

    /**
     * Eメール送信完了フォーム
     */
    public function customer_complete_email()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
                'Register/customer_add',
            ], true) === false ) {
            $this->redirect(['controller' => 'register', 'action' => 'customer_add']);
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $data = CakeSession::read(self::MODEL_NAME_REGIST);
        $to = $data['email'];

        /** send mail */
        // 再設定用キー取得
        $key = Security::hash(date('YmdHis') . CakeText::uuid() . $to, 'md5', true);
        // キーファイル名
        $filename = date('Ymd') . '_' . $key;
        // 再設定用キーファイル作成
        $key_file_path = self::REGISTER_EMAIL_FILE_DIR . DS . $filename;
        $key_file = new File($key_file_path, true);
        $key_file->append($to);
        $mail = new AppMail();
        $mail->sendRegisterEmail($to, $key);

        // セッションから入力値を取得しviewに渡す
        $this->set(self::MODEL_NAME_REGIST, $data);

        CakeSession::delete(self::MODEL_NAME_REGIST);

    }

    /**
     * Email個人情報登録フォーム
     */
    public function customer_add_personal_email()
    {
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_REGIST);

        // URL遷移時
        if ($this->request->is('get')) {

            // セッションから入力値を取得
            $this->request->data[self::MODEL_NAME_REGIST] = CakeSession::read(self::MODEL_NAME_REGIST);

            if (empty($this->request->data[self::MODEL_NAME_REGIST])) {
                $key = Hash::get($this->request->query, 'hash');
                if (isset($key)) {
                    // キーファイルからEmail情報取得
                    $this->request->data[self::MODEL_NAME_REGIST] = $this->_getKeyFileData($key);
                    CakeSession::write(self::MODEL_NAME_REGIST, $this->request->data[self::MODEL_NAME_REGIST]);
                }
            }
            $this->CustomerRegistInfo->set($this->request->data);

        // 確認遷移時
        } else if ($this->request->is('post')) {

            $this->request->data[self::MODEL_NAME_REGIST] = array_merge(
                CakeSession::read(self::MODEL_NAME_REGIST),
                $this->request->data[self::MODEL_NAME_REGIST]
            );

            // 生年月日を結合
            $this->request->data[self::MODEL_NAME_REGIST]['birth'] = implode('-', [
                $this->request->data[self::MODEL_NAME_REGIST]['birth_year'],
                // ゼロ埋め
                sprintf('%02d', $this->request->data[self::MODEL_NAME_REGIST]['birth_month']),
                // ゼロ埋め
                sprintf('%02d', $this->request->data[self::MODEL_NAME_REGIST]['birth_day']),
            ]);

            // 入力値セット
            $this->CustomerRegistInfo->set($this->request->data);

            // バリデーション
            $validation_item = [
                'lastname',
                'lastname_kana',
                'firstname',
                'firstname_kana',
                'gender',
                'birth',
                'password',
                'password_confirm',
                'newsletter',
            ];
            if (!$this->CustomerRegistInfo->validates(['fieldList' => $validation_item])) {
                return $this->render('customer_add_personal_email');
            }

            CakeSession::write(self::MODEL_NAME_REGIST, $this->CustomerRegistInfo->toArray());

            // 既存ユーザチェック
            $this->loadModel(self::MODEL_NAME_EMAIL);
            $result = $this->Email->getEmail(array('email' => $this->request->data[self::MODEL_NAME_REGIST]['email']));
            if ($result->status === "0") {
                $this->CustomerRegistInfo->validationErrors['email'][0] = '登録済みのメールアドレスです';
                return $this->render('customer_add');
            }

            // エラーがない場合は確認画面にリダイレクト
            return $this->redirect(['controller' => 'register', 'action' => 'add_address_email']);
        }
    }

    /**
     * Email住所情報登録フォーム
     */
    public function customer_add_address_email()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
                'Register/customer_add_personal_email',
                'Register/customer_add_address_email',
                'Register/customer_confirm_entry_email',
            ], true) === false ) {
            $this->redirect(['controller' => 'register', 'action' => 'customer_add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_REGIST);

        if ($this->request->is('get')) {
            // セッションから入力値を取得
            $this->request->data[self::MODEL_NAME_REGIST] = CakeSession::read(self::MODEL_NAME_REGIST);
            $this->CustomerRegistInfo->set($this->request->data);

        } else if ($this->request->is('post')) {

            $this->request->data[self::MODEL_NAME_REGIST] = array_merge(
                CakeSession::read(self::MODEL_NAME_REGIST),
                $this->request->data[self::MODEL_NAME_REGIST]
            );

            // 電話番号を半角変換
            $this->request->data[self::MODEL_NAME_REGIST]['tel1'] = self::_wrapConvertKana($this->request->data[self::MODEL_NAME_REGIST]['tel1']);

            // 入力値セット
            $this->CustomerRegistInfo->set(array_merge($this->request->data));

            // バリデーション
            $validation_item = [
                'postal',
                'pref',
                'address1',
                'address2',
                'address3',
                'tel1',
            ];
            if (!$this->CustomerRegistInfo->validates(['fieldList' => $validation_item])) {
                return $this->render('customer_add_address_email');
            }

            CakeSession::write(self::MODEL_NAME_REGIST, $this->CustomerRegistInfo->toArray());

            // エラーがない場合は確認画面にリダイレクト
            return $this->redirect(['controller' => 'register', 'action' => 'confirm_entry_email']);
        }
    }

    /**
     * Email登録内容確認フォーム
     */
    public function customer_confirm_entry_email()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
                'Register/customer_add_address_email',
                'Register/customer_confirm_entry_email',
            ], true) === false ) {
            $this->redirect(['controller' => 'register', 'action' => 'customer_add']);
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // セッションから入力値を取得しviewに渡す
        $this->set(self::MODEL_NAME_REGIST, CakeSession::read(self::MODEL_NAME_REGIST));
    }

    /**
     * Email登録完了フォーム
     */
    public function customer_complete_entry_email()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
                'Register/customer_confirm_entry_email',
            ], true) === false ) {
            $this->redirect(['controller' => 'register', 'action' => 'customer_add']);
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_REGIST);

        $data = CakeSession::read(self::MODEL_NAME_REGIST);

        if (empty($data['alliance_cd'])) {
            unset($data['alliance_cd']);
        }
        $this->CustomerRegistInfo->set($data);

        // 既存ユーザチェック
        $this->loadModel(self::MODEL_NAME_EMAIL);
        $result = $this->Email->getEmail(array('email' => $data['email']));
        if ($result->status === "0") {
            // TODO 登録済みの場合にどこにrenderするのがいいのだろう・・・
            $this->CustomerRegistInfo->validationErrors['email'][0] = '登録済みのメールアドレスです';
            return $this->render('customer_add_entry');
        }

        // 本登録
        $res = $this->CustomerRegistInfo->regist();

        if (!empty($res->error_message)) {
            $this->Flash->validation($res->error_message, ['key' => 'complete_error']);
            return $this->redirect(['controller' => 'register', 'action' => 'customer_add']);
        }

        // ログイン
        $this->loadModel('CustomerLogin');
        $this->CustomerLogin->data['CustomerLogin']['email'] = $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['email'];
        $this->CustomerLogin->data['CustomerLogin']['password'] = $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['password'];
        $res = $this->CustomerLogin->login();

        // カスタマー情報を取得しセッションに保存
        $this->Customer->setTokenAndSave($res->results[0]);
        $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);
        $this->Customer->getInfo();

        CakeSession::delete(self::MODEL_NAME_REGIST);
        CakeSession::delete('app.data.alliance_cd');
    }

    public function customer_complete_facebook()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
                'Register/customer_add',
            ], true) === false ) {
            $this->redirect(['controller' => 'register', 'action' => 'customer_add']);
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $input_data = [
            'facebook_user_id'    => isset($_POST['facebook_user_id'])    ? $_POST['facebook_user_id']    : '',
            'facebook_email'      => isset($_POST['facebook_email'])      ? $_POST['facebook_email']      : '',
            'facebook_first_name' => isset($_POST['facebook_first_name']) ? $_POST['facebook_first_name'] : '',
            'facebook_last_name'  => isset($_POST['facebook_last_name'])  ? $_POST['facebook_last_name']  : '',
            // TODO facebookへ申請する必要あり
//            'facebook_gender'     => isset($_POST['facebook_gender'])     ? $_POST['facebook_gender']     : '',
//            'facebook_birthday'   => isset($_POST['facebook_birthday'])   ? $_POST['facebook_birthday']   : '',
//            'facebook_location'   => isset($_POST['facebook_location'])   ? $_POST['facebook_location']   : '',
        ];

        // TODO facebook登録APIを実施する

        // facebook情報を保持
        CakeSession::write(self::MODEL_NAME_REGIST, $input_data);

    }

    private function _getKeyFileData($key)
    {
        // tmpファイル形式チェック
        if ($key === null or $key === "") {
            new AppTerminalInfo(AppE::BAD_REQUEST . 'key file not found', 400);
            // TODO 要確認
            $this->Flash->set(__('customer_register_email_expiration'));
            $this->CustomerRegistInfo->validationErrors['error_message'][0] = 'お手数ですが初めから設定してください';
            return $this->redirect(['controller' => 'register', 'action' => 'customer_add']);
        }

        // 再発行申請ファイル取得
        $dir   = new Folder(self::REGISTER_EMAIL_FILE_DIR);
        $files = $dir->find('[0-9]{8}_' . $key);

        // tmpファイルチェック
        if (!is_array($files) or count($files) !== 1) {
            new AppTerminalInfo(AppE::BAD_REQUEST . 'key file not found', 400);
            $this->Flash->set(__('customer_register_email_expiration'));
            return $this->redirect(['controller' => 'register', 'action' => 'customer_add']);
        }

        // tmpファイル存在チェック
        $file = new File(self::REGISTER_EMAIL_FILE_DIR . DS . $files[0]);
        if (! $file->exists()) {
            new AppTerminalInfo(AppE::BAD_REQUEST . 'key file not exist', 400);
            $this->Flash->set(__('customer_register_email_expiration'));
            return $this->redirect(['controller' => 'register', 'action' => 'customer_add']);
        }

        // 有効期限チェック
        if ($file->lastChange() < time() - self::REGISTER_EMAIL_MAIL_LIMIT) {
            new AppTerminalInfo(AppE::BAD_REQUEST . 'key file is expired', 400);
            $this->Flash->set(__('customer_register_email_expiration'));
            return $this->redirect(['controller' => 'register', 'action' => 'customer_add']);
        }

        // キーファイルから変更メールアドレス取り出し
        $email = $file->read();
        return ["email" => $email, "key" => $key];
    }
}
