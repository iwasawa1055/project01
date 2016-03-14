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
    public function cmp($a, $b)
    {
        $result = 0;
        foreach ($this->sortKeyList as $key => $isAsc) {

            $aValue = Hash::get($a, $key);
            $bValue = Hash::get($b, $key);
            if ($aValue !== $bValue) {
                if (!empty($aValue) && !empty($bValue)) {
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
