<?php

require 'MinikuraTestCase.php';

class CustomerCreditCardTest extends MinikuraTestCase
{
    public function testInvalidWithoutRequired()
    {
        $this->setLogin();
    
        $this->url('/customer/credit_card/edit');
        $this->waitPageLoad();
    
        $this->byId("PaymentGMOSecurityCardCardNo")->clear();
        $this->byId("PaymentGMOSecurityCardSecurityCd")->clear();
        $this->byId("PaymentGMOSecurityCardHolderName")->clear();
    
        $this->byXPath("(//button[@type='submit'])[2]")->click();
        $this->waitPageLoad();
    
        $elements = $this->elements($this->using('css selector')->value('p.error-message'));
        $this->assertEquals("クレジットカード番号は必須です", $elements[0]->text());
        $this->assertEquals("セキュリティコードは必須です", $elements[1]->text());
        $this->assertEquals("クレジットカード名義は必須です", $elements[2]->text());
    }

    public function testInvalidFormat()
    {
        $this->setLogin();
    
        $this->url('/customer/credit_card/edit');
        $this->waitPageLoad();
    
        $this->byId('PaymentGMOSecurityCardCardNo')->clear();
        $this->byId('PaymentGMOSecurityCardSecurityCd')->clear();
        $this->byId('PaymentGMOSecurityCardHolderName')->clear();
    
        $this->byId('PaymentGMOSecurityCardCardNo')->value('41111111111111aa');
        $this->byId('PaymentGMOSecurityCardSecurityCd')->value('asdf');
        $this->byId('PaymentGMOSecurityCardHolderName')->value('せい めい');
    
        $this->byXPath("(//button[@type='submit'])[2]")->click();
        $this->waitPageLoad();
    
        $elements = $this->elements($this->using('css selector')->value('p.error-message'));
        $this->assertEquals("クレジットカード番号の形式が正しくありません", $elements[0]->text());
        $this->assertEquals("セキュリティコードの形式が正しくありません", $elements[1]->text());
        $this->assertEquals("クレジットカード名義の形式が正しくありません", $elements[2]->text());
    }

    public function testSuccessCustomerCreditCard()
    {
        $expectedCardNo = '4111111111111111';
        $expectedSecurityCd = '1111';
        $expectedExpireMonth = '12';
        $expectedExpireYear = '30';
        $expectedHolderName = 'TEST TEST';

        $this->setLogin();
    
        // 入力画面
        $this->url('/customer/credit_card/edit');
        $this->waitPageLoad();
    
        $result = $this->byCssSelector('h1.page-header')->text();
        $this->assertEquals('クレジットカード変更', $result);
    
        // データ入力
        $this->byId('PaymentGMOSecurityCardCardNo')->clear();
        $this->byId('PaymentGMOSecurityCardSecurityCd')->clear();
        $this->byId('PaymentGMOSecurityCardHolderName')->clear();
    
        $this->byId('PaymentGMOSecurityCardCardNo')->value($expectedCardNo);
        $this->byId('PaymentGMOSecurityCardSecurityCd')->value($expectedSecurityCd);
        $this->select($this->byId('PaymentGMOSecurityCardExpireMonth'))->selectOptionByValue($expectedExpireMonth);
        $this->select($this->byId('PaymentGMOSecurityCardExpireYear'))->selectOptionByValue($expectedExpireYear);
        $this->byId('PaymentGMOSecurityCardHolderName')->value($expectedHolderName);
    
        $this->byXPath("(//button[@type='submit'])[2]")->click();
        $this->waitPageLoad();
    
        // 確認画面
        $elements = $this->elements($this->using('css selector')->value('div.form-group.col-lg-12 > p'));
    
        // assert
        $this->assertEquals("/customer/credit_card/confirm", $this->getCurrentUrlPath());
        $this->assertEquals($expectedCardNo, $elements[0]->text());
        $this->assertEquals($expectedSecurityCd, $elements[1]->text());
        $this->assertEquals("{$expectedExpireMonth}月/20{$expectedExpireYear}年", $elements[2]->text());
        $this->assertEquals($expectedHolderName, $elements[3]->text());
    
        $this->byXPath("(//button[@type='submit'])[2]")->click();
        $this->waitPageLoad();
    
        // 完了画面
        $result = $this->byCssSelector('h1.page-header')->text();
        $this->assertEquals('クレジットカード変更', $result);
    
        $result = $this->byCssSelector('p.form-control-static')->text();
        $this->assertEquals('クレジットカードの変更が完了しました。', $result);
    
        // 入力画面
        $this->url('/customer/credit_card/edit');
        $this->waitPageLoad();
    
        $actualCardNo = $this->byId('PaymentGMOSecurityCardCardNo')->value();
        $this->assertEquals('*************111', $actualCardNo);
    
        $actualSecurityCd = $this->byId('PaymentGMOSecurityCardSecurityCd')->value();
        $this->assertEmpty($actualSecurityCd);
    
        $actualExpireMonth = $this->byId('PaymentGMOSecurityCardExpireMonth')->value();
        $this->assertEquals($expectedExpireMonth, $actualExpireMonth);
    
        $actualExpireYear = $this->byId('PaymentGMOSecurityCardExpireYear')->value();
        $this->assertEquals($expectedExpireYear, $actualExpireYear);
    
        $actualHolderName = $this->byId('PaymentGMOSecurityCardHolderName')->value();
        $this->assertEquals($expectedHolderName, $actualHolderName);
    }
}
