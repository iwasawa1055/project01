<?php

require_once TESTS .'Case\MinikuraTestCase.php';

class InquiryTest extends MinikuraTestCase
{

    public function setUpPage()
    {
        $this->setLogout();
    }

    /**
     * @test
     */
    public function 表示()
    {
        $url = '/inquiry/add';
        $this->urlAndWait($url);
        $this->assertRegExp('/^お問い合わせ.+/', $this->title());
        $this->assertStringEndsWith($url, $this->url());
        // count option
        $this->assertGreaterThanOrEqual(2, count($this->selectEl('#InquiryDivision')->selectOptionLabels()));
    }

    /**
     * @test
     */
    public function 未入力()
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

    /**
     * @test
     */
    public function 問合せ成功()
    {
        $url = '/inquiry/add';
        $this->urlAndWait($url);

        $this->firstEl('#InquiryLastname')->value('手酢');
        $this->firstEl('#InquiryLastnameKana')->value('テス');
        $this->firstEl('#InquiryFirstname')->value('都');
        $this->firstEl('#InquiryFirstnameKana')->value('ト');
        $this->firstEl('#InquiryEmail')->value(time() . '@example.com');
        $this->selectOption('#InquiryDivision');
        $this->firstEl('#InquiryText')->value($this->getLongText());

        $this->firstEl('#InquiryAddForm button[type=submit]')->click();
        $this->waitPageLoad();
        $this->assertStringEndsWith('/inquiry/confirm', $this->url());

        $this->firstEl('.agree-before-submit[type="checkbox"]')->click();
        $this->waitPageLoad();
        $this->firstEl('#InquiryConfirmForm button[type=submit]')->click();
        $this->waitPageLoad();

        $this->assertStringEndsWith('/inquiry/complete', $this->url());

        // return my page
        $this->firstEl('a.btn')->click();
        $this->waitPageLoad();
        $this->assertStringEndsWith('/login', $this->url());
    }
}
