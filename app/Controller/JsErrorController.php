<?php

App::uses('MinikuraController', 'Controller');

class JsErrorController extends MinikuraController
{
    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
    }

    /**
     * フロントからのエラーを受け取り、ログに出力する
     */
    public function index()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        $this->autoRender = false;

        $name = filter_input(INPUT_POST, 'name');
        $error = filter_input(INPUT_POST, 'error');

        CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' ' . $name . ' ' . $error);
    }
}
