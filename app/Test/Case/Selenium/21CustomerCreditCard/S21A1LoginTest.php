<?php

require_once TESTS . 'Case/Selenium/MinikuraTestCase.php';
require_once TESTS . 'Case/Selenium/Helper/LoginHelper.php';

class S21A1LoginTest extends MinikuraTestCase
{
    protected $loginEmail = '201603221051@example.com';
    protected $loginPassword = '123123';

    private $helper;

    public function setUpPage()
    {
        $this->urlAndWait('/login');
        $this->helper = new LoginHelper($this);
        $this->helper->accessIndex();
    }

    /**
     * @test
     */
    public function メールアドレス未入力()
    {
        $expected = [
            'email' => '',
            'password' => $this->loginPassword,
        ];
        $this->helper->setIndexFrom($expected);
        $this->helper->clickIndexSubmit();

        $result = $this->byCssSelector("p.error-message")->text();
        $this->assertEquals("メールアドレスは必須です", $result);
    }

    /**
     * @test
     */
    public function パスワード入力未入力()
    {
        $expected = [
            'email' => $this->loginEmail,
            'password' => '',
        ];
        $this->helper->setIndexFrom($expected);
        $this->helper->clickIndexSubmit();

        $result = $this->byCssSelector("p.error-message")->text();
        $this->assertEquals("パスワードは必須です", $result);
    }

    /**
     * @test
     */
    public function 入力値誤り()
    {
        $expected = [
            'email' => '150aaaaaaaaaaaasssssssssssddddddddd' . $this->loginEmail,
            'password' => $this->loginPassword,
        ];
        $this->helper->setIndexFrom($expected);
        $this->helper->clickIndexSubmit($expected);

        $this->assertFlashMessage("メールアドレスまたはパスワードに誤りがあるか、登録されていません。");
    }

    /**
     * @test
     */
    public function ログイン成功()
    {
        $expected = [
            'email' => $this->loginEmail,
            'password' => $this->loginPassword,
        ];
        $this->helper->setIndexFrom($expected);
        $this->helper->clickIndexSubmit($expected);

        $this->assertPageHeader('マイページ');
    }
}
