<?php
require_once 'SeleniumHelper.php';

class InboundBoxHelper extends SeleniumHelper
{
    public function accessAdd()
    {
        $this->test->urlAndWait('/inbound/box/add');
    }

    public function inputAddFrom($data)
    {
        if (array_key_exists('hako-box_title', $data)) {
            $this->test->firstEl('.hako-box input[type=text]')->value($data['hako-box_title']);
        }
        if (array_key_exists('hako-box_checkbox', $data)) {
            $this->test->firstEl('.hako-box input[type=checkbox]')->click();
        }
        if (array_key_exists('delivery_carrier', $data)) {
            $this->test->selectOption('#InboundDeliveryCarrier', $data['delivery_carrier']);
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


    public function clickAddSubmit()
    {
        $this->test->firstEl('#InboundAddForm button[type=submit]')->click();
        $this->test->waitPageLoad();
    }

    public function checkConfirmCheckbox()
    {
        $els = $this->test->allEl('.agree-before-submit[type="checkbox"]');
        $els[0]->click();
        $els[1]->click();
        $els[2]->click();
    }
    public function clickConfirmSubmit()
    {
        $this->test->firstEl('#InboundConfirmForm button[type=submit]')->click();
        $this->test->waitPageLoad();
    }

    public function clickConfirmReturn()
    {
        $this->clickElByText('戻る', "#InboundConfirmForm a.btn");
    }

    public function clickCompleteReturn()
    {
        $this->clickElByText('マイページへ戻る');
    }
}
