<?php

require_once TESTS . 'Case/Selenium/MinikuraTestCase.php';
require_once TESTS . 'Case/Selenium/Helper/CustomerEmailHelper.php';

class S21C1CustomerEmailTest extends MinikuraTestCase
{
    protected $loginEmail = '201603221051@example.com';
    protected $loginPassword = '123123';

    private $helper;

    public function setUpPage()
    {
        $this->login();
        $this->helper = new CustomerEmailHelper($this);
    }

    /**
     * @test
     */
    public function 表示()
    {
        $this->helper->accessEdit();

        $this->assertRegExp('/^メールアドレス変更.+/', $this->title());
        $this->assertStringEndsWith('/customer/email/edit', $this->url());
    }

    /**
     * @test
     */
    public function クリア()
    {
        $expected = [
            'email' => 'asdf',
            'email_confirm' => 'asdf',
        ];

        $this->helper->accessEdit();
        $this->helper->setEditFrom($expected);
        $this->helper->clickEditClear();

        $this->assertStringEndsWith('', $this->firstEl('#CustomerEmailEmail')->value());
        $this->assertStringEndsWith('', $this->firstEl('#CustomerEmailEmailConfirm')->value());
    }

    /**
     * @test
     */
    public function 未入力()
    {
        $expected = [
            'email' => '',
            'email_confirm' => '',
        ];

        $this->helper->accessEdit();
        $this->helper->setEditFrom($expected);
        $this->helper->clickEditSubmit();

        $this->assertRegExp('/.+必須.+/', $this->firstEl('#CustomerEmailEmail + p')->text());
        $this->assertRegExp('/.+必須.+/', $this->firstEl('#CustomerEmailEmailConfirm + p')->text());
    }

    /**
     * @test
     */
    public function 形式不正()
    {
        $expected = [
            'email' => 'asdf',
            'email_confirm' => 'asdf',
        ];

        $this->helper->accessEdit();
        $this->helper->setEditFrom($expected);
        $this->helper->clickEditSubmit();

        $this->assertRegExp('/.+形式.+/', $this->firstEl('#CustomerEmailEmail + p')->text());
        $this->assertRegExp('/.+形式.+/', $this->firstEl('#CustomerEmailEmailConfirm + p')->text());
    }

    /**
     * @test
     */
    public function 確認入力不一致()
    {
        $expected = [
            'email' => 'aaaaaassssss@example.com',
            'email_confirm' => 'xxxxxxxx@example.com',
        ];

        $this->helper->accessEdit();
        $this->helper->setEditFrom($expected);
        $this->helper->clickEditSubmit();

        $this->assertRegExp('/.+一致.+/', $this->firstEl('#CustomerEmailEmailConfirm + p')->text());
    }

    /**
     * @test
     */
    public function 確認から戻る()
    {
        $expected = [
            'email' => $this->loginEmail,
            'email_confirm' => $this->loginEmail,
        ];

        $this->helper->accessEdit();
        $this->helper->setEditFrom($expected);
        $this->helper->clickEditSubmit();
        $this->assertStringEndsWith('/customer/email/confirm', $this->url());

        $this->helper->clickConfirmReturn();
        $actual = $this->helper->getEditFrom();

        $this->assertEquals([], Hash::diff($expected, $actual));
    }

    /**
     * @test
     */
    public function 変更成功()
    {
        $expected = [
            'email' => $this->loginEmail,
            'email_confirm' => $this->loginEmail,
        ];

        $this->helper->accessEdit();
        $this->helper->setEditFrom($expected);
        $this->helper->clickEditSubmit();
        $this->helper->clickConfirmSubmit();
        $this->assertStringEndsWith('/customer/email/complete', $this->url());

        // マイページ
        $this->helper->clickCompleteReturn();
        $this->assertStringEndsWith('/', $this->url());
    }
}
