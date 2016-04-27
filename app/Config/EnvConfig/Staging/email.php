<?php

class EmailConfig
{
    public $default = array(
        'transport' => 'Smtp',
        'from' => 'test@minikura.com',
        'host' => '192.168.16.119',
        'port' => 25,
        'timeout' => 30,
        // 'username' => 'user',
        // 'password' => 'secret',
        // 'client' => null,
        // 'log' => false,
         'charset' => 'utf-8',
         'headerCharset' => 'utf-8',
    );
}
