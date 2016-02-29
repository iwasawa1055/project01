<?php

require_once TESTS .'Case/MinikuraTestCase.php';

class CustomerPasswordResetTest extends MinikuraTestCase
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
        $url = '/customer/password_reset/add';
        $this->urlAndWait($url);
        $this->assertRegExp('/^パスワード再発行.+/', $this->title());
        $this->assertStringEndsWith($url, $this->url());
    }

    /**
     * @test
     */
    public function ログインに戻る()
    {
        $url = '/customer/password_reset/add';
        $this->urlAndWait($url);

        $this->firstEl('a.btn')->click();
        $this->waitPageLoad();
        $this->assertStringEndsWith('/login', $this->url());
    }

    /**
     * @test
     */
    public function 未入力()
    {
        $url = '/customer/password_reset/add';
        $this->urlAndWait($url);

        $this->firstEl('#CustomerPasswordResetCustomerAddForm button[type=submit]')->click();
        $this->waitPageLoad();

        $this->assertRegExp('/.+必須.+/', $this->firstEl('#CustomerPasswordResetEmail + p')->text());
        $this->assertRegExp('/.+必須.+/', $this->firstEl('#CustomerPasswordResetNewPassword + p')->text());
        $this->assertRegExp('/.+必須.+/', $this->firstEl('#CustomerPasswordResetNewPasswordConfirm + p')->text());
    }

    /**
     * @test
     */
    public function 形式不正()
    {
        $url = '/customer/password_reset/add';
        $this->urlAndWait($url);

        $this->firstEl('#CustomerPasswordResetEmail')->value('asdf');
        $this->firstEl('#CustomerPasswordResetNewPassword')->value('asdf');
        $this->firstEl('#CustomerPasswordResetNewPasswordConfirm')->value('asdf');

        $this->firstEl('#CustomerPasswordResetCustomerAddForm button[type=submit]')->click();
        $this->waitPageLoad();

        $this->assertRegExp('/.+形式.+/', $this->firstEl('#CustomerPasswordResetEmail + p')->text());
        $this->assertRegExp('/.+形式.+/', $this->firstEl('#CustomerPasswordResetNewPassword + p')->text());
        $this->assertRegExp('/.+形式.+/', $this->firstEl('#CustomerPasswordResetNewPasswordConfirm + p')->text());
    }

    /**
     * @test
     */
    public function 確認入力不一致()
    {
        $url = '/customer/password_reset/add';
        $this->urlAndWait($url);

        $this->firstEl('#CustomerPasswordResetNewPassword')->value('aaaaaaaa');
        $this->firstEl('#CustomerPasswordResetNewPasswordConfirm')->value('asdfasdf');

        $this->firstEl('#CustomerPasswordResetCustomerAddForm button[type=submit]')->click();
        $this->waitPageLoad();

        $this->assertRegExp('/.+一致.+/', $this->firstEl('#CustomerPasswordResetNewPasswordConfirm + p')->text());
    }

    /**
     * @test
     */
    public function 確認から戻る()
    {
        $url = '/customer/password_reset/add';
        $this->urlAndWait($url);

        $email = '150@terrada.co.jp';
        $expectedNewPassword = 'happyhappy';
        $expectedNewPasswordConfirm = 'happyhappy';

        // データ入力
        $this->firstEl('#CustomerPasswordResetEmail')->value($email);
        $this->firstEl('#CustomerPasswordResetNewPassword')->value($expectedNewPassword);
        $this->firstEl('#CustomerPasswordResetNewPasswordConfirm')->value($expectedNewPasswordConfirm);

        $this->firstEl('#CustomerPasswordResetCustomerAddForm button[type=submit]')->click();
        $this->waitPageLoad();

        // 確認画面
        $this->assertStringEndsWith('/customer/password_reset/confirm', $this->url());
        $this->firstEl('a.btn')->click();
        $this->waitPageLoad();

        $this->assertStringEndsWith($email, $this->firstEl('#CustomerPasswordResetEmail')->value());
        $this->assertStringEndsWith('', $this->firstEl('#CustomerPasswordResetNewPassword')->value());
        $this->assertStringEndsWith('', $this->firstEl('#CustomerPasswordResetNewPasswordConfirm')->value());
    }

    /**
     * @test
     */
    public function 変更成功()
    {
        $email = '150@terrada.co.jp';
        $expectedNewPassword = 'happyhappy';
        $expectedNewPasswordConfirm = 'happyhappy';

        $url = '/customer/password_reset/add';
        $this->urlAndWait($url);

        // データ入力
        $this->firstEl('#CustomerPasswordResetEmail')->value($email);
        $this->firstEl('#CustomerPasswordResetNewPassword')->value($expectedNewPassword);
        $this->firstEl('#CustomerPasswordResetNewPasswordConfirm')->value($expectedNewPasswordConfirm);

        $this->firstEl('#CustomerPasswordResetCustomerAddForm button[type=submit]')->click();
        $this->waitPageLoad();

        // 確認画面
        $this->assertStringEndsWith('/customer/password_reset/confirm', $this->url());
        $this->firstEl('#customer_confirmForm button[type=submit]')->click();
        $this->waitPageLoad();

        // 完了画面
        $this->assertStringEndsWith('/customer/password_reset/complete', $this->url());

        // return page
        $this->firstEl('a.btn')->click();
        $this->waitPageLoad();
        $this->assertStringEndsWith('/login', $this->url());
    }
}
