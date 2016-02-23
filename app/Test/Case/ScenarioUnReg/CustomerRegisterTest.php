<?php

require_once TESTS .'Case\MinikuraTestCase.php';

class CustomerRegisterTest extends MinikuraTestCase
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
        $url = '/customer/register/add';
        $this->urlAndWait($url);
        $this->assertRegExp('/^ユーザー登録.+/', $this->title());
        $this->assertStringEndsWith($url, $this->url());
    }
}
