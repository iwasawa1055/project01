<?php

App::uses('ExceptionRenderer', 'Error');

class AppExceptionRenderer extends ExceptionRenderer
{
    public function __construct(Exception $exception)
    {
        CakeLog::write(DEBUG_LOG, 'AppExceptionHandler::handle');

        parent::__construct($exception);
        // 400, 500に統一します
        $code = $exception->getCode();
        $method = 'error500';
        if (500 <= $code) {
        } elseif (400 <= $code) {
            $method = 'error400';
        }
        $this->template = $method;
        $this->method = $method;
        $this->error = $exception;
    }
}
