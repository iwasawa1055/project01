<?php

require_once TESTS . 'Case/Selenium/MinikuraTestCase.php';
require_once TESTS . 'Case/Selenium/Helper/InquiryHelper.php';

class S01A1InquiryTest extends MinikuraTestCase
{
    private $helper;

    public function setUpPage()
    {
        $this->logout();
        $this->helper = new InquiryHelper($this);
    }

    /**
     * @test
     */
    public function 表示()
    {
        $this->helper->accessAdd();
        $this->assertStringEndsWith('/inquiry/add', $this->url());

        $this->assertRegExp('/^お問い合わせ.+/', $this->title());
        // count option
        $this->assertEquals(count(INQUIRY_DIVISION) + 1, count($this->selectEl('#InquiryDivision')->selectOptionLabels()));
    }

    /**
     * @test
     */
    public function 未入力()
    {
        $this->helper->accessAdd();
        $this->assertStringEndsWith('/inquiry/add', $this->url());
        $this->helper->clickAddSubmit();
        $this->assertStringEndsWith('/inquiry/confirm', $this->url());

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
        $expectedData = [
            'lastname' => '手酢',
            'lastname_kana' => 'テス',
            'firstname' => '都',
            'firstname_kana' => 'ト',
            'email' => time() . '@example.com',
            'division' => INQUIRY_DIVISION[16],
            'text' => $this->getLongText(),
        ];

        // access add
        $this->helper->accessAdd();
        $this->assertStringEndsWith('/inquiry/add', $this->url());
        // input data
        $this->helper->setAddFrom($expectedData);
        // add to confirm
        $this->helper->clickAddSubmit();
        $this->assertStringEndsWith('/inquiry/confirm', $this->url());
        // confirm to add
        $this->helper->clickConfirmReturn();
        $this->assertStringEndsWith('/inquiry/add?back=true', $this->url());
        // check input data
        $actualData = $this->helper->getAddFrom();
        $this->assertEquals([], Hash::diff($expectedData, $actualData));
        // add to confirm
        $this->helper->clickAddSubmit();
        $this->assertStringEndsWith('/inquiry/confirm', $this->url());
        // confirm to complete
        $this->helper->checkConfirmCheckbox();
        $this->helper->clickConfirmSubmit();
        $this->assertStringEndsWith('/inquiry/complete', $this->url());
        // return my page
        $this->helper->clickCompleteReturn();
        $this->assertStringEndsWith('/login', $this->url());
    }
}
