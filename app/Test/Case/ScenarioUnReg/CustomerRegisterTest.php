<?php

require_once TESTS .'Case\MinikuraTestCase.php';

class CustomerRegisterTest extends MinikuraTestCase
{

    public function setUpPage()
    {
        $this->setLogout();
        $this->urlAndWait('/customer/register/add');
    }

    /**
     * @test
     */
    public function 表示()
    {
        $this->assertRegExp('/^ユーザー登録.+/', $this->title());
        // $this->assertStringEndsWith($url, $this->url());
    }

    /**
     * @test
     */
    public function 仮登録()
    {
        $expectedMail = time() . '@example.com';
        $expectedPassword = '123123';
        $expectedPasswordConfirm = '123123';

        echo $expectedMail;

        $this->firstEl('#CustomerEntryEmail')->value($expectedMail);
        $this->firstEl('#CustomerEntryPassword')->value($expectedPassword);
        $this->firstEl('#CustomerEntryPasswordConfirm')->value($expectedPasswordConfirm);

        $this->firstEl('.agree-before-submit[type="checkbox"]')->click();
        $this->firstEl('#CustomerEntryCustomerAddForm button[type=submit]')->click();
        $this->waitPageLoad();

        $this->assertEquals('マイページ', $this->firstEl('h1.page-header')->text());

    }
}
