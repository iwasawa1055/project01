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

    public function replaceBoxtitleChar($title)
    {
        return InfoBox::replaceBoxtitleChar($title);
    }

    public function formatPointType($history)
    {
        $pointType = Hash::get(POINT_TYPE, $history['point_type']);
        if (empty($pointType)) {
            return '';
        }
        if ($history['point_type'] === POINT_TYPE_GETU) {
            $ym = explode('-', $history['note']);
            return 2 <= count($ym) ? "{$ym[0]}年{$ym[1]}月　{$pointType}" : $history['note'];
        }
        return $pointType;
    }
}
