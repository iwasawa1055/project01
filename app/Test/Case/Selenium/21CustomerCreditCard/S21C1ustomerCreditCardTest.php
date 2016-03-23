<?php

require_once TESTS . 'Case/Selenium/MinikuraTestCase.php';
require_once TESTS . 'Case/Selenium/Helper/CustomerCreditCardHelper.php';

class S21C1ustomerCreditCardTest extends MinikuraTestCase
{
    protected $loginEmail = '201603221051@example.com';
    protected $loginPassword = '123123';

    private $helper;

    public function setUpPage()
    {
        $this->login();
        $this->helper = new CustomerCreditCardHelper($this);
    }

    /**
     * @test
     */
    public function 未入力()
    {
        $expected = [
            'card_no' => '',
            'security_cd' => '',
            'holder_name' => '',
        ];
        $this->helper->accessEdit();
        $this->helper->setEditFrom($expected);
        $this->helper->clickEditSubmit();

        $elements = $this->elements($this->using('css selector')->value('p.error-message'));
        $this->assertEquals("クレジットカード番号は必須です", $elements[0]->text());
        $this->assertEquals("セキュリティコードは必須です", $elements[1]->text());
        $this->assertEquals("クレジットカード名義は必須です", $elements[2]->text());
    }

    /**
     * @test
     */
    public function 形式不正()
    {
        $expected = [
            'card_no' => '41111111111111aa',
            'security_cd' => 'asdf',
            'holder_name' => 'せい めい',
        ];
        $this->helper->accessEdit();
        $this->helper->setEditFrom($expected);
        $this->helper->clickEditSubmit();

        $elements = $this->elements($this->using('css selector')->value('p.error-message'));
        $this->assertEquals("クレジットカード番号の形式が正しくありません", $elements[0]->text());
        $this->assertEquals("セキュリティコードの形式が正しくありません", $elements[1]->text());
        $this->assertEquals("クレジットカード名義の形式が正しくありません", $elements[2]->text());
    }

    /**
     * @test
     */
    public function 変更成功()
    {
        $expected = [
            'card_no' => '4111111111111111',
            'security_cd' => '1111',
            'expire_month' => 10,
            'expire_year' => 15,
            'holder_name' => 'TEST TEST' . time(),
        ];
        $this->helper->accessEdit();
        // データ入力
        $this->helper->setEditFrom($expected);
        $this->helper->clickEditSubmit();

        // 確認画面
        $elements = $this->elements($this->using('css selector')->value('div.form-group.col-lg-12 > p'));

        // assert
        $this->assertEquals("/customer/credit_card/edit/confirm", $this->getCurrentUrlPath());
        $this->assertEquals($expected['card_no'], $elements[0]->text());
        $this->assertEquals($expected['security_cd'], $elements[1]->text());
        // $this->assertEquals("{$expectedExpireMonth}月/20{$expectedExpireYear}年", $elements[2]->text());
        $this->assertEquals($expected['holder_name'], $elements[3]->text());


        $this->helper->clickConfirmSubmit();

        // 完了画面
        $result = $this->byCssSelector('h1.page-header')->text();
        $this->assertEquals('クレジットカード変更', $result);

        $result = $this->byCssSelector('p.form-control-static')->text();
        $this->assertEquals('クレジットカードの変更が完了しました。', $result);
        $this->waitPageLoad();

        // 入力画面
        $this->helper->accessEdit();

        $actualCardNo = $this->byId('PaymentGMOSecurityCardCardNo')->value();
        $this->assertEquals('*************111', $actualCardNo);

        $actualSecurityCd = $this->byId('PaymentGMOSecurityCardSecurityCd')->value();
        $this->assertEmpty($actualSecurityCd);

        $actualHolderName = $this->byId('PaymentGMOSecurityCardHolderName')->value();
        $this->assertEquals($expected['holder_name'], $actualHolderName);
    }
}
