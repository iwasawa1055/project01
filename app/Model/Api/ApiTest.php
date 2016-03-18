<?php

App::uses('AppHttp', 'Lib');
App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class ApiTest extends ApiModel
{
    public function __construct()
    {
        parent::__construct('Test', '/test');
    }
}
