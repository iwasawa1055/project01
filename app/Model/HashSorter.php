<?php

/**
 * 配列ソートクラス
 */
class HashSorter
{
    public $sortKeyList = [];
    public function __construct($sortKeyList)
    {
        $this->sortKeyList = $sortKeyList;
    }
    public static function sort(&$list, $sortKey = [])
    {
        $cmpKitCd = [
          KIT_CD_MONO,
          KIT_CD_MONO_APPAREL,
          KIT_CD_MONO_BOOK,
          KIT_CD_HAKO,
          KIT_CD_HAKO_APPAREL,
          KIT_CD_HAKO_BOOK,
          KIT_CD_WINE_HAKO,
          KIT_CD_WINE_MONO,
          KIT_CD_CLEANING_PACK,
          KIT_CD_STARTER_MONO,
          KIT_CD_STARTER_MONO_APPAREL,
          KIT_CD_STARTER_MONO_BOOK,
          KIT_CD_HAKO_LIMITED_VER1,
        ];
        $cmpProductCd = [
          PRODUCT_CD_MONO,
          PRODUCT_CD_HAKO,
          PRODUCT_CD_CARGO_JIBUN,
          PRODUCT_CD_CARGO_HITOMAKASE,
          PRODUCT_CD_CLEANING_PACK,
          PRODUCT_CD_SHOES_PACK,
        ];

        $keys = array_keys($sortKey);
        $values = array_values($sortKey);

        $args = [];
        
        foreach ($list as $key => $value) {
          foreach ($keys as $i => $keyName) {

            $value2 = Hash::get($value, $keyName);
            if ($keyName === 'kit_cd' || $keyName === 'box.kit_cd') {
                $value2 = array_search($value2, $cmpKitCd);
            } elseif ($keyName === 'product_cd' || $keyName === 'box.product_cd') {
                $value2 = array_search($value2, $cmpProductCd);
            }

            $args[$i * 2][] = $value2;
            $args[$i * 2 + 1] =  $values[$i] ? SORT_ASC : SORT_DESC;
          }
        }
        $args[] = &$list;
        call_user_func_array('array_multisort', $args);
    }
}
