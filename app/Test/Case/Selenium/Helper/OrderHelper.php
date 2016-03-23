<?php
require_once 'SeleniumHelper.php';

class OrderHelper extends SeleniumHelper
{
    public function accessAdd()
    {
        $this->test->urlAndWait('/order/add');
    }

    public function inputAddFrom($data)
    {
        if (array_key_exists('mono_num', $data)) {
            $this->test->selectOption('#OrderKitMonoNum', $data['mono_num']);
        }
        if (array_key_exists('hako_num', $data)) {
            $this->test->selectOption('#OrderKitHakoNum', $data['hako_num']);
        }
        if (array_key_exists('cleaning_num', $data)) {
            $this->test->selectOption('#OrderKitCleaningNum', $data['cleaning_num']);
        }
        if (array_key_exists('security_cd', $data)) {
            $this->test->firstEl('#OrderKitSecurityCd')->clear();
            $this->test->firstEl('#OrderKitSecurityCd')->value($data['security_cd']);
        }
        if (array_key_exists('address_id', $data)) {
            $this->test->selectOption('#OrderKitAddressId', $data['address_id']);
        }
        if (array_key_exists('datetime_cd', $data)) {
            $this->test->selectOption('#OrderKitDatetimeCd', $data['datetime_cd'], 5);
        }
    }
    public function getAddFrom()
    {
        $data = [
            'lastname' => $this->test->firstEl('#InquiryLastname')->value(),
            'lastname_kana' => $this->test->firstEl('#InquiryLastnameKana')->value(),
            'firstname' => $this->test->firstEl('#InquiryFirstname')->value(),
            'firstname_kana' => $this->test->firstEl('#InquiryFirstnameKana')->value(),
            'email' => $this->test->firstEl('#InquiryEmail')->value(),
            'division' => $this->test->selectEl('#InquiryDivision')->selectedLabel(),
            'text' => $this->test->firstEl('#InquiryText')->value(),
        ];
        return $data;
    }

    public function clickAddClear()
    {
        $this->clickElByText('クリア', '#OrderKitAddForm a.btn');
    }

    public function clickAddSubmit()
    {
        $this->test->firstEl('#OrderKitAddForm button[type=submit]')->click();
        $this->test->waitPageLoad();
    }

    public function checkConfirmCheckbox()
    {
        $this->test->firstEl('.agree-before-submit[type="checkbox"]')->click();
    }
    public function clickConfirmSubmit()
    {
        $this->test->firstEl('#confirmForm button[type=submit]')->click();
        $this->test->waitPageLoad();
    }

    public function clickConfirmReturn()
    {
        $this->clickElByText('戻る', "#confirmForm a.btn");
    }

    public function clickCompleteReturn()
    {
        $this->clickElByText('マイページへ戻る');
    }
}
