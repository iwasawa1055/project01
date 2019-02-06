<?php

App::uses('MinikuraController', 'Controller');
// TODO 未確認
App::uses('CustomerEmail', 'Model');
// TODO 使用している
App::uses('AppMail', 'Lib');
// TODO 未確認
App::uses('Folder', 'Utility');

class RegisterController extends MinikuraController
{
    /** model */
    const MODEL_NAME = 'CustomerEntry';
    const MODEL_NAME_EMAIL = 'Email';
    const MODEL_NAME_REGIST = 'CustomerRegistInfo';
    // TODO これなんだろう
    const MODEL_NAME_CORP_REGIST = 'CorporateRegistInfo';

    /** tmp file */
    const REGISTER_EMAIL_FILE_DIR   = TMP . 'register_email';
    // TODO 時間は要確認
    const REGISTER_EMAIL_MAIL_LIMIT = 60 * 30 * 24;

    /** layout */
    public $layout = 'register';


	//* nike_snkrs alliance_cd
//	const SNEAKERS_ALLIANCE_CD = 'api.sneakers.alliance_cd';
//	const SNEAKERS_FILE_KEY_LIST = 'api.sneakers.file.key_list';
//	const SNEAKERS_FILE_REGISTERED_LIST = 'api.sneakers.file.registered_list';
//	const SNEAKERS_FILE_ERROR_LIST = 'api.sneakers.file.error_list';
//	const SNEAKERS_DIR = 'api.sneakers.dir';
//	const SNEAKERS_REASON_NOT_EXIST = 'sneakers key is not exist';
//	const SNEAKERS_REASON_REGISTERED = 'sneakers key has already registered';




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
     * エントリー登録フォーム
     */
    public function customer_add()
    {
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // 紹介コード
        $alliance_cd = '';
        if (isset($_GET['alliance_cd'])) {
            $alliance_cd = $_GET['alliance_cd'];
        } elseif (CakeSession::read('app.data.alliance_cd')) {
            $alliance_cd = CakeSession::read('app.data.alliance_cd');
        }
        CakeSession::Write('app.data.alliance_cd', $alliance_cd);

        return;
    }

