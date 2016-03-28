<?php

/**
 * 配列ソートクラス
 */
class HashSorter
{
    public $sortKeyList = [];
    private $cmpKitCd = [];
    public function __construct($sortKeyList)
    {
        $this->sortKeyList = $sortKeyList;
        $this->cmpKitCd = [
          KIT_CD_MONO,
          KIT_CD_MONO_APPAREL,
          KIT_CD_MONO_BOOK,
          KIT_CD_HAKO,
          KIT_CD_HAKO_APPAREL,
          KIT_CD_HAKO_BOOK,
          KIT_CD_WINE_HAKO,
          KIT_CD_WINE_MONO,
          KIT_CD_CLEANING_PACK,
        ];
    }
    public function cmp($a, $b)
    {
        $result = 0;
        foreach ($this->sortKeyList as $key => $isAsc) {

            $aValue = Hash::get($a, $key);
            $bValue = Hash::get($b, $key);
            if ($aValue !== $bValue) {
                if (!empty($aValue) && !empty($bValue)) {
                    if ($key === 'kit_cd') {
                        $aValue = array_search($aValue, $this->cmpKitCd);
                        $bValue = array_search($bValue, $this->cmpKitCd);
                    }
                    $result = strcmp($aValue, $bValue);
                } else {
                    $result = empty($aValue) ? 1 : -1;
                }
                break;
            }
        }
        return $result * ($isAsc? 1 : -1);
    }
    public static function sort(&$list, $sortKey = []) {
        $sorter = new HashSorter($sortKey);
        usort($list, [$sorter, 'cmp']);
    }
}
