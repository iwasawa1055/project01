<?php

require_once TESTS . 'Selenium\MinikuraTestCase.php';
require_once TESTS . 'Selenium\Helper\CustomerRegisterHelper.php';

class A1CustomerRegisterTest extends MinikuraTestCase
{
    private $helper;

    public function setUpPage()
    {
        $this->setLogout();
        $this->helper = new CustomerRegisterHelper();
        $this->helper->accessAdd();
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
        $expected = [
            'email' => time() . '@example.com',
            'password' => '123123',
            'password_confirm' => '123123',
        ];

        // echo $expectedMail;

        $this->helper->setAddFrom($expected);
        $this->helper->checkAddCheckbox();
        $this->helper->clickAddSubmit();

        $this->helper->clickCompleteReturn();
    }
}
