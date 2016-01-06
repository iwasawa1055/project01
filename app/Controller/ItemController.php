<?php

App::uses('AppController', 'Controller');

class ItemController extends AppController
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
        return $this->redirect('/item/detail/1');
    }
}
