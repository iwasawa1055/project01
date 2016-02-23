<?php

require_once TESTS .'Case\MinikuraTestCase.php';

class LoginTest extends MinikuraTestCase
{

    public function setUpPage()
    {
        $this->urlAndWait('/login');
    }

    /**
     * @test
     */
    public function メールアドレス未入力()
    {
        $this->byName('data[CustomerLogin][email]')->value('');
        $this->byName('data[CustomerLogin][password]')->value('happyhappy');
        $this->byXPath("//button[@type='submit']")->click();
        $this->waitPageLoad();

        $result = $this->byCssSelector("p.error-message")->text();
        $this->assertEquals("メールアドレスは必須です", $result);
    }

    /**
     * @test
     */
    public function パスワード入力未入力()
    {
        $this->byName('data[CustomerLogin][email]')->value('150@example.com');
        $this->byName('data[CustomerLogin][password]')->value('');
        $this->byXPath("//button[@type='submit']")->click();
        $this->waitPageLoad();

        $result = $this->byCssSelector("p.error-message")->text();
        $this->assertEquals("パスワードは必須です", $result);
    }

    /**
     * @test
     */
    public function 入力値誤り()
    {
        $this->byName('data[CustomerLogin][email]')->value('150aaaaaaaaaaaasssssssssssddddddddd@example.com');
        $this->byName('data[CustomerLogin][password]')->value('happyhappy');
        $this->byXPath("//button[@type='submit']")->click();
        $this->waitPageLoad();

        $result = $this->byId("flashMessage")->text();
        $this->assertEquals("メールアドレスまたはパスワードに誤りがあるか、登録されていません。", $result);
    }

    /**
     * @test
     */
    public function ログイン成功()
    {
        $this->byName('data[CustomerLogin][email]')->value('150@terrada.co.jp');
        $this->byName('data[CustomerLogin][password]')->value('happyhappy');
        $this->byXPath("//button[@type='submit']")->click();
        $this->waitPageLoad();

        $result = $this->byCssSelector('h1.page-header')->text();
        $this->assertEquals('マイページ', $result);
    }
}
