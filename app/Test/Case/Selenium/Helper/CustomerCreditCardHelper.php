<?php
require_once 'SeleniumHelper.php';

class CustomerCreditCardHelper extends SeleniumHelper
{
    public function accessEdit()
    {
        $this->test->urlAndWait('/customer/credit_card/edit');
    }

    public function setEditFrom($data)
    {
        if (array_key_exists('card_no', $data)) {
            $this->test->firstEl('#PaymentGMOSecurityCardCardNo')->clear();
            $this->test->firstEl('#PaymentGMOSecurityCardCardNo')->value($data['card_no']);
        }
        if (array_key_exists('security_cd', $data)) {
            $this->test->firstEl('#PaymentGMOSecurityCardSecurityCd')->clear();
            $this->test->firstEl('#PaymentGMOSecurityCardSecurityCd')->value($data['security_cd']);
        }
        if (array_key_exists('expire_month', $data)) {
            $this->test->selectOption('#PaymentGMOSecurityCardExpireMonth', $data['expire_month']);
        }
        if (array_key_exists('expire_year', $data)) {
            $this->test->selectOption('#PaymentGMOSecurityCardExpireYear', $data['expire_year']);
        }
        if (array_key_exists('holder_name', $data)) {
            $this->test->firstEl('#PaymentGMOSecurityCardHolderName')->clear();
            $this->test->firstEl('#PaymentGMOSecurityCardHolderName')->value($data['holder_name']);
        }

    }
    public function getEditFrom()
    {
        $data = [
            'card_no' => $this->test->firstEl('#PaymentGMOSecurityCardCardNo')->value(),
            'security_cd' => $this->test->firstEl('#PaymentGMOSecurityCardSecurityCd')->value(),
            'expire_month' => $this->test->firstEl('#PaymentGMOSecurityCardExpireMonth')->value(),
            'expire_year' => $this->test->firstEl('#PaymentGMOSecurityCardExpireYear')->value(),
            'holder_name' => $this->test->firstEl('#PaymentGMOSecurityCardHolderName')->value(),
        ];
        return $data;
    }

    public function clickEditClear()
    {
        $this->clickElByText('クリア', '#PaymentGMOSecurityCardCustomerEditForm a.btn');
    }

    public function clickEditSubmit()
    {
        $this->test->firstEl('#PaymentGMOSecurityCardCustomerEditForm button[type=submit]')->click();
        $this->test->waitPageLoad();
    }
    public function clickConfirmSubmit()
    {
        $this->test->firstEl('#customer_editForm button[type=submit]')->click();
        $this->test->waitPageLoad();
    }

    public function clickConfirmReturn()
    {
        $this->clickElByText('戻る', '#customer_editForm a.btn');
    }

    public function clickCompleteReturn()
    {
        $this->clickElByText('マイページへ戻る');
    }
}
