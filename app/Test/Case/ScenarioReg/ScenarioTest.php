<?php

require_once TESTS .'Case\MinikuraTestCase.php';

class ScenarioTest extends MinikuraTestCase
{
    public function setUpPage()
    {
        $this->setLogin();

    }

    /**
     * @test
     */
    public function ボックスをキット購入から出庫まで()
    {
        $this->createOrder();
        $this->donefirstOrderId();
        $this->createInboundBoxHako();
        $this->donefirstInboundBoxId();
        $this->createOutboundBoxHako();
        $this->donefirstOutboundWorkId();
    }

    public function createOrder()
    {
        $this->urlAndWait('/order/add');
        //-入力画面
        // 個数
        $this->selectOption('#OrderKitHakoNum', 1);
        // セキュリティコード
        $this->firstEl("#OrderKitSecurityCd")->value('123');
        // お届け先 お届希望日時
        $this->selectOption('#OrderKitAddressId');
        $this->selectOption('#OrderKitDatetimeCd', null, 5);
        // 確定ボタン
        $this->firstEl('#OrderKitAddForm button[type=submit]')->click();
        $this->waitPageLoad();
        //-確認画面
        $this->firstEl('.agree-before-submit[type="checkbox"]')->click();
        $this->firstEl('#confirmForm button[type=submit]')->click();
        $this->waitPageLoad();
    }

    public function createInboundBoxHako()
    {
        $this->urlAndWait('/inbound/box/add');
        //-入力画面
        // タイトルと選択
        $title = 'box_title_' .  date("YmdHis");
        $this->firstEl('.hako-box input[type=text]')->value($title);
        $this->firstEl('.hako-box input[type=checkbox]')->click();
        // 預け入れ方法
        $this->selectOption('#InboundDeliveryCarrier');
        // 確定ボタン
        $this->firstEl('#InboundAddForm button[type=submit]')->click();
        $this->waitPageLoad();
        //-確認画面
        $els = $this->allEl('.agree-before-submit[type="checkbox"]');
        $els[0]->click();
        $els[1]->click();
        $els[2]->click();
        $this->firstEl('#InboundConfirmForm button[type=submit]')->click();
        $this->waitPageLoad();
    }

    public function createOutboundBoxHako()
    {
        $this->urlAndWait('/outbound/box/');
        //-持ち出しリスト
        $this->lastEl('.outbound_select_checkbox input[type=checkbox]')->click();
        $this->firstEl('#OutboundBoxForm button[type=submit]')->click();
        $this->waitPageLoad();
        //-入力画面
        // お届け先 お届希望日時
        $this->selectOption('#OutboundAddressId');
        $this->selectOption('#OutboundDatetimeCd', null, 5);
        // 確定ボタン
        $this->firstEl('#OutboundIndexForm button[type=submit]')->click();
        $this->waitPageLoad();
        //-確認画面
        // 確定ボタン
        $this->firstEl('#OutboundConfirmForm button[type=submit]')->click();
        $this->waitPageLoad();
    }

    public function donefirstOrderId()
    {
        $this->url('/dev/');
        $this->waitPageLoad();
        $this->firstEl('.order_id dd a')->click();
    }
    public function donefirstInboundBoxId()
    {
        $this->url('/dev/');
        $this->waitPageLoad();
        $this->firstEl('.inbound_box_id dd a')->click();
    }
    public function donefirstOutboundWorkId()
    {
        $this->url('/dev/');
        $this->waitPageLoad();
        $this->firstEl('.outbound_work_id dd a')->click();
    }
}