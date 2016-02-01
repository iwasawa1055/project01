<?php

require 'MinikuraTestCase.php';

class WebTest extends MinikuraTestCase
{
    public function testTitle()
    {
        $this->url('/');
        $this->waitPageLoad();

        $this->assertEquals('minikura | minikura', $this->title());
    }
}
