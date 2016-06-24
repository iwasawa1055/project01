<?php

App::uses('ApiCachedModel', 'Model');
App::uses('HashSorter', 'Model');

class InfoBox extends ApiCachedModel
{
    const SESSION_CACHE_KEY = 'INFO_BOX_CACHE';

    const DEFAULTS_SORT_KEY = [
        'product_cd' => true,
        'kit_cd' => true,
        'box_id' => true,
        'box_name' => true,
        'box_status' => true,
    ];
	
    //* 入庫・出庫ページ用sort #8679
    const INBOUND_OUTBOUND_SORT_KEY = [
        'product_cd' => true,
        'box_id' => true,
        'box_name' => true,
        'box_status' => true,
    ];

    const BOXTITLE_CHAR_SEARCH = [
        ':',
        ',',
    ];

    const BOXTITLE_CHAR_REPLACE = [
        '：',
        '，'
    ];

    public function __construct()
    {
        parent::__construct(self::SESSION_CACHE_KEY, 300, 'InfoBox', '/info_box');
    }

    // 結果ゼロ件チェック
    protected $checkZeroResultsKey = 'box_id';

    // 購入済みキット一覧
    // 利用中のBOX一覧　と　並び替え
    // 商品別集計
    public function getProductSummary($outboundOnly = true, $key = 'summary')
    {
        $summary = $this->readCache($key, []);
        if (!empty($summary)) {
            return $summary;
        }

        // サイドバーに出庫済みの数字を含めない
        $all = $this->getListForServiced(null, [], $outboundOnly);
        $summary = [];
        foreach ($all as $a) {
            $productCd = $a['product_cd'];
            if (empty($productCd)) {
                continue;
            }
            if (empty($summary[$productCd])) {
                $summary[$productCd] = 1;
            } else {
                $summary[$productCd]++;
            }
        }
        $this->writeCache($key, [], $summary);

        return $summary;
    }

    // 入庫画面で表示
    public function getListForInbound()
    {
        $okStatus = [
            BOXITEM_STATUS_BUYKIT_START,
            BOXITEM_STATUS_BUYKIT_IN_PROGRESS,
            BOXITEM_STATUS_BUYKIT_DONE,
        ];
        $all = $this->apiGetResults();
        $list = $this->apiGetResultsWhere([], ['box_status' => $okStatus]);
        //* 預け入れ[入庫]ページ, ソート条件 #8697
        foreach ($list as $k => $v){
            $list[$k]['product_cd'] = $this->kitCd2ProductCd($v['kit_cd']);	
            $list[$k]['product_name'] = KIT_NAME[$v['kit_cd']];	
        } 

		//* 預け入れ[入庫]ページ, ソート条件 #8697
        HashSorter::sort($list, self::INBOUND_OUTBOUND_SORT_KEY);
        return $list;
    }

    // 入庫済み一覧
    public function getListForServiced($product = null, $sortKey = [], $withOutboundDone = true, $outboundOnly = false)
    {
        // productCd
        $productCd = null;
        if ($product === 'hako') {
            $productCd = [PRODUCT_CD_HAKO];
        } elseif ($product === 'mono') {
            $productCd = [PRODUCT_CD_MONO];
        } elseif ($product === 'cleaning') {
            $productCd = [PRODUCT_CD_CLEANING_PACK];
        } elseif ($product === 'shoes') {
            $productCd = [PRODUCT_CD_SHOES_PACK];
        } elseif ($product === 'cargo01') {
            $productCd = [PRODUCT_CD_CARGO_JIBUN];
        } elseif ($product === 'cargo02') {
            $productCd = [PRODUCT_CD_CARGO_HITOMAKASE];
        } elseif ($product === 'sneakers') {
            $productCd = [PRODUCT_CD_SNEAKERS];
        }

        $okStatus = [
            BOXITEM_STATUS_INBOUND_IN_PROGRESS,
            BOXITEM_STATUS_INBOUND_DONE,
            BOXITEM_STATUS_OUTBOUND_START,
            BOXITEM_STATUS_OUTBOUND_IN_PROGRESS,
        ];

        if ($withOutboundDone) {
            if (!empty($outboundOnly)) {
                unset($okStatus);
            }
            $okStatus[] = BOXITEM_STATUS_OUTBOUND_DONE;
        }

        $where = ['box_status' => $okStatus, 'product_cd' => $productCd];
        if (empty($where['product_cd'])) {
            unset($where['product_cd']);
        }

        $list = $this->apiGetResultsWhere([], $where);
        HashSorter::sort($list, ($sortKey + self::DEFAULTS_SORT_KEY));
        return $list;
    }


