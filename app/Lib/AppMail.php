<?php

App::uses('CakeEmail', 'Network/Email');

class AppMail
{


    public function sendPasswordReset($to, $hash)
    {
        $from = 'sender@domain.com';
        // $to = 'reciever@domain.com';
        $subject = 'パスワードリセット';
        $templete = 'password_reset';
        $data = [ 'url' => 'http://localhost:50080/customer/password_reset/add?hash=' . $hash ];
        $this->sendTemplate($from, $to, $subject, $templete, $data);
    }

    private function sendTemplate($from, $to, $subject, $templete, $data)
    {
        $email = new CakeEmail('default');
        $email->addHeaders(['X-Mailer' => '']);
        $email->from($from);
        $email->to($to);
        $email->subject($subject);
        $email->emailFormat('text');
        $email->template($templete);
        $email->viewVars($data);
        $email->send();
    }
}
