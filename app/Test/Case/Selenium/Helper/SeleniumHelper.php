<?php

class SeleniumHelper
{
    protected $test = null;

    public function __construct($testCase)
    {
        $this->test = $testCase;
    }

    protected function clickElByText($text, $css = 'a.btn')
    {
        $els = $this->test->allEl($css);
        foreach ($els as $el) {
            if ($el->text() == $text) {
                $el->click();
                $this->test->waitPageLoad();
                return;
            }
        }
        $this->test->assertTrue(false, 'notfound btn, text: ' . $text);
    }
}
