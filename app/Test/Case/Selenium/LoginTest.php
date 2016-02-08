<?php

require 'MinikuraTestCase.php';

class LoginTest extends MinikuraTestCase
{
    public function testInvalidWithoutEmail()
    {
        $this->url('/login');
        $this->waitPageLoad();
        $this->byName('data[CustomerLogin][email]')->value('');
        $this->byName('data[CustomerLogin][password]')->value('happyhappy');
        $this->byXPath("//button[@type='submit']")->click();
        $this->waitPageLoad();

        $result = $this->byCssSelector("p.error-message")->text();
        $this->assertEquals("メールアドレスは必須です", $result);
    }

    public function testInvalidWithoutPassword()
    {
        $this->url('/login');
        $this->waitPageLoad();
        $this->byName('data[CustomerLogin][email]')->value('150@terrada.co.jp');
        $this->byName('data[CustomerLogin][password]')->value('');
        $this->byXPath("//button[@type='submit']")->click();
        $this->waitPageLoad();

        $result = $this->byCssSelector("p.error-message")->text();
        $this->assertEquals("パスワードは必須です", $result);
    }

    public function testInvalidUserNotRegistered()
    {
        $this->url('/login');
        $this->waitPageLoad();
        $this->byName('data[CustomerLogin][email]')->value('150aaaaaaaaaaaasssssssssssddddddddd@terrada.co.jp');
        $this->byName('data[CustomerLogin][password]')->value('happyhappy');
        $this->byXPath("//button[@type='submit']")->click();
        $this->waitPageLoad();

        $result = $this->byId("flashMessage")->text();
        $this->assertEquals("メールアドレスまたはパスワードに誤りがあるか、登録されていません。", $result);
    }

    public function testLoginSuccess()
    {
        $this->url('/login');
        $this->waitPageLoad();
        $this->byName('data[CustomerLogin][email]')->value('150@terrada.co.jp');
        $this->byName('data[CustomerLogin][password]')->value('happyhappy');
        $this->byXPath("//button[@type='submit']")->click();
        $this->waitPageLoad();

        $result = $this->byCssSelector('h1.page-header')->text();
        $this->assertEquals('マイページ', $result);
    }
}
