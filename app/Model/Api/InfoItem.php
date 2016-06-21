<?php

App::uses('ApiCachedModel', 'Model');
App::uses('ImageItem', 'Model');
App::uses('HashSorter', 'Model');

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
    public function getListForServiced($sortKey = [], $where = [], $withOutboudDone = true, $outboundOnly = false)
    {
        $where['item_status'] = [
            BOXITEM_STATUS_INBOUND_IN_PROGRESS * 1,
            BOXITEM_STATUS_INBOUND_DONE * 1,
            BOXITEM_STATUS_OUTBOUND_START * 1,
            BOXITEM_STATUS_OUTBOUND_IN_PROGRESS * 1,
        ];
        if ($withOutboudDone) {
            // 出庫済みのみフラグが立っている場合、出庫済み以外をunsetする
            if (!empty($outboundOnly)) {
                unset($where['item_status']);
            }
            $where['item_status'][] = BOXITEM_STATUS_OUTBOUND_DONE * 1;
        }

		//* #feature_mypage_menu --[start] 追加仕様、箱（商品）に紐づくアイテムを選択する 
		$product = $where['product_cd'];
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
		//* アイテム取得にproduct_cdの検索は不要のため、unsetする。
		unset($where['product_cd']);

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
        $list = parent::apiGetResults($data);
        if (is_array($list)) {
            // 画像情報とボックス情報を設定
            $listImage = Hash::combine($imageModel->apiGetResults(), '{n}.item_id', '{n}');
            $listBox = Hash::combine($boxModel->apiGetResults(), '{n}.box_id', '{n}');
            foreach ($list as $index => $item) {
                if (array_key_exists($item['item_id'], $listImage)) {
                    $list[$index]['image_first'] = $listImage[$item['item_id']];
                }
                if (array_key_exists($item['box_id'], $listBox)) {
                    $list[$index]['box'] = $listBox[$item['box_id']];
                }
            }
        }
        return $list;
    }

    public function getListLastInbound() {
        $where = [
            'item_status' => [
                BOXITEM_STATUS_INBOUND_DONE * 1,
                BOXITEM_STATUS_OUTBOUND_START * 1,
                BOXITEM_STATUS_OUTBOUND_IN_PROGRESS * 1,
            ]
        ];
        $list = $this->apiGetResultsWhere([], $where);
        $sortKey = ['box.inbound_date' => false];
        HashSorter::sort($list, ($sortKey + self::DEFAULTS_SORT_KEY));
        return $list;
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
