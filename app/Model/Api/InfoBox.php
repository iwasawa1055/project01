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
        HashSorter::sort($list, self::DEFAULTS_SORT_KEY);
        return $list;
    }

    // 入庫済み一覧
    public function getListForServiced($product = null, $sortKey = [], $withOutboudDone = true, $outboundOnly = false)
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
        
        if ($withOutboudDone) {
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

        // ランク付け用にポイントをそれぞれ設定
        $columns = [
            'box_name' => '10', 
            'box_id' => '8', 
            'product_name' => '6', 
            'box_note' => '4', 
            'kit_name' => '2'
        ];

        if (empty($params['type'])) {
            $type = '1';
        } else {
            $type = $params['type'];
        }

        $keyword = str_replace(' ', '|', str_replace('　', ' ', $params['keyword']));
        $keywords = explode('|', $keyword);
        $keyword_count = count($keywords);

        $tmp = [];

        # マッチ数, 出現数, マッチカラム で表示順整形
        foreach ($results as $k => $v) {

            $match_all_lists = [];
            $match_columns = [];

            foreach ($columns as $column => $column_rank) {
                if (preg_match_all("/{$keyword}/", $v[$column], $matches)) {
                    $match_all_lists = array_merge($match_all_lists, $matches[0]);
                    $match_columns[] = $column;
                }
            }


            // マッチしたキーワード数の数
            $unique = array_unique($match_all_lists);
            $unique_count = count($unique);

            // 0件だったらcontinue;
            if ($unique_count === 0) {
                continue;
            }

            // type = 2 の場合、and検索なのですべて入ってるデータのみ
            if ($type === '2' && $unique_count !== $keyword_count) {
                continue;
            }

            // 出現数
            $all_count = count($match_all_lists);

            // マッチしたカラム
            $rank = 0;
            foreach ($match_columns as $column) {
                $rank += $columns[$column];
            }
            $tmp[$unique_count][$all_count][$rank][] = $v;
        }

        krsort($tmp);

        $hits = [];

        // リスト生成
        foreach ($tmp as $unique_count_key => $matches_count_list) {
            krsort($matches_count_list);
            foreach ($matches_count_list as $matches_count_data) {
                krsort($matches_count_data);
                foreach($matches_count_data as $rank =>  $rank_data) {
                    foreach ($rank_data as $row) {
                        $hits[] = $row;
                    }
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
}
