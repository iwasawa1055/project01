<?php

App::uses('MinikuraController', 'Controller');
App::uses('CustomerEmail', 'Model');
App::uses('AppMail', 'Lib');
App::uses('Folder', 'Utility');

class RegisterController extends MinikuraController
{
    /** model */
    const MODEL_NAME           = 'CustomerEntry';
    const MODEL_NAME_EMAIL     = 'Email';
    const MODEL_NAME_REGIST    = 'CustomerRegistInfo';
    const MODEL_NAME_FB_REGIST = 'CustomerFacebook';
    const MODEL_NAME_FB_CHECK  = 'FacebookUser';
    const MODEL_NAME_GOOGLE_REGIST = 'CustomerGoogle';
    const MODEL_NAME_GOOGLE_CHECK  = 'GoogleUser';

    /** tmp file */
    const REGISTER_EMAIL_FILE_DIR   = TMP . 'register_email';
    const REGISTER_ALLIANCE_FILE_DIR   = TMP . 'register_alliance_cd';
    const REGISTER_GIFT_FILE_DIR   = TMP . 'register_gift_cd';
    const REGISTER_EMAIL_MAIL_LIMIT = 60 * 30 * 24;

    /** layout */
    public $layout = 'register';

    // ログイン不要なページ
    protected $checkLogined = false;

    // エントリーフラグ
    private $entryFlag = false;

    /**
     * アクセス拒否.
     */
    protected function isAccessDeny()
    {
        if ($this->Customer->isLogined()) {
            if (!$this->Customer->isEntry()) {
                return true;
            }
            $this->entryFlag = true;
        }

        return false;
    }

