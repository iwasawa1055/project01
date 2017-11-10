<?php

App::uses('MinikuraController', 'Controller');
App::uses('InfoBox', 'Model');
App::uses('InfoItem', 'Model');
App::uses('News', 'Model');
App::uses('AnnouncementNoCache', 'Model');

class MyPageController extends MinikuraController
{

    /**
     * ルートインデックス.
     */
    public function index()
    {
        $News = new News();

        $newsList = $News->getNews(2);
        $this->set('newsList', $newsList);

        $announcement = new AnnouncementNoCache();

        $res = $announcement->apiGet();

        $count = 0;
        $ret = array();
        foreach ($res->results as $key => $value) {

            // 特定文字の含まれるメッセージは非表示
            if($this->_isNoDispAnnouncement($value['text'])) {
                unset($res->results[$key]);
            } else {
                $count++;
                $ret[] = $res->results[$key];
            }

            // マイページでの表示件数は５
            if($count > 4) {
                break;
            }

        }

        $this->set('notice_announcements', $ret);

    }
}