    /**
     * kit_cdからproduct_cdに変換
     * @param  [type] $kitCd [description]
     * @return [type]        [description]
     */
    public static function kitCd2ProductCd($kitCd)
    {
        switch ($kitCd) {
            case KIT_CD_MONO:
            case KIT_CD_MONO_BOOK:
            case KIT_CD_MONO_APPAREL:
                $productCd = PRODUCT_CD_MONO;
                break;
            case KIT_CD_HAKO:
            case KIT_CD_HAKO_BOOK:
            case KIT_CD_HAKO_APPAREL:
                $productCd = PRODUCT_CD_HAKO;
                break;
            case KIT_CD_CLEANING_PACK:
                $productCd = PRODUCT_CD_CLEANING_PACK;
                break;
            case KIT_CD_SNEAKERS:
                $productCd = PRODUCT_CD_SNEAKERS;
                break;
            default:
                break;
        }
        return $productCd;
    }

    public function getListLastInbound()
    {
        $where = [
            'box_status' => [
                BOXITEM_STATUS_INBOUND_DONE,
                BOXITEM_STATUS_OUTBOUND_START,
                BOXITEM_STATUS_OUTBOUND_IN_PROGRESS,
            ]
        ];
        $list = $this->apiGetResultsWhere([], $where);
        $sortKey = ['inbound_date' => false];
        HashSorter::sort($list, ($sortKey + self::DEFAULTS_SORT_KEY));
        return $list;
    }

    public static function replaceBoxtitleChar($title)
    {
        return str_replace(self::BOXTITLE_CHAR_SEARCH, self::BOXTITLE_CHAR_REPLACE, $title);
    }


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
    public function editBySearchTerm($results, $params)
    {
        if (empty($params['keyword'])) {
            return $results;
        }

        $keywords_tmp = explode('OR', $params['keyword']);

        $keywords = null;
        foreach ($keywords_tmp as $k => $v) {
            $and_words = explode(' ', str_replace('　', ' ',$v));
            $and_words = array_filter($and_words);
            $and_words = array_values($and_words);

            $keywords[] = $and_words;
        }

        $all_minus_flag = true;
        foreach ($keywords as $and_lists) {
            foreach ($and_lists as $and_word) {
                if (strpos($and_word, '-') !== 0) {
                    $all_minus_flag = false;
                }
            }
        }

        // ランク付け用にポイントをそれぞれ設定
        $columns = [
            'box_name' => 100, 
            'box_id' => 80, 
            'product_name' => 60, 
            'box_note' => 40, 
            'kit_name' => 20,
        ];

        // rankレート（順番で除算する）
        // 1番目:100, 2番目:50, 3番目:33, 4番目:25
        $match = 100;

        // マッチしたワードのユニーク数を乗算する
        // 4ワード:80, 3ワード:60, 2ワード:40, 1ワード:20
        $match_num = 20;

        // マッチしたワードの総件数。数を乗算する
        $all_num = 10;

        // ニアリーマッチの件数
        $neary_num = 5;

        $hits = [];
        $tmp = [];

        foreach ($results as  $k => $v) {
            $rank = 0;
            $minus_flag = false;

            foreach ($keywords as $word_num => $words) {
                // まずマイナス検索を確認
                foreach ($columns as $column => $column_rank) {
                    foreach ($words as $word) {
                        if (strpos($word, '-') === 0) {
                            $word = mb_substr($word, 1);
                            if (preg_match("/{$word}/", $v[$column])) {
                                $minus_flag = true;
                                unset($results[$k]);
                            }
                        }
                    }
                }

                // wordsが複数ある場合、まず連結文字列を調査
                $combine_word = null;
                $word = implode('|', $words);

                if (count($words) > 1) {
                    $combine_word = implode($words);
                    $match_all_lists = [];
                    $match_columns = [];
                    foreach ($columns as $column => $column_rank) {
                        if (preg_match_all("/{$combine_word}/", $v[$column], $matches)) {
                            $match_all_lists = array_merge($match_all_lists, $matches[0]);
                            $match_columns[] = $column;
                            // カラム別ランク
                            $rank += $column_rank;
                            // カラム別ランク
                            $rank += $all_num;
                        }
                    }
                }

                $match_all_lists = [];
                $match_columns = [];
                // 文字にマッチするかどうか
                foreach ($columns as $column => $column_rank) {
                    if (preg_match_all("/{$word}/", $v[$column], $matches) ) {
                        $match_all_lists = array_merge($match_all_lists, $matches[0]);
                        $match_columns[] = $column;

                        // カラム別ランク
                        $rank += $column_rank;
                        // マッチランク
                        $rank += $all_num;
                    }
                }

                // ニアリーマッチ
                // 文字にマッチするかどうか
                foreach ($columns as $column => $column_rank) {
                    if (preg_match_all("/[{$word}]/u", $v[$column], $matches)) {
                        $match_all_lists = array_merge($match_all_lists, $matches[0]);
                        $match_columns[] = $column;

                        // カラム別ランク
                        $rank += $column_rank;
                        // マッチランク
                        $rank += $neary_num;
                    }
                }

                // マッチしたキーワード数の数
                $unique = array_unique($match_all_lists);
                $unique_count = count($unique);

                // 0件だったらcontinue;
                if ($unique_count === 0) {
                    continue;
                }

                for ($unique_count;0 < $unique_count;$unique_count--) {
                    $rank += $match_num * $unique_count;               
                }

                if ($minus_flag === false) {
                    $tmp[$rank][] = $v;
                }
            }
        }

        krsort($tmp);

        if ($all_minus_flag === true) {
            $hits = $results;
        } else {
            foreach ($tmp as $rank => $list) {
                foreach($list as $k => $v) {
                    $hits[] = $v;                
                }
            }
        }

        // sort
        if (!empty($params['order']) && !empty($params['direction'])) {
            $sortKey = [$params['order'] => ($params['direction'] === 'asc')];            
            HashSorter::sort($hits, ($sortKey + self::DEFAULTS_SORT_KEY));
        }

        return $hits;
    }

