<?php

App::uses('ApiModel', 'Model');

class InboundBase extends ApiModel
{
    public static function createBoxParam($item)
    {
        $kitCd = self::getDefualt($item, 'kit_cd');
        $productCd = InfoBox::kitCd2ProductCd($kitCd);
        $boxId = self::getDefualt($item, 'box_id');
        $title = self::getDefualt($item, 'title');
        $option = self::getDefualt($item, 'option');
        return "${productCd}:${boxId}:${title}:${option}";
    }

    public static function getDefualt($a, $k, $d = '')
    {
        if (array_key_exists($k, $a)) {
            return $a[$k];
        }
        return $d;
    }
}
