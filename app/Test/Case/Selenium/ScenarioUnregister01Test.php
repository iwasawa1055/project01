<?php

require 'MinikuraTestCase.php';

class ScenarioUnregisterTest extends MinikuraTestCase
{

    public function setUpPage()
    {
        $this->setLogout();
    }

    public function testUnregister01()
    {
        $url = '/customer/register/add';
        $this->urlAndWait($url);
        $this->assertRegExp('/^ユーザー登録.+/', $this->title());
        $this->assertStringEndsWith($url, $this->url());
    }

    public function testUnregister02()
    {
        $url = '/inquiry/add';
        $this->urlAndWait($url);
        $this->assertRegExp('/^お問い合わせ.+/', $this->title());
        $this->assertStringEndsWith($url, $this->url());
        // count option
        $this->assertGreaterThanOrEqual(2, count($this->selectEl('#InquiryDivision')->selectOptionLabels()));
    }

    public function testUnregister02_01()
    {
        $url = '/inquiry/add';
        $this->urlAndWait($url);

        $this->firstEl('#InquiryAddForm button[type=submit]')->click();
        $this->waitPageLoad();

        $this->assertRegExp('/.+必須.+/', $this->firstEl('#InquiryLastname + p')->text());
        $this->assertRegExp('/.+必須.+/', $this->firstEl('#InquiryLastnameKana + p')->text());
        $this->assertRegExp('/.+必須.+/', $this->firstEl('#InquiryFirstname + p')->text());
        $this->assertRegExp('/.+必須.+/', $this->firstEl('#InquiryFirstnameKana + p')->text());
        $this->assertRegExp('/.+必須.+/', $this->firstEl('#InquiryEmail + p')->text());
        $this->assertRegExp('/.+必須.+/', $this->firstEl('#InquiryDivision + p')->text());
        $this->assertRegExp('/.+必須.+/', $this->firstEl('#InquiryText + p')->text());
    }

    public function testUnregister02_02()
    {
        $url = '/inquiry/add';
        $this->urlAndWait($url);

        $this->firstEl('#InquiryLastname')->value('手酢');
        $this->firstEl('#InquiryLastnameKana')->value('テス');
        $this->firstEl('#InquiryFirstname')->value('都');
        $this->firstEl('#InquiryFirstnameKana')->value('ト');
        $this->firstEl('#InquiryEmail')->value($this->getName() . '@example.com');
        $this->selectOption('#InquiryDivision');
        $this->firstEl('#InquiryText')->value($this->getLongText());

        $this->firstEl('#InquiryAddForm button[type=submit]')->click();
        $this->waitPageLoad();
        $this->assertStringEndsWith('/inquiry/confirm', $this->url());

        $this->firstEl('.agree-before-submit[type="checkbox"]')->click();
        $this->firstEl('#InquiryConfirmForm button[type=submit]')->click();
        $this->waitPageLoad();

        $this->assertStringEndsWith('/inquiry/complete', $this->url());

        // return my page
        $this->firstEl('a.btn')->click();
        $this->waitPageLoad();
        $this->assertStringEndsWith('/login', $this->url());
    }

    public function testUnregister03()
    {
        $this->urlAndWait('/order/add');
        $this->assertStringEndsWith('/login', $this->url());
    }
    public function testUnregister04()
    {
        $this->urlAndWait('/inbound/box/add');
        $this->assertStringEndsWith('/login', $this->url());
    }
    public function testUnregister05()
    {
        $this->urlAndWait('/outbound/box');
        $this->assertStringEndsWith('/login', $this->url());
    }
    public function testUnregister06()
    {
        $this->urlAndWait('/contract');
        $this->assertStringEndsWith('/login', $this->url());
    }
}
