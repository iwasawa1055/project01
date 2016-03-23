<?php
require_once 'SeleniumHelper.php';

class CustomerEmailHelper extends SeleniumHelper
{
    public function accessEdit()
    {
        $this->test->urlAndWait('/customer/email/edit');
    }

    public function setEditFrom($data)
    {
        if (array_key_exists('email', $data)) {
            $this->test->firstEl('#CustomerEmailEmail')->clear();
            $this->test->firstEl('#CustomerEmailEmail')->value($data['email']);
        }
        if (array_key_exists('email_confirm', $data)) {
            $this->test->firstEl('#CustomerEmailEmailConfirm')->clear();
            $this->test->firstEl('#CustomerEmailEmailConfirm')->value($data['email_confirm']);
        }
    }
    public function getEditFrom()
    {
        $data = [
            'email' => $this->test->firstEl('#CustomerEmailEmail')->value(),
            'email_confirm' => $this->test->firstEl('#CustomerEmailEmailConfirm')->value(),
        ];
        return $data;
    }

    public function clickEditClear()
    {
        $this->clickElByText('クリア', '#CustomerEmailCustomerEditForm a.btn');
    }

    public function clickEditSubmit()
    {
        $this->test->firstEl('#CustomerEmailCustomerEditForm button[type=submit]')->click();
        $this->test->waitPageLoad();
    }
    public function clickConfirmSubmit()
    {
        $this->test->firstEl('#customer_confirmForm button[type=submit]')->click();
        $this->test->waitPageLoad();
    }

    public function clickConfirmReturn()
    {
        $this->clickElByText('戻る', '#customer_confirmForm a.btn');
    }

    public function clickCompleteReturn()
    {
        $this->clickElByText('マイページへ戻る');
    }
}
