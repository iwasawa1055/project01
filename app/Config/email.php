<?php

class EmailConfig
{
    public $default = array(
        'transport' => 'Smtp',
        'from' => 'dev.minikura@terrada.co.jp',
        'host' => '127.0.0.1',
        'port' => 25,
        'timeout' => 30,
        // 'username' => 'user',
        // 'password' => 'secret',
        // 'client' => null,
        // 'log' => false,
        // 'charset' => 'utf-8',
        // 'headerCharset' => 'utf-8',
    );
}