    /**
     * mailエントリー登録フォーム
     */
    public function customer_add_email()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
                'Register/customer_add',
                'Register/customer_add_email',
            ], true) === false ) {
            $this->redirect(['controller' => 'register', 'action' => 'customer_add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // 初期表示
        if ($this->request->is('get')) {
            // セッションから入力値を取得
            $data = CakeSession::read(self::MODEL_NAME_REGIST);
            $this->request->data = [self::MODEL_NAME_REGIST => $data];

            // TODO これの存在意義がわからない
//            if (empty($this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO])) {
//                $this->request->data[self::MODEL_NAME_CUSTOMER_REGIST_INFO] = [
//                    'alliance_cd' => $alliance_cd,
//                ];
//            }
        // 確認へ遷移する場合
        } elseif ($this->request->is('post')) {

            $this->loadModel(self::MODEL_NAME_REGIST);

            // 入力値セット
            $this->CustomerRegistInfo->set($this->request->data);

            // バリデーション
            if ($this->CustomerRegistInfo->validates(['fieldList' => ['email']])) {
                CakeSession::write(self::MODEL_NAME_REGIST, $this->CustomerRegistInfo->toArray());
            } else {
                return $this->render('customer_add_email');
            }

            // 既存ユーザチェック
            $this->loadModel(self::MODEL_NAME_EMAIL);
            $result = $this->Email->getEmail(array('email' => $this->request->data[self::MODEL_NAME_REGIST]['email']));
            if ($result->status === "0") {
                $this->CustomerRegistInfo->validationErrors['email'][0] = '登録済みのメールアドレスです';
                return $this->render('customer_add_email');
            }

            // エラーがない場合は確認画面にリダイレクト
            return $this->redirect('/customer/register/complete_email');
        }
    }

    public function customer_complete_email()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
                'Register/customer_add_email',
                'Register/customer_complete_email',
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

    }

    /**
     * エントリー登録フォーム
     */
    public function customer_add_entry()
    {
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // 初期表示
        if ($this->request->is('get')) {

            $key = Hash::get($this->request->query, 'hash');

//        if (isset($key) === false) {
//            $data = CakeSession::read(self::MODEL_NAME_REGIST);
//            if (isset($data['CustomerRegistInfo']['key'])) {
//                $key = $data['CustomerRegistInfo']['key'];
//            }
//        }

            // tmpファイル形式チェック
            if ($key === null or $key === "") {
                new AppTerminalInfo(AppE::BAD_REQUEST . 'key file not found', 400);
                $this->Flash->set(__('customer_register_email_expiration'));
                return $this->redirect(['action' => 'customer_index']);
            }

            // 再発行申請ファイル取得
            $dir   = new Folder(self::REGISTER_EMAIL_FILE_DIR);
            $files = $dir->find('[0-9]{8}_' . $key);

            // tmpファイルチェック
            if (!is_array($files) or count($files) !== 1) {
                new AppTerminalInfo(AppE::BAD_REQUEST . 'key file not found', 400);
                $this->Flash->set(__('customer_register_email_expiration'));
                return $this->redirect(['action' => 'customer_index']);
            }

            // tmpファイル存在チェック
            $file = new File(self::REGISTER_EMAIL_FILE_DIR . DS . $files[0]);
            if (! $file->exists()) {
                new AppTerminalInfo(AppE::BAD_REQUEST . 'key file not exist', 400);
                $this->Flash->set(__('customer_register_email_expiration'));
                return $this->redirect(['action' => 'customer_index']);
            }

            // 有効期限チェック
            if ($file->lastChange() < time() - self::REGISTER_EMAIL_MAIL_LIMIT) {
                new AppTerminalInfo(AppE::BAD_REQUEST . 'key file is expired', 400);
                $this->Flash->set(__('customer_register_email_expiration'));
                return $this->redirect(['action' => 'customer_index']);
            }

            // キーファイルから変更メールアドレス取り出し
            $email = $file->read();
            $this->request->data = [self::MODEL_NAME_REGIST => ["email" => $email, "key" => $key]];
            CakeSession::write(self::MODEL_NAME_REGIST, $this->request->data);

        // 確認へ遷移する場合
        } else if ($this->request->is('post')) {

            // 生年月日を結合
            $this->request->data[self::MODEL_NAME_REGIST]['birth'] = implode('-', [
                $this->request->data[self::MODEL_NAME_REGIST]['birth_year'],
                // ゼロ埋め
                sprintf('%02d', $this->request->data[self::MODEL_NAME_REGIST]['birth_month']),
                // ゼロ埋め
                sprintf('%02d', $this->request->data[self::MODEL_NAME_REGIST]['birth_day']),
            ]);

            // 電話番号を半角変換
            $this->request->data[self::MODEL_NAME_REGIST]['tel1'] = self::_wrapConvertKana($this->request->data[self::MODEL_NAME_REGIST]['tel1']);

            $this->loadModel(self::MODEL_NAME_REGIST);

            // 入力値セット
            $this->CustomerRegistInfo->set($this->request->data);

            // バリデーション
            if ($this->CustomerRegistInfo->validates()) {
                CakeSession::write(self::MODEL_NAME_REGIST, $this->CustomerRegistInfo->toArray());
            } else {
                return $this->render('customer_add_entry');
            }

            // 既存ユーザチェック
            $this->loadModel(self::MODEL_NAME_EMAIL);
            $result = $this->Email->getEmail(array('email' => $this->request->data[self::MODEL_NAME_REGIST]['email']));
            if ($result->status === "0") {
                $this->CustomerRegistInfo->validationErrors['email'][0] = '登録済みのメールアドレスです';
                return $this->render('customer_add_entry');
            }

            // エラーがない場合は確認画面にリダイレクト
            return $this->redirect('/customer/register/confirm_entry');
        }
    }

    public function customer_confirm_entry()
    {
        //* session referer 確認
        if (in_array(CakeSession::read('app.data.session_referer'), [
                'Register/customer_add_entry',
                'Register/customer_confirm_entry',
            ], true) === false ) {
            $this->redirect(['controller' => 'register', 'action' => 'customer_add']);
        }

        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // セッションから入力値を取得しviewに渡す
        $this->set(self::MODEL_NAME_REGIST, CakeSession::read(self::MODEL_NAME_REGIST));
    }

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

        $data = CakeSession::read(self::MODEL_NAME_REGIST);
        unset($data['alliance_cd']);
        $this->CustomerRegistInfo->set($data);

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
}
