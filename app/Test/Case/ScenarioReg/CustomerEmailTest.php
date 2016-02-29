<?php

require_once TESTS .'Case/MinikuraTestCase.php';

class CustomerEmailTest extends MinikuraTestCase
{
    public function setUpPage()
    {
        $this->setLogin();
        $this->urlAndWait('/customer/email/edit');

        $this->firstEl("#CustomerEmailEmail")->clear();
        $this->firstEl("#CustomerEmailEmailConfirm")->clear();
    }

    /**
     * @test
     */
    public function 表示()
    {
        $url = '/customer/email/edit';

        $this->assertRegExp('/^メールアドレス変更.+/', $this->title());
        $this->assertStringEndsWith($url, $this->url());
    }

    /**
     * @test
     */
    public function クリア()
    {
        $this->firstEl('#CustomerEmailEmail')->value('asdf');
        $this->firstEl('#CustomerEmailEmailConfirm')->value('asdf');

        $this->firstEl('a.btn')->click();
        $this->waitPageLoad();

        $this->assertStringEndsWith('', $this->firstEl('#CustomerEmailEmail')->value());
        $this->assertStringEndsWith('', $this->firstEl('#CustomerEmailEmailConfirm')->value());
    }

    /**
     * @test
     */
    public function 未入力()
    {
        $this->firstEl('#CustomerEmailCustomerEditForm button[type=submit]')->click();
        $this->waitPageLoad();

        $this->assertRegExp('/.+必須.+/', $this->firstEl('#CustomerEmailEmail + p')->text());
        $this->assertRegExp('/.+必須.+/', $this->firstEl('#CustomerEmailEmailConfirm + p')->text());
    }

    /**
     * @test
     */
    public function 形式不正()
    {
        $this->firstEl('#CustomerEmailEmail')->value('asdf');
        $this->firstEl('#CustomerEmailEmailConfirm')->value('asdf');

        $this->firstEl('#CustomerEmailCustomerEditForm button[type=submit]')->click();
        $this->waitPageLoad();

        $this->assertRegExp('/.+形式.+/', $this->firstEl('#CustomerEmailEmail + p')->text());
        $this->assertRegExp('/.+形式.+/', $this->firstEl('#CustomerEmailEmailConfirm + p')->text());
    }

    /**
     * @test
     */
    public function 確認入力不一致()
    {
        $this->firstEl('#CustomerEmailEmail')->value('aaaaaassssss@example.com');
        $this->firstEl('#CustomerEmailEmailConfirm')->value('xxxxxxxx@example.com');

        $this->firstEl('#CustomerEmailCustomerEditForm button[type=submit]')->click();
        $this->waitPageLoad();

        $this->assertRegExp('/.+一致.+/', $this->firstEl('#CustomerEmailEmailConfirm + p')->text());
    }

    /**
     * @test
     */
    public function 確認から戻る()
    {
        $newEmail = '150@terrada.co.jp';
        $newEmailConfirm = '150@terrada.co.jp';

        // データ入力
        $this->firstEl('#CustomerEmailEmail')->value($newEmail);
        $this->firstEl('#CustomerEmailEmailConfirm')->value($newEmailConfirm);

        $this->firstEl('#CustomerEmailCustomerEditForm button[type=submit]')->click();
        $this->waitPageLoad();

        // 確認画面
        $this->assertStringEndsWith('/customer/email/confirm', $this->url());
        $this->firstEl('a.btn')->click();
        $this->waitPageLoad();

        $this->assertStringEndsWith($newEmail, $this->firstEl('#CustomerEmailEmail')->value());
        $this->assertStringEndsWith($newEmailConfirm, $this->firstEl('#CustomerEmailEmailConfirm')->value());
    }

    /**
     * @test
     */
    public function 変更成功()
    {
        $newEmail = '150@terrada.co.jp';
        $newEmailConfirm = '150@terrada.co.jp';

        // データ入力
        $this->firstEl('#CustomerEmailEmail')->value($newEmail);
        $this->firstEl('#CustomerEmailEmailConfirm')->value($newEmailConfirm);

        $this->firstEl('#CustomerEmailCustomerEditForm button[type=submit]')->click();
        $this->waitPageLoad();

        // 確認画面
        $this->assertStringEndsWith('/customer/email/confirm', $this->url());
        $this->firstEl('#customer_confirmForm button[type=submit]')->click();
        $this->waitPageLoad();

        // 完了画面
        $this->assertStringEndsWith('/customer/email/complete', $this->url());

        // return my page
        $this->firstEl('a.btn')->click();
        $this->waitPageLoad();
        $this->assertStringEndsWith('/', $this->url());
    }
}
