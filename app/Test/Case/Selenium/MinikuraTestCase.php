<?php

class MinikuraTestCase extends PHPUnit_Extensions_Selenium2TestCase
{
    public $parameters = [
        'seleniumServerRequestsTimeout' => 30000,
        'timeout' => 30000,
    ];

    public function setUp()
    {
        $this->setHost('hub');
        $this->setPort(4444);
        $this->setBrowser('chrome');
        $this->setBrowserUrl('http://minikura/');
    }

    public function waitPageLoad()
    {
        // wait
        usleep(0.5 * 1000000);
    }

    // 失敗時
    public function onNotSuccessfulTest(Exception $e)
    {
        $this->takeScreenshot('notSuccess');
        parent::onNotSuccessfulTest($e);
    }

    public function takeScreenshot($name)
    {
        $basePath = __DIR__ . '/' . $this->getBrowser() . '-' . get_class($this);
        $fileName = "${basePath}-${name}-" . time() . '.png';

        $filedata = $this->currentScreenshot();
        file_put_contents($fileName, $filedata);
    }
}