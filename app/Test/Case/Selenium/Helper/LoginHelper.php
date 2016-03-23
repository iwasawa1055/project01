<?php
require_once 'SeleniumHelper.php';

class LoginHelper extends SeleniumHelper
{
    public function accessIndex()
    {
        $this->test->urlAndWait('/login');
    }

    public function setIndexFrom($data)
    {
        if (array_key_exists('email', $data)) {
            $this->test->firstEl('#CustomerLoginEmail')->clear();
            $this->test->firstEl('#CustomerLoginEmail')->value($data['email']);
        }
        if (array_key_exists('password', $data)) {
            $this->test->firstEl('#CustomerLoginPassword')->clear();
            $this->test->firstEl('#CustomerLoginPassword')->value($data['password']);
        }
    }
    public function getIndexFrom()
    {
        $data = [
            'email' => $this->test->firstEl('#CustomerLoginEmail')->value(),
            'password' => $this->test->firstEl('#CustomerLoginPassword')->value(),
        ];
        return $data;
    }

    public function clickIndexSubmit()
    {
        $this->test->firstEl('#CustomerLoginIndexForm button[type=submit]')->click();
        $this->test->waitPageLoad();
    }
}
