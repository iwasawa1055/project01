<?php

App::uses('ApiCachedModel', 'Model');
App::uses('HashSorter', 'Model');
App::uses('AppSearch', 'Lib');

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
        parent::__construct(self::SESSION_CACHE_KEY, 300, 'InfoBox', '/info_box', 'minikura_v5');
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
        $list = $this->apiGetResultsWhere([], ['box_status' => $okStatus]);

        // キットコードが定義されていない場合、除外する。
        foreach ($list as $k => $v){
            $list[$k]['product_cd'] = $this->kitCd2ProductCd($v['kit_cd']);
            if(is_null($list[$k]['product_cd'])) {
                unset($list[$k]);
            }
        }

        //* 預け入れ[入庫]ページ, ソート条件 #8697
        foreach ($list as $k => $v){
            $list[$k]['product_cd'] = $this->kitCd2ProductCd($v['kit_cd']);
            $list[$k]['product_name'] = KIT_NAME[$v['kit_cd']];
        }

		//* 預け入れ[入庫]ページ, ソート条件 #8697
        HashSorter::sort($list, self::INBOUND_OUTBOUND_SORT_KEY);
        return $list;
    }

    // 入庫画面で表示(再入庫用のボックス)
    public function getListForInboundOldBox()
    {
        $okStatus = [
            BOXITEM_STATUS_OUTBOUND_DONE,
        ];
        $list = $this->apiGetResultsWhere([], ['box_status' => $okStatus]);

        // HAKO以外除外する。
        foreach ($list as $k => $v){
            if($list[$k]['product_cd'] != '004024') {
                unset($list[$k]);
            }
        }

        //* 預け入れ[入庫]ページ, ソート条件 #8697
        foreach ($list as $k => $v){
            $list[$k]['product_cd'] = $this->kitCd2ProductCd($v['kit_cd']);
            $list[$k]['product_name'] = !empty($v['kit_cd']) ? KIT_NAME[$v['kit_cd']] : '';
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
        } elseif ($product === 'mono_direct') {
            $productCd = [PRODUCT_CD_DIRECT_INBOUND];
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
        } elseif ($product === 'library') {
            $productCd = [PRODUCT_CD_LIBRARY];
        } elseif ($product === 'closet') {
            $productCd = [PRODUCT_CD_CLOSET];
        } elseif ($product === 'gift_cleaning') {
            $productCd = [PRODUCT_CD_GIFT_CLEANING_PACK];
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

        // 除外BOX プロダクト名が定義されていないプロダクトは表示BOXから除外
        foreach($list as $key => $value) {
            if(!array_key_exists($value['product_cd'], PRODUCT_NAME)){
                unset($list[$key]);
            }
        }

        // お預かり順ソートは最終入庫日を見るように修正
        if (isset($sortKey['inbound_date'])) {
            $sortKey['last_inbound_date'] = $sortKey['inbound_date'];
            unset($sortKey['inbound_date']);
        }

        HashSorter::sort($list, ($sortKey + self::DEFAULTS_SORT_KEY));
        return $list;
    }


    /**
     * kit_cdからproduct_cdに変換
     * kit_cdがない場合、nullを返す
     * @param  [type] $kitCd [description]
     * @return [type]        [description]
     */
    public static function kitCd2ProductCd($kitCd)
    {
        $productCd = null;

        switch ($kitCd) {
            case KIT_CD_MONO:
            case KIT_CD_MONO_BOOK:
            case KIT_CD_MONO_APPAREL:
            case KIT_CD_STARTER_MONO:
            case KIT_CD_STARTER_MONO_BOOK:
            case KIT_CD_STARTER_MONO_APPAREL:
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
            case KIT_CD_HAKO_LIMITED_VER1:
                $productCd = PRODUCT_CD_HAKO;
                break;
            case KIT_CD_LIBRARY_DEFAULT:
            case KIT_CD_LIBRARY_GVIDO:
                $productCd = PRODUCT_CD_LIBRARY;
                break;
            case KIT_CD_CLOSET:
                $productCd = PRODUCT_CD_CLOSET;
                break;
            case KIT_CD_GIFT_CLEANING_PACK:
                $productCd = PRODUCT_CD_GIFT_CLEANING_PACK;
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

        // 検索
        $hits = AppSearch::makeRank($results, $keywords, $columns, $all_minus_flag);

        // sort
        if (!empty($params['order']) && !empty($params['direction'])) {
            $sortKey = [$params['order'] => ($params['direction'] === 'asc')];
            HashSorter::sort($hits, ($sortKey + self::DEFAULTS_SORT_KEY));
        }

        return $hits;
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
    private function _makeRank($_results, $_keywords, $_columns, $_all_minus_flag)
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
                $strings = $this->mb_str_split($word);
                $regex = null;

                if ($tmp_minus_flag === true) {
                    $regex = "-";
                }

                foreach ($strings as $multibyte_char_key => $multibyte_char) {
                    $singlebyte_char = mb_convert_kana($multibyte_char, 'ak');
                    if ($multibyte_char === $singlebyte_char) {
                        $regex .= $multibyte_char;
                    } else {
                        $regex .= "({$multibyte_char}|{$singlebyte_char})";
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
                    }
                }

                // 0ptだったらcontinue;
                if ($rank === 0) {
                    continue;
                }


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

    public function mb_str_split($str, $split_len = 1) {

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

}
