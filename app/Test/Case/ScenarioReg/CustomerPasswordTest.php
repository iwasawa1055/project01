<?php

require_once TESTS .'Case\MinikuraTestCase.php';

class CustomerPasswordTest extends MinikuraTestCase
{

    public function setUpPage()
    {
        $this->setLogin();
        $this->urlAndWait('/customer/password/edit');

        $this->byId("CustomerPasswordPassword")->clear();
        $this->byId("CustomerPasswordNewPassword")->clear();
        $this->byId("CustomerPasswordNewPasswordConfirm")->clear();
    }

    /**
     * @test
     */
    public function 未入力()
    {

        $this->byXPath("(//button[@type='submit'])[2]")->click();
        $this->waitPageLoad();

        $elements = $this->elements($this->using('css selector')->value('p.error-message'));
        $this->assertEquals("現在のパスワードは必須です", $elements[0]->text());
        $this->assertEquals("新しいパスワードは必須です", $elements[1]->text());
        $this->assertEquals("新しいパスワード（再入力）は必須です", $elements[2]->text());
    }

    /**
     * @test
     */
    public function 形式不正()
    {

        $this->byId('CustomerPasswordPassword')->value('asdf');
        $this->byId('CustomerPasswordNewPassword')->value('asdf');
        $this->byId('CustomerPasswordNewPasswordConfirm')->value('asdf');

        $this->byXPath("(//button[@type='submit'])[2]")->click();
        $this->waitPageLoad();

        $elements = $this->elements($this->using('css selector')->value('p.error-message'));
        $this->assertEquals("新しいパスワードの形式が正しくありません", $elements[0]->text());
        $this->assertEquals("新しいパスワード（再入力）の形式が正しくありません", $elements[1]->text());
    }

    /**
     * @test
     */
    public function 確認入力不一致()
    {
        $this->byId('CustomerPasswordPassword')->value('happyhappy');
        $this->byId('CustomerPasswordNewPassword')->value('happyhappy');
        $this->byId('CustomerPasswordNewPasswordConfirm')->value('happyhappy123');

        $this->byXPath("(//button[@type='submit'])[2]")->click();
        $this->waitPageLoad();

        $elements = $this->elements($this->using('css selector')->value('p.error-message'));
        $this->assertEquals("新しいパスワード（再入力）が一致していません", $elements[0]->text());
    }

    /**
     * @test
     */
    public function 変更成功()
    {
        $expectedPassword = 'happyhappy';
        $expectedNewPassword = 'happyhappy';
        $expectedNewPasswordConfirm = 'happyhappy';

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
