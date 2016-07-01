<?php

class AppSearch
{
    /**
     * 検索ロジック
     * and 検索
     *  - [a b c]
     *    - abc が一番上にくる。次にab
     *    - a もしくは b もしくは c
     *    - a のみもOK
     *    - ab もしくは bc もしくは ac もOK
     * or 検索
     *  - [a OR b OR c]
     *    - a もしくは b もしくは c
     *    - a のみもOK
     *    - 連続文字列は出てこない
     * 例)A OR B C OR D
     *  - A OR (B AND C) OR D となる
     *  - A, D は単体での検索, BDは連続文字列として検索もある 
     * マイナス検索
     *  - 「a -b」 とすると aを含み、かつbを含まない含まないリストを作る
     *  - 「a b -c」
     */
    public static function makeRank($_results, $_keywords, $_columns, $_all_minus_flag)
    {
        $hits = [];
        $tmp = [];
        $keywords = [];
        foreach ($_keywords as $list) {
            $regexes_tmp = [];
            foreach ($list as $word) {

                $tmp_minus_flag = false;
                if (strpos($word, '-') === 0) {
                    $tmp_minus_flag = true;
                    $word = mb_substr($word, 1);
                }

                $word = mb_convert_kana($word, 'AK');
                $strings = self::mbStrSplit($word);
                $regex = null;

                if ($tmp_minus_flag === true) {
                    $regex = "-";
                }

                foreach ($strings as $multibyte_char_key => $multibyteChar) {
                    $singlebyteChar = mb_convert_kana($multibyteChar, 'ak');
                    if ($multibyteChar === $singlebyteChar) {
                        $regex .= $multibyteChar;
                    } else {

                        foreach (self::getEscapePregMatchStr() as $str) {

                            if ($singlebyteChar === $str) {
                                $singlebyteChar = str_replace($str, "\\{$str}", $singlebyteChar);
                            }
                        }

                        $regex .= "(" . $multibyteChar . "|". $singlebyteChar . ")" ;
                    }
                }
                $regexes_tmp[] = "{$regex}";
            }
            $keywords[] = $regexes_tmp;
        }


        foreach ($_results as $k => $v) {
            $rank = 0;
            $minus_flag = false;

            foreach ($keywords as $regex_num => $regexes) {
                // マイナス検索
                if (strpos($word, '-') === 0) {
                    foreach ($regexes as $regex) {
                        $regex = mb_substr($regex, 1);
                        foreach ($_columns as $column => $column_rank) {
                            if (preg_match("/{$regex}/", $v[$column])) {
                                $minus_flag = true;
                                unset($_results[$k]);
                            }
                        }
                    }
                }

                // 連結文字列
                if (count($regexes) > 1) {
                    $combine_regex = implode($regexes);
                    $match_all_lists = [];
                    $match_columns = [];

                    foreach ($_columns as $column => $column_rank) {
                        if (preg_match_all("/{$combine_regex}/i", $v[$column], $matches)) {
                            $match_all_lists = array_merge($match_all_lists, $matches[0]);
                            $match_columns[] = $column;
                            // カラム別ランク
                            $rank += $column_rank;
                            // カラム別ランク
                            $rank += RANK_RATE['all_num'];
                        }
                    }
                }

                // 文字にマッチするかどうか
                $match_all_lists = [];
                $match_columns = [];
                $regex = implode('|', $regexes);
                foreach ($_columns as $column => $column_rank) {
                    if (preg_match_all("/{$regex}/i", $v[$column], $matches) ) {
                        $match_all_lists = array_merge($match_all_lists, $matches[0]);
                        $match_columns[] = $column;

                        // カラム別ランク
                        $rank += $column_rank;
                        // マッチランク
                        $rank += RANK_RATE['all_num'];
                    }
                }

                // マッチしたキーワード数の数
                $unique = array_unique($match_all_lists);
                $unique_count = count($unique);

                for ($unique_count;0 < $unique_count;$unique_count--) {
                    $rank += RANK_RATE['match_num'] * $unique_count;               
                }

                // ニアリーマッチ
                // 文字にマッチするかどうか
                foreach ($_columns as $column => $column_rank) {
                    if (preg_match_all("/[{$regex}]/ui", $v[$column], $matches)) {
                        $match_all_lists = array_merge($match_all_lists, $matches[0]);
                        $match_columns[] = $column;

                        // カラム別ランク
                        $rank += $column_rank;
                        // マッチランク
                        $rank += RANK_RATE['neary_num'];

                        // noteの場合、対象の文字列の前後を抜き取る。また、対象の文字列を<b>タグで囲う
                        if ($column === 'box_note' || $column === 'item_note') {
                            // 抜き取りと文字強調タグを挿入する
                            $matches_unique = array_unique($matches[0]);
                            $matches_unique = array_values($matches_unique);
                            $first_pos = mb_strpos($v[$column], $matches_unique[0]);
                            $all_count = mb_strlen($v[$column]);
                            $limit_count = 180;
                            $harf_count = 180 / 2;

                            //開始ポイント取得
                            if ($first_pos > $harf_count) {
                                $start_point = $first_pos - $harf_count;
                                $before = '…';
                            } else {
                                $start_point = 0;
                                $before = '';
                            }
                            $limit_count = $limit_count - $first_pos;
                            $remaining_count = $all_count - $first_pos;

                            // 終了ポイント取得
                            if ($remaining_count > $harf_count) {
                                $end_point = $first_pos + $limit_count;
                                $after = '…';
                            } else {
                                $end_point = $first_pos + $limit_count;
                                $after = '';
                            }

                            $v[$column] = $before . mb_substr($v[$column], $start_point, $end_point) . $after;
                            foreach($matches_unique as $match_str) {
                                $v[$column] = str_replace($match_str, "<b>{$match_str}</b>", $v[$column]);
                            }
                            $v['search_note_flag'] = true;
                        }
                    }
                }


                // 0ptだったらcontinue;
                if ($rank === 0) {
                    continue;
                }
                $v['search_flag'] = true;

                if ($minus_flag === false) {
                    $tmp[$rank][] = $v;
                }
            }
        }

        krsort($tmp);

        if ($_all_minus_flag === true) {
            $hits = $_results;
        } else {
            foreach ($tmp as $rank => $list) {
                foreach($list as $k => $v) {
                    $hits[] = $v;                
                }
            }
        }
        return $hits;
    }

    protected static function mbStrSplit($str, $split_len = 1) {

        mb_internal_encoding('UTF-8');
        mb_regex_encoding('UTF-8');

        if ($split_len <= 0) {
            $split_len = 1;
        }

        $strlen = mb_strlen($str, 'UTF-8');
        $ret    = array();

        for ($i = 0; $i < $strlen; $i += $split_len) {
            $ret[ ] = mb_substr($str, $i, $split_len);
        }
        return $ret;
    }

    protected static function getEscapePregMatchStr()
    {
        $results = [
            '\\',
            '*',
            '+',
            '.',
            '?',
            '{', 
            '}',
            '(',
            ')',
            '[', 
            ']',
            '^',
            '$',
            '-',
            '|',
            '/',
        ];
        return $results;        
    }
}