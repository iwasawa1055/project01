
<?php
App::uses('MinikuraController', 'Controller');
App::uses('PickupController', 'Controller');
App::uses('PickupYamatoDateTime', 'Model');

/**
 *  YAMATO集荷日、時間を取得する
 */
class AjaxController extends MinikuraController
{
    // アクセス許可
    protected $checkLogined = false;

    /**
     * 指定できるヤマト集荷日時を返却
     */
    public function as_getYamatoDatetime()
    {
        $this->autoRender = false;

        if (!$this->request->is('ajax')) {
            return false;
        }

        $pickup_yamato_datetime = new PickupYamatoDateTime();
        $datetime = json_decode($pickup_yamato_datetime->getPickupYamatoDateTime(), true);
        $results = $datetime['results']['contents'];

        return json_encode(compact('results'));
    }
}
