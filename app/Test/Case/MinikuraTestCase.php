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
        usleep(1.5 * 1000000);
    }

    public function urlAndWait($url)
    {
        $this->url($url);
        $this->waitPageLoad();
    }

    // 失敗時
    public function onNotSuccessfulTest(Exception $e)
    {
        $this->takeScreenshot('notSuccess');
        parent::onNotSuccessfulTest($e);
    }

    public function takeScreenshot($name)
    {
        $basePath = TESTS . 'out/' . $this->getBrowser() . '-' . get_class($this);
        $fileName = "${basePath}-${name}-" . time() . '.png';

        $filedata = $this->currentScreenshot();
        file_put_contents($fileName, $filedata);
    }

    // テスト実施前ログイン
    public function setLogin()
    {
        $this->urlAndWait('/login');
        $this->byName('data[CustomerLogin][email]')->value('150@terrada.co.jp');
        $this->byName('data[CustomerLogin][password]')->value('happyhappy');
        $this->byXPath("//button[@type='submit']")->click();
    }
    public function setLogout()
    {
        $this->urlAndWait('/login/logout');
    }

    public function getCurrentUrlPath()
    {
        return '/'.str_replace($this->getBrowserUrl(), '', $this->url());
    }


    protected function firstEl($css)
    {
        return $this->byCssSelector($css);
    }
    protected function lastEl($css)
    {
        $els = $this->elements($this->using('css selector')->value('.outbound_select_checkbox input[type=checkbox]'));
        return end($els);
    }
    protected function selectEl($css)
    {
        $el = $this->firstEl($css);
        return $this->select($el);
    }
    protected function selectOption($css, $index = null, $tryCount = 1)
    {
        $values = [];
        $sl = $this->selectEl($css);
        for (;0 < $tryCount; $tryCount--) {
            $values = $sl->selectOptionLabels();
            if (0 < count($values)) {
                break;
            }
            $this->waitPageLoad();
        }

        if (empty($index)) {
            $index = count($values) - 1;
        }
        $sl->selectOptionByLabel($values[$index]);
    }
    protected function getLongText()
    {
        return <<<EOT
雨＝きゅうりってゅうコト。。。
９割以上が水分。。。
浅漬けにしょ。。。。
雨ってゅうのゎ。。
空から降る一億のきゅうり。。。
そしてきゅうりも、９割以上が水分。。。
浅漬けにしょ。。。。
空から降る一億のきゅうり。。。
もぅﾏﾁﾞ無理。。。
空から降る一億のきゅうり。。。
空から降る一億のきゅうり。。。
９割以上が水分。。。
雨ってゅうのゎ。。
雨＝きゅうりってゅうコト。。。
浅漬けにしょ。。。。
９割以上が水分。。。
もぅﾏﾁﾞ無理。。。
これゎもぅ。。。
９割以上が水分。。。
これゎもぅ。。。
雨ってゅうのゎ。。
空から降る一億のきゅうり。。。
これゎもぅ。。。
そしてきゅうりも、９割以上が水分。。。
もぅﾏﾁﾞ無理。。。
そしてきゅうりも、９割以上が水分。。。
これゎもぅ。。。
そぅ。。
そしてきゅうりも、９割以上が水分。。。
これゎもぅ。。。
そぅ。。
そぅ。。
そぅ。。
雨＝きゅうりってゅうコト。。。
もぅﾏﾁﾞ無理。。。
雨ってゅうのゎ。。
９割以上が水分。。。
浅漬けにしょ。。。。
もぅﾏﾁﾞ無理。。。
雨ってゅうのゎ。。
浅漬けにしょ。。。。
そしてきゅうりも、９割以上が水分。。。
そぅ。。
雨＝きゅうりってゅうコト。。。
雨＝きゅうりってゅうコト。。。
EOT;
    }
}