    protected function _makeRank($_results)
    {
        foreach ($_results as  $k => $v) {
            $rank = 0;
            $minus_flag = false;

            foreach ($keywords as $word_num => $words) {
                // まずマイナス検索を確認
                foreach ($columns as $column => $column_rank) {
                    foreach ($words as $word) {
                        if (strpos($word, '-') === 0) {
                            $word = mb_substr($word, 1);
                            if (preg_match("/{$word}/", $v[$column])) {
                                $minus_flag = true;
                                unset($_results[$k]);
                            }
                        }
                    }
                }

                // wordsが複数ある場合、まず連結文字列を調査
                $combine_word = null;
                $word = implode('|', $words);

                if (count($words) > 1) {
                    $combine_word = implode($words);
                    $match_all_lists = [];
                    $match_columns = [];
                    foreach ($columns as $column => $column_rank) {
                        if (preg_match_all("/{$combine_word}/", $v[$column], $matches)) {
                            $match_all_lists = array_merge($match_all_lists, $matches[0]);
                            $match_columns[] = $column;
                            // カラム別ランク
                            $rank += $column_rank;
                            // カラム別ランク
                            $rank += $all_num;
                        }
                    }
                }

                $match_all_lists = [];
                $match_columns = [];
                // 文字にマッチするかどうか
                foreach ($columns as $column => $column_rank) {
                    if (preg_match_all("/{$word}/", $v[$column], $matches) ) {
                        $match_all_lists = array_merge($match_all_lists, $matches[0]);
                        $match_columns[] = $column;

                        // カラム別ランク
                        $rank += $column_rank;
                        // マッチランク
                        $rank += $all_num;
                    }
                }

                // ニアリーマッチ
                // 文字にマッチするかどうか
                foreach ($columns as $column => $column_rank) {
                    if (preg_match_all("/[{$word}]/u", $v[$column], $matches)) {
                        $match_all_lists = array_merge($match_all_lists, $matches[0]);
                        $match_columns[] = $column;

                        // カラム別ランク
                        $rank += $column_rank;
                        // マッチランク
                        $rank += $neary_num;
                    }
                }

                // マッチしたキーワード数の数
                $unique = array_unique($match_all_lists);
                $unique_count = count($unique);

                // 0件だったらcontinue;
                if ($unique_count === 0) {
                    continue;
                }

                for ($unique_count;0 < $unique_count;$unique_count--) {
                    $rank += $match_num * $unique_count;               
                }

                if ($minus_flag === false) {
                    $tmp[$rank][] = $v;
                }
            }
        }
    }

}
