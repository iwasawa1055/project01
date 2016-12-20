<?php

App::uses('ApiCachedModel', 'Model');
App::uses('ImageItem', 'Model');
App::uses('HashSorter', 'Model');
App::uses('Sales', 'Model');

class InfoItem extends ApiCachedModel
{

    const SESSION_CACHE_KEY = 'INFO_ITEM_CACHE';

    const DEFAULTS_SORT_KEY = [
        'box.product_cd' => true,
        'box.kit_cd' => true,
        'box.box_id' => true,
        'box.box_name' => true,
        'item_id' => true,
        'item_name' => true,
        'item_status' => true,
        'item_group_cd' => true,
    ];

    // 結果ゼロ件チェック
    protected $checkZeroResultsKey = 'item_id';

    public function __construct()
    {
        parent::__construct(self::SESSION_CACHE_KEY, 300, 'InfoItem', '/info_item');
    }

    protected function triggerNotUsingCache() {
        parent::triggerNotUsingCache();
        (new ImageItem())->deleteCache();

    }

    // 入庫済み一覧
    // @param bool $outboundOnly true:出庫済みのみ表示する, false:出庫済みも含めて表示する
    public function getListForServiced($sortKey = [], $where = [], $withOutboundDone = true, $outboundOnly = false)
    {
        $where['item_status'] = [
            BOXITEM_STATUS_INBOUND_IN_PROGRESS * 1,
            BOXITEM_STATUS_INBOUND_DONE * 1,
            BOXITEM_STATUS_OUTBOUND_LIMIT_START * 1,
            BOXITEM_STATUS_OUTBOUND_LIMIT_IN_PROGRESS * 1,
            BOXITEM_STATUS_OUTBOUND_LIMIT_RETURN_DONE * 1,
            BOXITEM_STATUS_OUTBOUND_LIMIT_RETURN_IN_PROGRESS * 1,
            BOXITEM_STATUS_OUTBOUND_START * 1,
            BOXITEM_STATUS_OUTBOUND_IN_PROGRESS * 1,
        ];
        if ($withOutboundDone) {
            // 出庫済みのみフラグが立っている場合、出庫済み以外をunsetする
            if (!empty($outboundOnly)) {
                unset($where['item_status']);
            }
            $where['item_status'][] = BOXITEM_STATUS_OUTBOUND_LIMIT_DONE * 1;
            $where['item_status'][] = BOXITEM_STATUS_OUTBOUND_DONE * 1;
        }

		//* mock22  追加仕様、箱（商品）に紐づくアイテムを選択する 
		$product = null;
		if (! empty($where['product'])) {
		    $product = $where['product'];
		    //* アイテム取得にproductの検索は不要のため、unsetする。
		    unset($where['product']);
		}
		if ($product) {
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
		}

		//* アイテム取得、 中でアイテム画像とボックス情報取得
        $list = $this->apiGetResultsWhere([], $where);

		//* アイテム取得後にproduct_cdでリストを再生成する
		if (! empty($productCd)) {
		    $productData['product_cd'] = $productCd;
			$list = $this->_selectByProductCd($list, $productData);
		}

        // sort
        HashSorter::sort($list, ($sortKey + self::DEFAULTS_SORT_KEY));
        return $list;
    }


    public function apiGetResults($data = [])
    {
        $imageModel = new ImageItem();
        $boxModel = new InfoBox();
        //* sales 販売情報 201608から
        $sales_results = null;
        $salesModel = new Sales();
        $list = parent::apiGetResults($data);
        if (is_array($list)) {
            // 画像情報とボックス情報を設定
            $listImage = Hash::combine($imageModel->apiGetResults(), '{n}.item_id', '{n}');
            $listBox = Hash::combine($boxModel->apiGetResults(), '{n}.box_id', '{n}');
            //* sales 販売情報
            $sales_results = $salesModel->apiGet();
            if (! empty($sales_results->results)) {
                $listSales = Hash::combine($sales_results->results, '{n}.item_id', '{n}');
            }
            foreach ($list as $index => $item) {
                if (array_key_exists($item['item_id'], $listImage)) {
                    $list[$index]['image_first'] = $listImage[$item['item_id']];
                }
                if (array_key_exists($item['box_id'], $listBox)) {
                    $list[$index]['box'] = $listBox[$item['box_id']];
                }
                //* sales 販売情報 複数の販売情報ができる(販売中や、販売キャンセル、 )
                if (! empty($listSales)) {
                    if (array_key_exists($item['item_id'], $listSales)) {
                        $sales_results_by_item_id = [];
                        foreach ($sales_results->results as $key => $val) {
                            if ($item['item_id'] === $val['item_id']) {
                                $sales_results_by_item_id[] = $val;
                            }
                        }
                        $list[$index]['sales'] = $sales_results_by_item_id; 
                    }
                }
            }
        }
        return $list;
    }

    public function getListLastInbound() {
        $where = [
            'item_status' => [
                BOXITEM_STATUS_INBOUND_DONE * 1,
                BOXITEM_STATUS_OUTBOUND_LIMIT_START * 1,
                BOXITEM_STATUS_OUTBOUND_LIMIT_IN_PROGRESS * 1,
                BOXITEM_STATUS_OUTBOUND_LIMIT_RETURN_DONE * 1,
                BOXITEM_STATUS_OUTBOUND_LIMIT_RETURN_IN_PROGRESS * 1,
                BOXITEM_STATUS_OUTBOUND_START * 1,
                BOXITEM_STATUS_OUTBOUND_IN_PROGRESS * 1,
            ]
        ];
        $list = $this->apiGetResultsWhere([], $where);
        $sortKey = ['box.inbound_date' => false];
        HashSorter::sort($list, ($sortKey + self::DEFAULTS_SORT_KEY));
        return $list;
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
            'item_name' => 100, 
            'item_id' => 80, 
            'item_note' => 60, 
            'box_name' => 40, 
            'box_id' => 20,
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

    //* private

    /**
     * 商品コード毎にアイテムを表示する
     *
     * @access      private
     * @param       array $_list アイテム情報
     * @param       array $_product 商品情報
     * @return      array $findlist
     */
	private function _selectByProductCd($_list, $_product)
	{
		$apiRes = $_list;
		$where = $_product;
        $findList = [];

        foreach ($apiRes as $a) {
            $notMatch = false;
            foreach ($where as $key => $value) {
                if (!is_array($value)) {
                    $value = [$value];
                }
                if (!in_array(Hash::get($a['box'], $key), $value, true)) {
                    $notMatch = true;
                    break;
                }
            }
            if (!$notMatch) {
                $findList[] = $a;
            }
        }
		return $findList;
	}
}
