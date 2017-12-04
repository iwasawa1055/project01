<?php

App::uses('MinikuraController', 'Controller');
App::uses('CustomerEmail', 'Model');
App::uses('AppMail', 'Lib');
App::uses('Folder', 'Utility');

class PasswordResetController extends MinikuraController
{
    const MODEL_NAME = 'CustomerPasswordReset';
    // パスワードリセット管理用ディレクトリ
    const RESET_PASSWORD_FILE_DIR   = TMP . 'reset_password';
    // パスワードの有効期限(30分間)
    const RESET_PASSWORD_MAIL_LIMIT = 60 * 30;

    // ログイン不要なページ
    protected $checkLogined = false;

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
    }

    /**
     *
     */
    public function customer_index()
    {
        // #14395 リダイレクトループの対策として以前に発行した「.minikura.com」ドメインのcookieを削除します。
        // 該当のcookieの最長の有効期限は2018/09/14となるので、それ以降に下の処理の削除をお願いします。
        setcookie("WWWMINIKURACOM", "", time()-60, "", ".minikura.com");
        setcookie("MINIKURACOM", "", time()-60, "", ".minikura.com");

        if ($this->request->is('post')) {
            $this->CustomerPasswordReset->set($this->request->data);
            if ($this->CustomerPasswordReset->validates(['fieldList' => ['email']])) {

                $to = $this->CustomerPasswordReset->toArray()['email'];

                // 存在チェック
                $email = new CustomerEmail();
                $res = $email->apiGet(['email' => $to]);
                if ($res->isSuccess()) {
                    // 未登録メールアドレス
                    $this->Flash->set(__('customer_password_reset_mail_notfound'));
                    return $this->redirect(['action' => 'customer_index']);
                }

                // 再設定用キー取得
                $key = Security::hash(date('YmdHis') . CakeText::uuid() . $to, 'md5', true);

                // キーファイル名
                $filename = date('Ymd') . '_' . $key;

                // 再設定用キーファイル作成
                $key_file_path = self::RESET_PASSWORD_FILE_DIR . DS . $filename;
                $key_file = new File($key_file_path, true);
                $key_file->append($to);

                $mail = new AppMail();
                $mail->sendPasswordReset($to, $key);

                $this->Flash->set(__('customer_password_reset_mail_send'));
                return $this->redirect(['action' => 'customer_index']);
            } else {
                return $this->render('customer_index');
            }
        }
    }


    /**
     *
     */
    public function customer_add()
    {
        $key = Hash::get($this->request->query, 'hash');

        // tmpファイル形式チェック
        if ($key === null or $key === "") {
            new AppTerminalInfo(AppE::BAD_REQUEST . 'key file not found', 400);
            $this->Flash->set(__('customer_password_reset_expiration'));
            return $this->redirect(['action' => 'customer_index']);
        }

        // 再発行申請ファイル取得
        $dir   = new Folder(self::RESET_PASSWORD_FILE_DIR);
        $files = $dir->find('[0-9]{8}_' . $key);

        // tmpファイルチェック
        if (!is_array($files) or count($files) !== 1) {
            new AppTerminalInfo(AppE::BAD_REQUEST . 'key file not found', 400);
            $this->Flash->set(__('customer_password_reset_expiration'));
            return $this->redirect(['action' => 'customer_index']);
        }

        // tmpファイル存在チェック
        $file = new File(self::RESET_PASSWORD_FILE_DIR . DS . $files[0]);
        if (! $file->exists()) {
            new AppTerminalInfo(AppE::BAD_REQUEST . 'key file not exist', 400);
            $this->Flash->set(__('customer_password_reset_expiration'));
            return $this->redirect(['action' => 'customer_index']);
        }

        // 有効期限チェック
        if ($file->lastChange() < time() - self::RESET_PASSWORD_MAIL_LIMIT) {
            new AppTerminalInfo(AppE::BAD_REQUEST . 'key file is expired', 400);
            $this->Flash->set(__('customer_password_reset_expiration'));
            return $this->redirect(['action' => 'customer_index']);
        }

        // キーファイルから変更メールアドレス取り出し
        $email = $file->read();
        $this->request->data = [self::MODEL_NAME => ["email" => $email, "key" => $key]];
        CakeSession::write(self::MODEL_NAME, $this->request->data);
    }

    /**
     *
     */
    public function customer_complete()
    {
        // メールアドレスを上書き
        $data = CakeSession::read(self::MODEL_NAME);

        $email = $data['CustomerPasswordReset']['email'];
        $this->request->data['CustomerPasswordReset']['email'] = $email;
        $this->CustomerPasswordReset->set($this->request->data);

        if ($this->CustomerPasswordReset->validates()) {
            // api
            $this->CustomerPasswordReset->apiPut($this->CustomerPasswordReset->toArray());
            $this->set('email', $this->CustomerPasswordReset->toArray()['email']);

            // セッションの削除
            CakeSession::delete(self::MODEL_NAME);

            // キーファイルの削除
            $dir = new Folder(self::RESET_PASSWORD_FILE_DIR);
            $files = $dir->find('[0-9]{8}_' . $data['CustomerPasswordReset']['key']);
            $file = new File(self::RESET_PASSWORD_FILE_DIR . DS . $files[0]);
            if (!$file->exists()) {
                new AppInternalInfo(AppE::FILESYSTEM . 'key file not exist');
            } else {
                $file->delete();
            }
        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }
    }
}
