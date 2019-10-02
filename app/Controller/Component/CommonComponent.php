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
}
