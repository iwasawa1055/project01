<?php

App::uses('AppController', 'Controller');

class BoxController extends AppController
{
    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        AppController::beforeFilter();
    }

    /**
     * 一覧.
     */
    public function index()
    {
    }

    /**
     *
     */
    public function detail()
    {
    }

    /**
     *
     */
    public function edit()
    {
    }

    /**
     *
     */
    public function update()
    {
        return $this->redirect('/box/detail/1');
    }

    /**
     *
     */
    public function item()
    {
    }
}
