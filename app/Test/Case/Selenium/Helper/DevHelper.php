<?php
require_once 'SeleniumHelper.php';

class DevHelper extends SeleniumHelper
{
    public function doneFirstOrderId()
    {
        $this->test->urlAndWait('/dev/customer');
        $this->test->firstEl('.order_id dd a')->click();
    }
    public function doneFirstInboundBoxId()
    {
        $this->test->urlAndWait('/dev/customer');
        $this->test->firstEl('.inbound_box_id dd a')->click();
    }
    public function doneFirstOutboundWorkId()
    {
        $this->test->urlAndWait('/dev/customer');
        $this->test->firstEl('.outbound_work_id dd a')->click();
    }
}
