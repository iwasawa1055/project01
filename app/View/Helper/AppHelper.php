<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Helper', 'View');
App::uses('InfoBox', 'Model');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class AppHelper extends Helper
{
    /*
     * クレジットカード有効期限 月
    */
    public function creditcardExpireMonth()
    {
        $select_months = [];
        for ($i = 1; $i <= 12; ++$i) {
            $select_months[sprintf('%02d', $i)] = $i.'月';
        }

        return $select_months;
    }

    /*
     * クレジットカード有効期限 年
    */
    public function creditcardExpireYear()
    {
        $now_year = intval(date('Y'));
        $select_years = [];
        for ($i = 0; $i <= 20; ++$i) {
            $select_years[$now_year + $i - 2000] = ($now_year + $i).'年';
        }

        return $select_years;
    }

    public function formatYmdKanji($date)
    {
        $ymd = preg_split('/[-\/]/', $date);
        if (count($ymd) !== 3) {
            return $date;
        }
        return "{$ymd[0]}年{$ymd[1]}月{$ymd[2]}日";
    }

    public function formatYmdKanjiDatetime($datetime)
    {
        $ymd = preg_split('/[-\/\ ]/', $datetime);
        if (count($ymd) !== 4) {
            return $datetime;
        }
        $time = explode(':', $ymd[3]);

        return "{$ymd[0]}年{$ymd[1]}月{$ymd[2]}日 {$time[0]}:{$time[1]}";
    }

    public function replaceBoxtitleChar($title)
    {
        return InfoBox::replaceBoxtitleChar($title);
    }

    public function formatPointType($history)
    {
        $pointType = $history['cpss_type_name'];
        if (empty($pointType)) {
            return '';
        }
        if ($history['point_type'] === POINT_TYPE_GETU) {
            $ym = explode('-', $history['note']);
            return 2 <= count($ym) ? "{$ym[0]}年{$ym[1]}月　{$pointType}" : $history['note'];
        }
        return $pointType;
    }

    // 日付CD変換
    public function convDatetimeCode ( $data_code ){
        // 時間CODE変換表
        $timeList = array(
			2 => '午前中',
			// 2017/06/15 modified by osada@terrada
			// refs #13317 ヤマト配送時間変更
			3 => '14～16時',
			//3 => '12～14時',
			4 => '14～16時',
			5 => '16～18時',
			6 => '18～20時',
			// 2017/06/15 modified by osada@terrada
			// refs #13317 ヤマト配送時間変更
			7 => '19～21時',
			//7 => '19～21時',
		);

        // 日付
        $date = substr( $data_code, 0, 10 );

        // 時間
        $time = substr( $data_code, 11, 1 );

        // 戻り値
        $datetime = date( "Y年m月d日", strtotime( $date ) );
        if( isset( $timeList[$time] )  ) $datetime .= ' '.$timeList[$time];
        return $datetime;
    }

    // ボックス名取得
    public function getBoxName ( $box_id ){
        // 対象アイテム一覧
        $where = [
            'box_id' => $box_id,
        ];
        $InfoBox = new InfoBox();
        $results = $InfoBox->apiGetResultsWhere([], $where);
        foreach ($results as $v) {
            if ($v['box_id'] == $box_id) {
                return $v['box_name'];
            }
        }
        return null;
    }

    public function getProductImage($kit_cd)
    {
        switch ($kit_cd) {
            // HAKO レギュラー
            case '64':
                return '/images/hako-regular.png';
            // HAKO アパレル
            case '65':
                return '/images/hako-apparel.png';
            // HAKO ブック
            case '81':
                return '/images/hako-book.png';

            // MONO レギュラー
            case '66':
                return '/images/mono-regular.png';
            // MONO アパレル
            case '67':
                return '/images/mono-apparel.png';
            // MONO ブック
            case '82':
                return '/images/mono-regular.png';

            // クリーニングパック
            case '75':
                return '/images/cleaning.png';

            // Library
            case '214':
            case '215':
                return '/images/library.png';

            // closet
            case '216':
                return '/images/cleaning.png';

            // ギフト クリーニングパック
            case '217':
                return '/images/cleaning.png';
        }
    }

    public function getHashedEmail($customer)
    {
        if (is_object($customer) == false) {
            return '';
        }

        $info = $customer->getInfo();

        $email = '';
        if (count($info)) {
            if (isset($info['email'])) {
                $email = $info['email'];
                $processed_address = strtolower($email);
                $processed_address = trim($processed_address);
                $processed_address = mb_convert_encoding($processed_address, "UTF-8", "ISO-8859-1");
                $email = md5($processed_address);
            }
        }

        return $email;
    }
}
