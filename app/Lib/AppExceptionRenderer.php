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
        if (500 <= $code) {
            $method = 'error500';
        } elseif (400 <= $code) {
            $method = 'error400';
        }
        $this->template = $method;
        $this->method = $method;
        $this->error = $exception;
    }
}
