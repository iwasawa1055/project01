<?php
require_once 'SeleniumHelper.php';

class CustomerPasswordHelper extends SeleniumHelper
{
    public function accessEdit()
    {
        $this->test->urlAndWait('/customer/password/edit');
    }

    public function setEditFrom($data)
    {
        if (array_key_exists('password', $data)) {
            $this->test->firstEl('#CustomerPasswordPassword')->clear();
            $this->test->firstEl('#CustomerPasswordPassword')->value($data['password']);
        }
        if (array_key_exists('new_password', $data)) {
            $this->test->firstEl('#CustomerPasswordNewPassword')->clear();
            $this->test->firstEl('#CustomerPasswordNewPassword')->value($data['new_password']);
        }
        if (array_key_exists('new_password_confirm', $data)) {
            $this->test->firstEl('#CustomerPasswordNewPasswordConfirm')->clear();
            $this->test->firstEl('#CustomerPasswordNewPasswordConfirm')->value($data['new_password_confirm']);
        }
    }
    public function getEditFrom()
    {
        $data = [
            'password' => $this->test->firstEl('#CustomerPasswordPassword')->value(),
            'email_confirm' => $this->test->firstEl('#CustomerPasswordNewPassword')->value(),
            'new_password_confirm' => $this->test->firstEl('#CustomerPasswordNewPasswordConfirm')->value(),
        ];
        return $data;
    }

    public function clickEditReturn()
    {
        $this->clickElByText('戻る', '#CustomerPasswordCustomerEditForm a.btn');
    }

    public function clickEditSubmit()
    {
        $this->test->byXPath("(//button[@type='submit'])[2]")->click();
        $this->test->waitPageLoad();
    }
    public function clickCompleteReturn()
    {
        $this->clickElByText('マイページへ戻る');
    }
}
