<?php

class ArraySorter
{
    public $sortKeyList = [];
    public function __construct($sortKeyList)
    {
        $this->sortKeyList = $sortKeyList;
    }
    public function cmp($a, $b)
    {
        $result = 1;
        foreach ($this->sortKeyList as $key => $isAsc) {
            $aKeyExist = array_key_exists($key, $a);
            $bKeyExist = array_key_exists($key, $b);
            if ($aKeyExist && $bKeyExist && $a[$key] !== $b[$key]) {
                $result = strcmp($a[$key], $b[$key]);
                break;
            } elseif (!$aKeyExist || !$bKeyExist) {
                $result = $aKeyExist ? 1 : -1;
                break;
            }
        }
        return $result *  ($isAsc? 1 : -1);
    }
}