    /**
     * 登録種別選択フォーム
     */
    public function customer_add()
    {
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // 初期表示
        if ($this->request->is('get')) {

            // 紹介コード
            $alliance_cd = '';
            if (isset($_GET['alliance_cd'])) {
                $alliance_cd = $_GET['alliance_cd'];
            } elseif (isset($_GET['code'])) {
                $alliance_cd = $_GET['code'];
            } elseif (CakeSession::read('app.data.alliance_cd')) {
                $alliance_cd = CakeSession::read('app.data.alliance_cd');
            }
            CakeSession::Write('app.data.alliance_cd', $alliance_cd);

            // ギフトコード
            $gift_cd = '';
            if (isset($_GET['gift_cd'])) {
                $gift_cd = $_GET['gift_cd'];
            } elseif (CakeSession::read('app.data.gift_cd')) {
                $gift_cd = CakeSession::read('app.data.gift_cd');
            }
            CakeSession::Write('app.data.gift_cd', $gift_cd);

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
        if (empty($data)) {
            $this->redirect(['controller' => 'register', 'action' => 'customer_add']);
        }
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

        if (CakeSession::Read('app.data.alliance_cd')) {
            // alliance_cdをキーファイル作成
            $key_file_path = self::REGISTER_ALLIANCE_FILE_DIR . DS . $filename;
            $key_file = new File($key_file_path, true);
            $key_file->append(CakeSession::Read('app.data.alliance_cd'));
        }

        if (CakeSession::Read('app.data.gift_cd')) {
            // gift_cdをキーファイル作成
            $key_file_path = self::REGISTER_GIFT_FILE_DIR . DS . $filename;
            $key_file = new File($key_file_path, true);
            $key_file->append(CakeSession::Read('app.data.gift_cd'));
        }

        // セッションから入力値を取得しviewに渡す
        $this->set(self::MODEL_NAME_REGIST, $data);

        CakeSession::delete(self::MODEL_NAME_REGIST);

    }

    /**
     * facebook情報取得フォーム
     */
    public function customer_complete_facebook()
    {
        echo('<pre>');
        var_dump($this->request->data);
        echo('</pre>');
        exit;
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
                'Register/customer_add',
                'Register/customer_complete_facebook',
            ], true) === false ) {
            $this->redirect(['controller' => 'register', 'action' => 'customer_add']);
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_FB_CHECK);

        // facebook既存チェック
        $this->FacebookUser->set($this->request->data);
        if ($this->FacebookUser->validates()) {
            $res = $this->FacebookUser->get_account_data();
            if (!empty($res->error_message)) {
                $this->FacebookUser->validationErrors['facebook'][0] = '既にfacebookアカウントで登録済みです';
                return $this->render('customer_add');
            }
        } else {
            return $this->render('customer_add');
        }
        // alliance_cdを保存
        if (CakeSession::Read('app.data.alliance_cd')) {
            $this->request->data[self::MODEL_NAME_FB_CHECK]['alliance_cd'] = CakeSession::Read('app.data.alliance_cd');
        }

        // facebook情報を保持
        CakeSession::write(self::MODEL_NAME_REGIST, $this->request->data[self::MODEL_NAME_FB_CHECK]);

        // 個人情報入力画面へリダイレクトする
        return $this->redirect(['controller' => 'register', 'action' => 'add_personal']);

    }

    /**
     * google情報取得フォーム
     */
    public function customer_complete_google()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
                'Register/customer_add',
                'Register/customer_complete_google',
            ], true) === false ) {
            $this->redirect(['controller' => 'register', 'action' => 'customer_add']);
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_GOOGLE_CHECK);

        //google既存チェック
        $this->GoogleUser->set($this->request->data);
        if ($this->GoogleUser->validates()) {
            $res = $this->GoogleUser->get_account_data();
            if (!empty($res->error_message)) {
                $this->GoogleUser->validationErrors['google'][0] = '既にgoogleアカウントで登録済みです';
                return $this->render('customer_add');
            }
        } else {
            return $this->render('customer_add');
        }
        // alliance_cdを保存
        if (CakeSession::Read('app.data.alliance_cd')) {
            $this->request->data[self::MODEL_NAME_GOOGLE_CHECK]['alliance_cd'] = CakeSession::Read('app.data.alliance_cd');
        }

        // google情報を保持
        CakeSession::write(self::MODEL_NAME_REGIST, $this->request->data[self::MODEL_NAME_GOOGLE_CHECK]);

        // 個人情報入力画面へリダイレクトする
        return $this->redirect(['controller' => 'register', 'action' => 'add_personal']);

    }

    /**
     * 個人情報登録フォーム
     */
    public function customer_add_personal()
    {
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_REGIST);

        $this->set('entry_flag', $this->entryFlag);

        // URL遷移時
        if ($this->request->is('get')) {

            $data = CakeSession::read(self::MODEL_NAME_REGIST);
            if ($this->entryFlag && empty($data)) {
                // エントリーユーザデータ取得
                $this->request->data[self::MODEL_NAME_REGIST] = $this->Customer->getInfo();
            } else {
                // セッションから入力値を取得
                $this->request->data[self::MODEL_NAME_REGIST] = $data;
            }

            if (empty($this->request->data[self::MODEL_NAME_REGIST])) {
                $key = Hash::get($this->request->query, 'hash');
                if (isset($key)) {
                    // キーファイルからEmail情報取得
                    $this->request->data[self::MODEL_NAME_REGIST] = $this->_getKeyFileData($key);

                    // AllianceCdファイル取得
                    $dir   = new Folder(self::REGISTER_ALLIANCE_FILE_DIR);
                    $files = $dir->find('[0-9]{8}_' . $key);

                    // tmpファイル存在チェック
                    if (isset($files) && count($files) == 1) {
                        $file = new File(self::REGISTER_ALLIANCE_FILE_DIR . DS . $files[0]);
                        if ($file->exists()) {
                            $alliance_cd = $file->read();
                            $this->request->data[self::MODEL_NAME_REGIST]['alliance_cd'] = $alliance_cd;
                        }
                    }
                    // GiftCdファイル取得
                    $dir   = new Folder(self::REGISTER_GIFT_FILE_DIR);
                    $files = $dir->find('[0-9]{8}_' . $key);
                    // tmpファイル存在チェック
                    if (isset($files) && count($files) == 1) {
                        $file = new File(self::REGISTER_GIFT_FILE_DIR . DS . $files[0]);
                        if ($file->exists()) {
                            $gift_cd = $file->read();
                            CakeSession::Write('app.data.gift_cd', $gift_cd);
                        }
                    }
                }
            }
            CakeSession::write(self::MODEL_NAME_REGIST, $this->request->data[self::MODEL_NAME_REGIST]);
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
                'newsletter',
            ];

            // パスワードをバリデーション追加(FBユーザー以外)
            if (isset($this->request->data[self::MODEL_NAME_REGIST]['facebook_user_id']) == false && !$this->entryFlag) {
                $validation_item[] = 'password';
                $validation_item[] = 'password_confirm';
            }

            if (!$this->CustomerRegistInfo->validates(['fieldList' => $validation_item])) {
                return $this->render('customer_add_personal');
            }

            CakeSession::write(self::MODEL_NAME_REGIST, $this->CustomerRegistInfo->toArray());

            // 既存ユーザチェック(エントリーユーザは登録済みのため除く)
            if (!$this->entryFlag) {
                $this->loadModel(self::MODEL_NAME_EMAIL);
                $result = $this->Email->getEmail(array('email' => $this->request->data[self::MODEL_NAME_REGIST]['email']));
                if ($result->status === "0") {
                    $this->CustomerRegistInfo->validationErrors['email'][0] = '登録済みのメールアドレスです';
                    return $this->render('customer_add');
                }
            }

            // エラーがない場合は確認画面にリダイレクト
            return $this->redirect(['controller' => 'register', 'action' => 'add_address']);
        }
    }

    /**
     * 住所情報登録フォーム
     */
    public function customer_add_address()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
                'Register/customer_add_personal',
                'Register/customer_add_address',
                'Register/customer_confirm_entry',
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
                return $this->render('customer_add_address');
            }

            CakeSession::write(self::MODEL_NAME_REGIST, $this->CustomerRegistInfo->toArray());

            // エラーがない場合は確認画面にリダイレクト
            return $this->redirect(['controller' => 'register', 'action' => 'confirm_entry']);
        }
    }

    /**
     * 登録内容確認フォーム
     */
    public function customer_confirm_entry()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
                'Register/customer_add_address',
                'Register/customer_confirm_entry',
            ], true) === false ) {
            $this->redirect(['controller' => 'register', 'action' => 'customer_add']);
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // セッションから入力値を取得しviewに渡す
        $this->set(self::MODEL_NAME_REGIST, CakeSession::read(self::MODEL_NAME_REGIST));
    }

    /**
     * 登録完了フォーム
     */
    public function customer_complete_entry()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
                'Register/customer_confirm_entry',
            ], true) === false ) {
            $this->redirect(['controller' => 'register', 'action' => 'customer_add']);
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_REGIST);
        $this->loadModel(self::MODEL_NAME_FB_REGIST);

        $data = CakeSession::read(self::MODEL_NAME_REGIST);

        // エントリーユーザ用情報
        if ($this->entryFlag) {
            $data['token'] = CakeSession::read(ApiModel::SESSION_API_TOKEN);
            $data['password'] = $this->Customer->getPassword();
        }

        if (empty($data['alliance_cd'])) {
            unset($data['alliance_cd']);
        }
        $this->CustomerRegistInfo->set($data);

        // 既存ユーザチェック(エントリーユーザは登録済みのため除く)
        if (!$this->entryFlag) {
            $this->loadModel(self::MODEL_NAME_EMAIL);
            $result = $this->Email->getEmail(array('email' => $data['email']));
            if ($result->status === "0") {
                $this->CustomerRegistInfo->validationErrors['email'][0] = '登録済みのメールアドレスです';
                return $this->render('customer_add');
            }
        }

        // Facebook登録のみ仮のパスワードを発行
        if (isset($data['facebook_user_id'])) {
            // 仮のパスワードを設定
            $this->CustomerRegistInfo->data['CustomerRegistInfo']['password'] = uniqid();
        }

        if (!$this->entryFlag) {
            // 本登録
            $res = $this->CustomerRegistInfo->regist();
        } else {
            // エントリーユーザ登録
            $res = $this->CustomerRegistInfo->regist_no_oemkey();
            // 再度ログイン
            $this->Customer->switchEntryToCustomer();
        }
        if (!empty($res->error_message)) {
            $this->Flash->validation($res->error_message, ['key' => 'complete_error']);
            return $this->redirect(['controller' => 'register', 'action' => 'customer_add_personal']);
        }

        // ログイン
        $this->loadModel('CustomerLogin');
        $this->CustomerLogin->data['CustomerLogin']['email'] = $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['email'];
        $this->CustomerLogin->data['CustomerLogin']['password'] = $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['password'];
        $login_res = $this->CustomerLogin->login();

        // カスタマー情報を取得しセッションに保存
        $this->Customer->setTokenAndSave($login_res->results[0]);
        $this->Customer->setPassword($this->CustomerLogin->data['CustomerLogin']['password']);
        $this->Customer->getInfo();

        // Facebook登録のみFacebook連携
        if (isset($data['facebook_user_id'])) {
            // FB連携
            $this->CustomerFacebook->set(['facebook_user_id' => $data['facebook_user_id']]);
            $res = $this->CustomerFacebook->regist();
            if (!empty($res->error_message)) {
                $this->Flash->validation($res->error_message, ['key' => 'complete_error']);
                return $this->redirect(['controller' => 'register', 'action' => 'customer_add_personal']);
            }

            CakeSession::write(CustomerLogin::SESSION_FACEBOOK_ACCESS_KEY, $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['access_token']);
        }

        // Google連携
        if (isset($data['google_user_id'])) {
            // FB連携
            $this->CustomerGoogle->set(['google_user_id' => $data['google_user_id']]);
            $res = $this->CustomerGoogle->regist();
            if (!empty($res->error_message)) {
                $this->Flash->validation($res->error_message, ['key' => 'complete_error']);
                return $this->redirect(['controller' => 'register', 'action' => 'customer_add_personal']);
            }

            CakeSession::write(CustomerLogin::SESSION_GOOGLE_ACCESS_KEY, $this->CustomerRegistInfo->data[self::MODEL_NAME_REGIST]['access_token']);
        }

        CakeSession::delete(self::MODEL_NAME_REGIST);
        CakeSession::delete('app.data.alliance_cd');
    }

    private function _getKeyFileData($key)
    {
        // tmpファイル形式チェック
        if ($key === null or $key === "") {
            new AppTerminalInfo(AppE::BAD_REQUEST . 'key file not found', 400);
            $this->Flash->set(__('customer_register_email_expiration'));
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
