<?php

class MyPageHelper extends AppHelper {

    private $KitCdToClassName = [
        KIT_CD_HAKO => 'hako-box',
        KIT_CD_HAKO_APPAREL => 'hako-box',
        KIT_CD_HAKO_BOOK => 'hako-box',
        KIT_CD_MONO => 'mono-box',
        KIT_CD_MONO_APPAREL => 'mono-box',
        KIT_CD_WINE_HAKO => 'hako-box',
        KIT_CD_WINE_MONO => 'mono-box',
        KIT_CD_MONO_BOOK => 'mono-box',

        KIT_CD_CLEANING_PACK => 'cleaning-box',
    ];
    private $productCdToClassName = [
        PRODUCT_CD_MONO => 'mono-box',
        PRODUCT_CD_HAKO => 'hako-box',
        PRODUCT_CD_CLEANING_PACK => 'cleaning-box',
        PRODUCT_CD_SHOES_PACK => 'cleaning-box',
    ];

    public function boxClassName($box) {
        $productCd = $box['product_cd'];
        $kitCd = $box['kit_cd'];
        if (!empty($productCd) && array_key_exists($productCd, $this->productCdToClassName)) {
            return $this->productCdToClassName[$productCd];
        }
        if (!empty($kitCd) && array_key_exists($kitCd, $this->KitCdToClassName)) {
            return $this->KitCdToClassName[$kitCd];
        }
        return current($this->KitCdToClassName);
    }
}
