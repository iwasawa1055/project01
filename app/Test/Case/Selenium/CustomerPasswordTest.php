<?php

require 'MinikuraTestCase.php';

class CustomerPasswordTest extends MinikuraTestCase
{
    public function testInvalidWithoutRequired()
    {
        $this->setLogin();

        $this->url('/customer/password/edit');
        $this->waitPageLoad();

        $this->byId("CustomerPasswordPassword")->clear();
        $this->byId("CustomerPasswordNewPassword")->clear();
        $this->byId("CustomerPasswordNewPasswordConfirm")->clear();

        $this->byXPath("(//button[@type='submit'])[2]")->click();
        $this->waitPageLoad();

        $elements = $this->elements($this->using('css selector')->value('p.error-message'));
        $this->assertEquals("現在のパスワードは必須です", $elements[0]->text());
        $this->assertEquals("新しいパスワードは必須です", $elements[1]->text());
        $this->assertEquals("新しいパスワード（再入力）は必須です", $elements[2]->text());
    }

    public function testInvalidFormat()
    {
        $this->setLogin();

        $this->url('/customer/password/edit');
        $this->waitPageLoad();

        $this->byId('CustomerPasswordPassword')->value('asdf');
        $this->byId('CustomerPasswordNewPassword')->value('asdf');
        $this->byId('CustomerPasswordNewPasswordConfirm')->value('asdf');

        $this->byXPath("(//button[@type='submit'])[2]")->click();
        $this->waitPageLoad();

        $elements = $this->elements($this->using('css selector')->value('p.error-message'));
        $this->assertEquals("新しいパスワードの形式が正しくありません", $elements[0]->text());
        $this->assertEquals("新しいパスワード（再入力）の形式が正しくありません", $elements[1]->text());
    }

    public function testInvalidConfirm()
    {
        $this->setLogin();

        $this->url('/customer/password/edit');
        $this->waitPageLoad();

        $this->byId('CustomerPasswordPassword')->value('happyhappy');
        $this->byId('CustomerPasswordNewPassword')->value('happyhappy');
        $this->byId('CustomerPasswordNewPasswordConfirm')->value('happyhappy123');

        $this->byXPath("(//button[@type='submit'])[2]")->click();
        $this->waitPageLoad();

        $elements = $this->elements($this->using('css selector')->value('p.error-message'));
        $this->assertEquals("新しいパスワード（再入力）が一致していません", $elements[0]->text());
    }

    public function testSuccess()
    {
        $expectedPassword = 'happyhappy';
        $expectedNewPassword = 'happyhappy';
        $expectedNewPasswordConfirm = 'happyhappy';

        $this->setLogin();

        // 入力画面
        $this->url('/customer/password/edit');
        $this->waitPageLoad();

        $result = $this->byCssSelector('h1.page-header')->text();
        $this->assertEquals('パスワード変更', $result);

        // データ入力
        $this->byId('CustomerPasswordPassword')->value($expectedPassword);
        $this->byId('CustomerPasswordNewPassword')->value($expectedNewPassword);
        $this->byId('CustomerPasswordNewPasswordConfirm')->value($expectedNewPasswordConfirm);

        $this->byXPath("(//button[@type='submit'])[2]")->click();
        $this->waitPageLoad();

        // 完了画面
        $result = $this->byCssSelector('h1.page-header')->text();
        $this->assertEquals('パスワード変更', $result);

        $result = $this->byCssSelector('p.form-control-static')->text();
        $this->assertEquals('パスワードの変更が完了しました。', $result);
    }
}
