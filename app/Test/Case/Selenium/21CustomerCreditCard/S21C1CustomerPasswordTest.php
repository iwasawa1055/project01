<?php

require_once TESTS . 'Case/Selenium/MinikuraTestCase.php';
require_once TESTS . 'Case/Selenium/Helper/CustomerPasswordHelper.php';

class S21C1CustomerPasswordTest extends MinikuraTestCase
{
    protected $loginEmail = '201603221051@example.com';
    protected $loginPassword = '123123';

    private $helper;

    public function setUpPage()
    {
        $this->login();
        $this->helper = new CustomerPasswordHelper($this);
    }

    /**
     * @test
     */
    public function 未入力()
    {
        $this->helper->accessEdit();
        $this->helper->clickEditSubmit();

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
        $expected = [
            'password' => 'asdf',
            'new_password' => 'asdf',
            'new_password_confirm' => 'asdf',
        ];

        $this->helper->accessEdit();
        $this->helper->setEditFrom($expected);
        $this->helper->clickEditSubmit();

        $elements = $this->elements($this->using('css selector')->value('p.error-message'));
        $this->assertEquals("新しいパスワードの形式が正しくありません", $elements[0]->text());
        $this->assertEquals("新しいパスワード（再入力）の形式が正しくありません", $elements[1]->text());
    }

    /**
     * @test
     */
    public function 確認入力不一致()
    {
        $expected = [
            'password' => $this->loginPassword,
            'new_password' => $this->loginPassword,
            'new_password_confirm' => $this->loginPassword . '123',
        ];

        $this->helper->accessEdit();
        $this->helper->setEditFrom($expected);
        $this->helper->clickEditSubmit();


        $elements = $this->elements($this->using('css selector')->value('p.error-message'));
        $this->assertEquals("新しいパスワード（再入力）が一致していません", $elements[0]->text());
    }

    /**
     * @test
     */
    public function 変更成功()
    {
        $expected = [
            'password' => $this->loginPassword,
            'new_password' => $this->loginPassword,
            'new_password_confirm' => $this->loginPassword,
        ];

        $this->helper->accessEdit();
        $this->helper->setEditFrom($expected);
        $this->helper->clickEditSubmit();

        // 完了画面
        $this->assertPageHeader('パスワード変更');

        $result = $this->byCssSelector('p.form-control-static')->text();
        $this->assertEquals('パスワードの変更が完了しました。', $result);
    }
}
