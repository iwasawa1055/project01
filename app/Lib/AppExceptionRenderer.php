<?php
App::uses('ExceptionRenderer', 'Error');

class AppExceptionRenderer extends ExceptionRenderer
{
    public function __construct(Exception $_exception)
    {
        parent::__construct($_exception);
        $this->method = 'errorApp';
    }

    public function errorApp($_error)
    {
        $message = $_error->getMessage();
        $url = $this->controller->request->here();
        $code = $_error->getCode();
        $this->controller->response->statusCode($code);
        $this->controller->response->disableCache();
        $this->controller->set(array(
            'name' => h($message),
            'message' => h($message),
            'url' => h($url),
            'error' => $_error,
            '_serialize' => array('name', 'message', 'url')
        ));
        //* error $codeでview振り分け
        $file_path = ROOT.DS.APP_DIR.DS.'View'.DS.'Errors'.DS.'error'.$code.'.ctp';
        if (file_exists($file_path) && is_file($file_path)) {
            $template = 'error'.$code;
        } else {
            if ($code >= 400 && $code < 500) {
                $template = 'error400';
            } else {
                $template = 'error500';
            }
        }
        $this->_outputMessage($template);
    }
}
