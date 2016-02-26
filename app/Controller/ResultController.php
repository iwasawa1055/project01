<?php

App::uses('MinikuraController', 'Controller');
App::uses('Announcement', 'Model');
App::uses('InfoBox', 'Model');
App::uses('InfoItem', 'Model');

class ResultController extends MinikuraController
{
    /**
     *
     */
    public function index()
    {
        $maxCount = 8;
        $keyword = Hash::get($this->request->data, 'keyword');

        // お知らせ
        $res = $this->Announcement->apiGet();
        if ($res->isSuccess()) {
            $hits = [];
            foreach ($res->results as $d) {
                $haystack = implode([
                    $d['announcement_id'],
                    $d['date'],
                    $d['title'],
                    $d['text'],
                ]);
                if (!empty($keyword) && strpos(implode($d), $keyword) != false) {
                    $hits[] = $d;
                    if ($maxCount < count($hits)) {
                        break;
                    }
                }
            }
            $this->set('announcementList', $hits);
        }

        // アイテム
        $o = new InfoItem();
        $res = $o->apiGet();
        if ($res->isSuccess()) {
            $hits = [];
            foreach ($res->results as $d) {
                $haystack = implode([
                    $d['item_id'],
                    $d['box_id'],
                    $d['box_name'],
                    $d['item_note'],
                    $d['item_name'],
                ]);
                if (!empty($keyword) && strpos($haystack, $keyword) !== false) {
                    $hits[] = $d;
                    if ($maxCount < count($hits)) {
                        break;
                    }
                }
            }
            $this->set('itemList', $hits);
        }


        // ボックス
        $o = new InfoBox();
        $res = $o->apiGet();
        if ($res->isSuccess()) {
            $hits = [];
            foreach ($res->results as $d) {
                $haystack = implode([
                    $d['kit_name'],
                    $d['product_name'],
                    $d['box_id'],
                    $d['box_name'],
                    $d['box_note'],
                ]);
                if (!empty($keyword) && strpos($haystack, $keyword) !== false) {
                    $hits[] = $d;
                    if ($maxCount < count($hits)) {
                        break;
                    }
                }
            }
            $this->set('boxList', $hits);
        }
    }
}
