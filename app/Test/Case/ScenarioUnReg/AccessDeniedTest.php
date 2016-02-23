<?php

require_once TESTS .'Case\MinikuraTestCase.php';

class AccessDeniedTest extends MinikuraTestCase
{

    public function setUpPage()
    {
        $this->setLogout();
    }

    /**
     * @test
     */
    public function キット購入()
    {
        $this->urlAndWait('/order/add');
        $this->assertStringEndsWith('/login', $this->url());
    }
    /**
     * @test
     */
    public function 入庫()
    {
        $this->urlAndWait('/inbound/box/add');
        $this->assertStringEndsWith('/login', $this->url());
    }
    /**
     * @test
     */
    public function 出庫()
    {
        $this->urlAndWait('/outbound/box');
        $this->assertStringEndsWith('/login', $this->url());
    }
    /**
     * @test
     */
    public function 契約情報()
    {
        $this->urlAndWait('/contract');
        $this->assertStringEndsWith('/login', $this->url());
    }
}
