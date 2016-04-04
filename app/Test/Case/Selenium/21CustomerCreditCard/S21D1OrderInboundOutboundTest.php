<?php

require_once TESTS . 'Case/Selenium/MinikuraTestCase.php';
require_once TESTS . 'Case/Selenium/Helper/OrderHelper.php';
require_once TESTS . 'Case/Selenium/Helper/DevHelper.php';
require_once TESTS . 'Case/Selenium/Helper/InboundBoxHelper.php';
require_once TESTS . 'Case/Selenium/Helper/OutboundBoxHelper.php';

class S21D1OrderInboundOutboundTest extends MinikuraTestCase
{
    protected $loginEmail = '201603221051@example.com';
    protected $loginPassword = '123123';

    private $order;
    private $dev;
    private $inboundBox;
    private $outboundBox;

    public function setUpPage()
    {
        $this->order = new OrderHelper($this);
        $this->dev = new DevHelper($this);
        $this->inboundBox = new InboundBoxHelper($this);
        $this->outboundBox = new OutboundBoxHelper($this);
        $this->login();
    }

    /**
     * @test
     */
    public function ボックスをキット購入から出庫まで()
    {
        $this->createOrder();
        $this->dev->doneFirstOrderId();
        $this->createInboundBoxHako();
        $this->dev->doneFirstInboundBoxId();
        $this->createOutboundBoxHako();
        $this->dev->doneFirstOutboundWorkId();
    }

    public function createOrder()
    {
        $expected = [
            'mono_num' => '3',
            'mono_appa_num' => '3',
            'mono_book_num' => '3',
            'hako_num' => '2',
            'hako_appa_num' => '3',
            'hako_book_num' => '3',
            'cleaning_num' => '4',
            'security_cd' => '123',
            'address_id' => 1,
            'datetime_cd' => 1,
        ];
        $this->order->accessAdd();
        $this->order->inputAddFrom($expected);
        $this->order->clickAddSubmit();
        $this->order->checkConfirmCheckbox();
        $this->order->clickConfirmSubmit();
        $this->order->clickCompleteReturn();
    }

    public function createInboundBoxHako()
    {
        $expected = [
            'hako-box_title' => 'box_title_' . date("YmdHis"),
            'hako-box_checkbox' => true,
            'delivery_carrier' => null,
        ];
        $this->inboundBox->accessAdd();
        $this->inboundBox->inputAddFrom($expected);
        $this->inboundBox->clickAddSubmit();
        $this->inboundBox->checkConfirmCheckbox();
        $this->inboundBox->clickConfirmSubmit();
        $this->inboundBox->clickCompleteReturn();
    }

    public function createOutboundBoxHako()
    {
        $this->outboundBox->accessBoxIndex();
        $this->outboundBox->clickBoxSelectFirst();
        $this->outboundBox->clickBoxSubmit();

        $expected = [
            'address_id' => 1,
            'datetime_cd' => 1,
        ];
        $this->outboundBox->inputAddFrom($expected);
        $this->outboundBox->clickAddSubmit();
        $this->outboundBox->clickConfirmSubmit();
        $this->outboundBox->clickCompleteReturn();
    }
}
