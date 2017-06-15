<?php

App::uses('Helper', 'View');

/**
 * 表示に関連する共通処理はこのクラスにメソッド追加する
 * ※名前がAppHelperHelperなのはctpファイル内で使うときに
 *   $this->AppHelper->hoge();
 *   と書いて保守時に処理を追いやすくするため
 */
class AppHelperHelper extends Helper
{
    /**
     * ページャー表示用情報取得
     *
     * @access    public
     * @param     現在のページ、1ページ当たりの件数、総件数
     * @return    お知らせ情報一覧
     */
    public function getPagerInfo($current_page, $per_page, $total_count)
    {
        // 総ページ数算出
        $total_page = ceil( $total_count / $per_page);

        // カレントページ補正
        if( empty( $current_page ) or $current_page == 0 ) $current_page = 1;
        if( $current_page > $total_page and $current_page != 1 ) $current_page = $total_page;

        // 開始位置算出
        $start_row = ( $current_page - 1 ) * $per_page;

        // 前ページ・次ページ
        $prev_page = ( $current_page > 1 )? $current_page - 1 : 0;
        $next_page = ( $current_page < $total_page )? $current_page + 1 : 0;

        // 最初ページ・最後ページ
        $first_page = ( $current_page > 1 )? 1 : 0;
        $last_page  = ( $current_page < $total_page )? $total_page : 0;

        // 表示される件数
        $view_count = ( $current_page < $total_page )? $per_page : $total_count - $start_row;

        // ページャー用配列
        $page_count = array();
        $start_page = ( ( $current_page - 2 ) > 0 )? $current_page - 2 : 1 ;
        for( $i = $start_page ; $i <= $total_page ; $i++ ){
            array_push( $page_count, $i);
            if( count( $page_count ) >= 5 ) break;
        }
        if(  count( $page_count ) < 5 ){
            for( $i = $start_page - 1 ; $i > 0 ; $i-- ){
                array_unshift( $page_count, $i);
                if( count( $page_count ) >= 5 ) break;
            }
        }

        // ページャー表示用配列作成
        $pager = array( "current_page" => $current_page,
                        "view_count"   => $view_count,
                        "per_page"     => $per_page,
                        "prev_page"    => $prev_page,
                        "next_page"    => $next_page,
                        "firs_tpage"   => $first_page,
                        "last_page"    => $last_page,
                        "total_page"   => $total_page,
                        "total_count"  => $total_count,
                        "page_count"   => $page_count,
                        "start_row"    => $start_row,
                        "end_row"      => $start_row + $view_count
                      );

        // 戻り値
        return $pager;
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
			7 => '18～21時',
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

    // リスト用 month 
    public function selectMonth(){
        //* select box month list 
        $select_month = [];
        for ($i = '1'; $i <= '12'; $i++) {
            $select_month[sprintf('%02d', $i)] = $i . '月';
        }
        return $select_month;
    }

    // リスト用 year for card , expire_year 
    public function selectYear(){
        //* select box year list 
        $select_year = [];
        $now_year = intval(date('Y'));
        for ($i = '0'; $i <= '20'; $i++) {
            $select_year[$now_year + $i - 2000 ] = ($now_year + $i) . '年';
        }
        
        return $select_year;
    }    

    /**
     * カード番号にマスクをかける
     * @param type $card_no 元のカード番号
     * @return type マスクされたカード番号
     */
    public function maskCardNo($card_no)
    {
        return preg_replace('/....$/', 'xxxx', $card_no);
    }

    /**
     * セキュリティコードにマスクをかける
     * @param type $security_cd 元のセキュリティコード
     * @return string マスクされたセキュリティコード
     */
    public function maskSecurityCd($security_cd)
    {
        return preg_replace('/./', 'x', $security_cd);
    }

}
