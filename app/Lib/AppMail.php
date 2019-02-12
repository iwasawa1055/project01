<?php

App::uses('CakeEmail', 'Network/Email');

class AppMail
{
    const CONFIG_NAME = 'default';

    /**
     * パスワードリセットで再設定ページお知らせメール
     * @param [type] $to   [description]
     * @param [type] $hash [description]
     */
    public function sendPasswordReset($to, $hash)
    {
        $from = null;
        $subject = 'パスワードリセット';
        $templete = 'password_reset';
        $data = [ 'url' => Configure::read('site.url') . '/customer/password_reset/add?hash=' . $hash ];
        $this->sendTemplate($from, $to, $subject, $templete, $data);
    }
    /**
     * 新規会員登録時の仮登録メール
     * @param [type] $to   [description]
     * @param [type] $hash [description]
     */
    public function sendRegisterEmail($to, $hash)
    {
        //
        $from = null;
        $subject = '新規会員(仮登録)';
        $templete = 'register_email';
        $data = [ 'url' => Configure::read('site.url') . '/customer/register/add_personal_email?hash=' . $hash ];
        $this->sendTemplate($from, $to, $subject, $templete, $data);
    }
    /**
     * テンプレートを使用したメール送信
     * @param  [type] $from     [description]
     * @param  [type] $to       [description]
     * @param  [type] $subject  [description]
     * @param  [type] $templete [description]
     * @param  [type] $data     [description]
     * @return [type]           [description]
     */
    private function sendTemplate($from, $to, $subject, $templete, $data)
    {
        $email = new CakeEmail(self::CONFIG_NAME);
        $email->addHeaders(['X-Mailer' => '']);
        $email->from($from);
        $email->to($to);
        $email->subject($subject);
        $email->emailFormat('text');
        $email->template($templete);
        $email->viewVars($data);
        CakeLog::write(MAIL_LOG, print_r($email->send(), true));
    }
    /**
     * 設定配列を取得
     * @return [type] [description]
     */
    public function config()
    {
        $email = new CakeEmail(self::CONFIG_NAME);
        return $email->config();
    }
}
