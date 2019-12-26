<?php

App::uses('CustomerAddress', 'Model');
App::uses('CustomerInfo', 'Model');
App::uses('CorporateInfo', 'Model');

class CommonComponent extends Component
{
    /*
     * サービス無料期限取得
     *
     * @param string $_date   サービス開始日時(購入日時)
     * @param string $_format 返却時の日時フォーマット
     *
     * @return string サービス無料期限日
     */
    public function getServiceFreeLimit($_date, $_format = 'Y年m月d日')
    {
        // サービス期間後の年月日を取得
        $datetime = new DateTime($_date . ' +2 month');
        $free_limit_date = $datetime->format('Y-m-t');

        // TODO 暫定処置として日付を指定する（2019年12月）
        if ($free_limit_date == '2019-12-31') {
            $free_limit_date = '2019-12-28';
        }

        // 日曜日の場合は前日に変更
        $targetTime = strtotime($free_limit_date);
        $week = (int) date('w', $targetTime);
        if ($week === 0) {
            $free_limit_date = date('Y-m-d', strtotime('-1 day', $targetTime));
        }

        $free_limit_date = date($_format, strtotime($free_limit_date));

        return $free_limit_date;
    }

    /*
     * 最低保管日時取得
     *
     * @param string $_date   入庫日時
     * @param string $_format 返却時の日時フォーマット
     *
     * @return string 最低保管日
     */
    public function getMinimumKeepDate($_date, $_format = 'Y/m/d')
    {
        $keep_limit_date = '';
        $start_date  = strtotime('2019-08-01');
        $target_date = strtotime($_date);

        // 最低保管開始日時より以前のボックスは対象外
        if ($start_date > $target_date) {
            return $keep_limit_date;
        }

        // 最低保管の年月日を取得
        $datetime = new DateTime($_date . ' +1 month');
        $tmp_keep_limit_date = $datetime->format('Y-m-t');

        // 最低保管が過去の場合は対象外
        if (time() > strtotime($tmp_keep_limit_date)) {
            return $keep_limit_date;
        }

        $keep_limit_date = date($_format, strtotime($tmp_keep_limit_date));

        return $keep_limit_date;
    }

    /*
     * 取り出し料金無料開始日時取得
     *
     * @param string $_date   入庫日時
     * @param string $_format 返却時の日時フォーマット
     *
     * @return string 取り出し料金無料日
     */
    public function getTakeOutFreeDate($_date, $_format = 'Y/m/d')
    {
        $free_start_date = '';

        // 最低保管後の年月日を取得
        $datetime = new DateTime($_date . ' +16 month');
        $tmp_free_start_date = $datetime->format('Y-m-01');

        // 取り出し無料開始日が過去の場合は対象外
        if (time() > strtotime($tmp_free_start_date)) {
            return $free_start_date;
        }

        $free_start_date = date($_format, strtotime($tmp_free_start_date));

        return $free_start_date;
    }

    /**
     * メッセージ画面で集荷情報を変更するボタン、活性非活性フラグ
     *
     * @param array $api_res PickupYamato(Model)の返却値
     *
     * @return boolean
     */
    public function pickupYamatoChangeFlag($api_res)
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
