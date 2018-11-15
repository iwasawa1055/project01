<?php

App::uses('MinikuraController', 'Controller');
App::uses('Receipt', 'Model');
App::uses('Billing', 'Model');
App::uses('ReceiptDetail', 'Model');
App::uses('PickupYamato', 'Model');

class AnnouncementController extends MinikuraController
{
    const MODEL_NAME = 'Announcement';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
    }

    /**
     * 一覧.
     */
    public function index()
    {
        $all = $this->Announcement->apiGetResults();
        // 特定文字の含まれるメッセージは非表示
        foreach($all as $key => $value) {
            if($this->_isNoDispAnnouncement($value['text'])) {
                unset($all[$key]);
            }
        }
        $list = $this->paginate($all);
        $this->set('announcements', $list);
    }

    /**
     *
     */
    public function detail()
    {
        $id = $this->params['id'];
        $data = $this->Announcement->apiGetResultsFind([], ['announcement_id' => $id]);
        if (!empty($data)) {
            $this->set('announcement', $data);
            $this->Announcement->apiPatch(['announcement_id' => $id]);

            if ($data['category_id'] === ANNOUNCEMENT_CATEGORY_ID_BILLING) {
                $billing = new Billing();
                $res = $billing->apiGet([
                    'announcement_id' => $id,
                    'category_id' => $data['category_id']
                ]);
                if ($res->isSuccess()) {
                    $this->set('billing', $res->results);
                }
            }

            if (in_array($data['category_id'], ANNOUNCEMENT_CATEGORY_YAMATO)) {
                $pickup_yamato = new PickupYamato();
                $res = $pickup_yamato->apiGet([
                    'announcement_id' => $id,
                ]);
                if ($res->isSuccess()) {
                    $pickup_yamato_change = $this->pickupYamatoChangeFlag($res);
                    $this->set('pickup_yamato', $res->results[0]);
                    $this->set('pickup_yamato_change', $pickup_yamato_change);
                }
            }
        }
    }

    /**
     * 領収証ダウンロード
     * @return [type] [description]
     */
    public function receipt()
    {
        $id = $this->params['id'];
        if ($this->request->is('post')) {
            $receipt = new Receipt();
            $data = $this->Announcement->apiGetResultsFind([], ['announcement_id' => $id]);
            if (!empty($data)) {
                if ($data['category_id'] === ANNOUNCEMENT_CATEGORY_ID_RECEIPT) {
                    $res = $receipt->apiGet([
                        'announcement_id' => $id,
                        'category_id' => $data['category_id']
                    ]);
                    if ($res->isSuccess() || count($res->results) === 1) {
                        $name = $res->results[0]['file_name'];
                        $binary = base64_decode($res->results[0]['receipt']);
                        $this->autoRender = false;
                        $this->response->type('pdf');
                        $this->response->download($name);
                        $this->response->body($binary);
                        return;
                    } else {
                        $this->Flash->set($res->error_message);
                    }
                }
                if ($data['category_id'] === ANNOUNCEMENT_CATEGORY_ID_KIT_RECEIPT) {
                    $receiptDetail = new ReceiptDetail();
                    $res = $receiptDetail->apiGet([
                        'announcement_id' => $id
                    ]);
                    if ($res->isSuccess() && count($res->results) === 1) {
                        $timelyReceiptId = $res->results[0]['timely_receipt_id'];
                        $name = "receipt{$timelyReceiptId}.pdf";
                        $binary = base64_decode($res->results[0]['receipt_data']);
                        $this->autoRender = false;
                        $this->response->type('pdf');
                        $this->response->download($name);
                        $this->response->body($binary);
                        return;
                    } else {
                        // $this->Flash->set($res->error_message);
                        $this->Flash->set('領収証を発行できません。お問い合わせフォームにて領収証発行を依頼ください。');
                    }
                }
            }
        }
        return $this->redirect(['action' => 'detail', 'id' => $id]);
    }

    /**
        * メッセージ画面で集荷情報を変更するボタン、活性非活性フラグ
     * @return boolean
     */
    private function pickupYamatoChangeFlag($api_res)
    {
        // pickup_time_code定数
        $pickup_time_code_1 = 1;   // 希望なし
        $pickup_time_code_2 = 2;   // AM
        $pickup_time_code_4 = 4;   // 14時～16時
        $pickup_time_code_5 = 5;   // 16時～18時
        $pickup_time_code_6 = 6;   // 18時～21時

        // APIで取得した値
        $pickup_date = $api_res->results[0]['pickup_date'];
        $pickup_time_code = (int)$api_res->results[0]['pickup_time_code'];
        $tracking_number = $api_res->results[0]['tracking_number']; 
        $create_datetime = explode(' ', $api_res->results[0]['create_date']);
        $create_date = $create_datetime[0];
        $create_time = $create_datetime[1];

        // 現在日時
        $current_date_time = explode(' ', date('Y-m-d H:i:s'));
        $current_date = $current_date_time[0];
        $current_time = $current_date_time[1];

        /** 集荷依頼変更できる条件
            締め切り時間は３回(07:00 13:00 21:00)ある。
            集荷依頼日が明日(含めて)以降の場合
                ・集荷依頼日が明日かつ集荷依頼時刻が午前と指定希望なしの場合は変更出来ない。 
                ・それ以外の条件は修正できる
            集荷依頼日が本日の場合
                ・07:00の締め切りには集荷依頼は14時～18時を指定していた人で、ボタンを押せるタイミングは現在時刻が21時以降、07時以前の場合。
                ・13:00の締め切りには集荷依頼は8時～21時を指定していた人で、ボタンを押せるタイミングは現在時刻が07時以降、13時以前の場合。
                ・21:00の締め切りには集荷依頼は午前中を指定していた人で、ボタンを押せるタイミングは現在時刻が13時以降、21時以前の場合。
                ・集荷依頼希望なしを指定している場合、本日に登録していているかつバッジがまだ実行されていない場合。
        **/

        $pickup_yamato_change = null;
        // 集荷情報変更ボタン false:非活性 true:活性
        $change_flag = null;
        // 集荷情報変更可能日時
        $change_date = null;
       
        /***  集荷依頼日が明日以降の場合 ***/
        if (strtotime(date('Ymd', strtotime('+1 day'))) <= strtotime($pickup_date)) {
            // AM指定 又は 指定希望なし
            if ($pickup_time_code === 2 || $pickup_time_code === 1) {
                // 集荷指定日が明日で、現在時刻が21:00以降の場合は変更できない(集荷指定日前日の21:00まで修正可能)
                if (strtotime('+1 day') === strtotime($pickup_date) && strtotime($current_time) >= strtotime('21:00:00')) {
                    return $pickup_yamato_change;
                } else {
                    $change_date = date('Y/m/d', strtotime('-1 day', strtotime($pickup_date))) . ' 21:00';
                }

            // 14時～18時 (07:00まで修正可能)
            } else if ($pickup_time_code === 4 || $pickup_time_code === 5) {
                $change_date = date('Y/m/d', strtotime($pickup_date)) . ' 07:00';

            // 18時～21時 (13:00)
            } else if ($pickup_time_code === 6) {
                $change_date = date('Y/m/d', strtotime($pickup_date)) . ' 13:00';
            }

            $change_flag = true;
            $pickup_yamato_change = [
                'change_flag' => $change_flag, 
                'change_date' => $change_date,
            ];

            return $pickup_yamato_change;
        }

        /***  ユーザ締め切り時間(07:00) ***/
        // 集荷依頼日が本日 かつ 現在時刻が21時以降 かつ 現在時刻が7時以前 
        if (strtotime($current_date) === strtotime($pickup_date) && strtotime($current_time) > strtotime('21:00:00') && strtotime($current_time) < strtotime('07:00:00')) {
            // (集荷依頼時刻が14時～16時 16時～18時を指定)
            if ($pickup_time_code === $pickup_time_code_4 && $pickup_time_code === $pickup_time_code_5) {
                $change_flag = true;
                $change_date = date('Y/m/d', strtotime($pickup_date)) . ' 07:00';
            }
            // (集荷依頼時刻希望なし かつ create_dateが本日 かつ create_timeが21:05以降 かつ tracking_number(伝票番号)がnull)
            if ($pickup_time_code === $pickup_time_code_1 && strtotime($create_date) === strtotime($current_date) && strtotime($create_time) > strtotime('21:05:00') && is_null($tracking_number)) {
                $change_flag = true;
                $change_date = date('Y/m/d', strtotime($pickup_date)) . ' 07:00';
            }
            // (集荷依頼時刻希望なし かつ create_dateが本日より過去  かつ racking_number(伝票番号)がnull)
            if ($pickup_time_code === $pickup_time_code_1 && strtotime($create_date) < strtotime($current_date) && is_null($tracking_number)) {
                $change_flag = true;
                $change_date = date('Y/m/d', strtotime($pickup_date)) . ' 07:00';
            }

        /***  ユーザ締め切り時間(13:00) ***/
        // 集荷依頼日が本日 かつ 現在時刻が7時以降 かつ 現在時刻が13時以前
        } else if (strtotime($current_date) === strtotime($pickup_date) && strtotime($current_time) > strtotime('07:00:00') && strtotime($current_time) < strtotime('13:00:00')) {
            // (集荷依頼時刻が18時～21時を指定) 又は
            if ($pickup_time_code === $pickup_time_code_6) {
                $change_flag = true;
                $change_date = date('Y/m/d', strtotime($pickup_date)) . ' 13:00';
            }
            // (集荷依頼時刻希望なし かつ create_dateが本日 かつ create_timeが07:05以降 かつ tracking_number(伝票番号)がnull)
            if ($pickup_time_code === $pickup_time_code_1 && strtotime($create_date) === strtotime($current_date) && strtotime($create_time) > strtotime('07:05:00') && is_null($tracking_number)) {
                $change_flag = true;
                $change_date = date('Y/m/d', strtotime($pickup_date)) . ' 13:00';
            }
            // (集荷依頼時刻希望なし かつ create_dateが本日より過去  かつ racking_number(伝票番号)がnull)
            if ($pickup_time_code === $pickup_time_code_1 && strtotime($create_date) < strtotime($current_date) && is_null($tracking_number)) {
                $change_flag = true;
                $change_date = date('Y/m/d', strtotime($pickup_date)) . ' 13:00';
            }

        /***  ユーザ締め切り時間(21:00) ***/
        // 現在時刻が13時以降 かつ 現在時刻が21時以前 の場合
        } else if ( strtotime($current_time) > strtotime('13:00:00') && strtotime($current_time) < strtotime('21:00:00')) {
            // 集荷依頼時刻が午前中指定 かつ 集荷日が明日指定
            if ($pickup_time_code === $pickup_time_code_2 && strtotime('+1 day', strtotime($current_date)) === strtotime($pickup_date)) {
                $change_flag = true;
                $change_date = date('Y/m/d', strtotime($pickup_date)) . ' 21:00';
            }
        }

        if ($change_flag) {
            $pickup_yamato_change = [
                'change_flag' => $change_flag, 
                'change_date' => $change_date,
            ];
        }

        return $pickup_yamato_change;
    }
}
