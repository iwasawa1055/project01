<?php
require_once 'SeleniumHelper.php';

class InquiryHelper extends SeleniumHelper
{
    public function accessAdd()
    {
        $this->test->urlAndWait('/inquiry/add');
    }

    public function setAddFrom($data)
    {
        if (array_key_exists('lastname', $data)) {
            $this->test->firstEl('#InquiryLastname')->clear();
            $this->test->firstEl('#InquiryLastname')->value($data['lastname']);
        }
        if (array_key_exists('lastname_kana', $data)) {
            $this->test->firstEl('#InquiryLastnameKana')->clear();
            $this->test->firstEl('#InquiryLastnameKana')->value($data['lastname_kana']);
        }
        if (array_key_exists('firstname', $data)) {
            $this->test->firstEl('#InquiryFirstname')->clear();
            $this->test->firstEl('#InquiryFirstname')->value($data['firstname']);
        }
        if (array_key_exists('firstname_kana', $data)) {
            $this->test->firstEl('#InquiryFirstnameKana')->clear();
            $this->test->firstEl('#InquiryFirstnameKana')->value($data['firstname_kana']);
        }
        if (array_key_exists('email', $data)) {
            $this->test->firstEl('#InquiryEmail')->clear();
            $this->test->firstEl('#InquiryEmail')->value($data['email']);
        }
        if (array_key_exists('division', $data)) {
            $this->test->selectOption('#InquiryDivision', 0);
            $sl = $this->test->selectEl('#InquiryDivision');
            $sl->selectOptionByLabel($data['division']);
        }
        if (array_key_exists('text', $data)) {
            $this->test->firstEl('#InquiryText')->clear();
            $this->test->firstEl('#InquiryText')->value($data['text']);
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
        $this->clickElByText('クリア', '#InquiryAddForm a.btn');
    }

    public function clickAddSubmit()
    {
        $this->test->firstEl('#InquiryAddForm button[type=submit]')->click();
        $this->test->waitPageLoad();
    }
    public function checkConfirmCheckbox()
    {
        $this->test->firstEl('.agree-before-submit[type="checkbox"]')->click();
        $this->test->waitPageLoad();
    }
    public function clickConfirmSubmit()
    {
        $this->test->firstEl('#InquiryConfirmForm button[type=submit]')->click();
        $this->test->waitPageLoad();
    }

    public function clickConfirmReturn()
    {
        $els = $this->test->allEl('#InquiryConfirmForm a.btn');
        foreach ($els as $el) {
            if ($el->text() == '戻る') {
                $el->click();
                $this->test->waitPageLoad();
                return;
            }
        }
        $this->test->assertTrue(false, 'notfound');
    }

    public function clickCompleteReturn()
    {
        $this->clickElByText('ログインへ戻る');
    }
}
