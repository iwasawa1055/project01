<?php
require_once 'SeleniumHelper.php';

class OutboundBoxHelper extends SeleniumHelper
{
    public function accessBoxIndex()
    {
        $this->test->urlAndWait('/outbound/box');
    }
    public function clickBoxSelectFirst()
    {
        $this->test->lastEl('.outbound_select_checkbox input[type=checkbox]')->click();
    }
    public function clickBoxSubmit()
    {
        $this->test->firstEl('#OutboundBoxForm button[type=submit]')->click();
        $this->test->waitPageLoad();
    }

    public function inputAddFrom($data)
    {
        if (array_key_exists('address_id', $data)) {
            $this->test->selectOption('#OutboundAddressId', $data['address_id']);
        }
        if (array_key_exists('datetime_cd', $data)) {
            $this->test->selectOption('#OutboundDatetimeCd', $data['datetime_cd'], 5);
        }
    }

    public function clickAddSubmit()
    {
        $this->test->firstEl('#OutboundIndexForm button[type=submit]')->click();
        $this->test->waitPageLoad();
    }
    public function clickAddReturnItem()
    {
        $this->clickElByText('アイテムを選択に戻る', "#OutboundIndexForm a.btn");
        $this->test->waitPageLoad();
    }
    public function clickAddReturnBox()
    {
        $this->clickElByText('ボックスを選択に戻る', "#OutboundIndexForm a.btn");
        $this->test->waitPageLoad();
    }

    public function clickConfirmSubmit()
    {
        $this->test->firstEl('#OutboundConfirmForm button[type=submit]')->click();
        $this->test->waitPageLoad();
    }

    public function clickConfirmReturn()
    {
        $this->clickElByText('戻る', "#OutboundConfirmForm a.btn");
    }

    public function clickCompleteReturn()
    {
        $this->clickElByText('マイページへ戻る');
    }
}
