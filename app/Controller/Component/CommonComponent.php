<?php

App::uses('CustomerAddress', 'Model');
App::uses('CustomerInfo', 'Model');
App::uses('CorporateInfo', 'Model');

class CommonComponent extends Component
{
    /*
     * サービス無料期限取得
     *
     * @param string $date   サービス開始日時(購入日時)
     * @param string $format 返却時の日時フォーマット
     *
     * @return string サービス無料期限
     */
    public function getServiceFreeLimit($date, $format = 'Y年m月d日')
    {
        // サービス期間後の年月日を取得
        $datetime = new DateTime($date . ' +2 month');
        $free_limit = $datetime->format('Y-m-t');

        // TODO 暫定処置として日付を指定する（2019年12月）
        if ($free_limit == '2019-12-31') {
            $free_limit = '2019-12-28';
        }

        // 日曜日の場合は前日に変更
        $targetTime = strtotime($free_limit);
        $week = (int) date('w', $targetTime);
        if ($week === 0) {
            $free_limit = date('Y-m-d', strtotime('-1 day', $targetTime));
        }

        $free_limit = date($format, strtotime($free_limit));

        return $free_limit;
    }
}
