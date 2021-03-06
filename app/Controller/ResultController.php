<?php

App::uses('MinikuraController', 'Controller');
App::uses('Announcement', 'Model');
App::uses('InfoBox', 'Model');
App::uses('InfoItem', 'Model');
App::uses('GlobalSreach', 'Model');

class ResultController extends MinikuraController
{
    /**
     *
     */
    public function index()
    {
        $maxCount = 10;

        $this->set('announcementList', []);
        $this->set('itemList', []);
        $this->set('boxList', []);

        $o = new GlobalSreach();
        $o->set($this->request->data);
        if (!$o->validates()) {
            return $this->render('index');
        }
        // キーワード
        $keyword = $o->toArray()['keyword'];

        // お知らせ
        $hits = [];
        $list = $this->Announcement->apiGetResults();
        foreach ($list as $d) {
            $haystack = implode([
                $d['announcement_id'],
                $d['date'],
                $d['title'],
                $d['text'],
            ]);
            if (!empty($keyword) && strpos(implode($d), $keyword) != false) {
                $hits[] = $d;
                if ($maxCount <= count($hits)) {
                    break;
                }
            }
        }
        $this->set('announcementList', $hits);

        // アイテム
        $o = new InfoItem();
        $hits = [];
        if (!$this->Customer->isEntry()) {
            $list = $o->apiGetResults();
            foreach ($list as $d) {
                $haystack = implode([
                    $d['item_id'],
                    $d['box_id'],
                    $d['box_name'],
                    $d['item_note'],
                    $d['item_name'],
                ]);
                if (!empty($keyword) && strpos($haystack, $keyword) !== false) {
                    $hits[] = $d;
                    if ($maxCount <= count($hits)) {
                        break;
                    }
                }
            }
        }
        $this->set('itemList', $hits);


        // ボックス
        $o = new InfoBox();
        $hits = [];
        if (!$this->Customer->isEntry()) {
            $list = $o->apiGetResults();
            foreach ($list as $d) {
                $haystack = implode([
                    $d['kit_name'],
                    $d['product_name'],
                    $d['box_id'],
                    $d['box_name'],
                    $d['box_note'],
                ]);
                if (!empty($keyword) && strpos($haystack, $keyword) !== false) {
                    $hits[] = $d;
                    if ($maxCount <= count($hits)) {
                        break;
                    }
                }
            }
        }
        $this->set('boxList', $hits);
    }
}
