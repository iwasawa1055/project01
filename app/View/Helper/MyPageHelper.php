<?php

class MyPageHelper extends AppHelper {

    private $productCdToClassName = [
        '004025' => 'mono-box',
        '004024' => 'hako-box',
        '004029' => 'cleaning-box',
        '005000' => 'cleaning-box',
    ];

    public function productCdToClassName($productCd) {
        if (array_key_exists($productCd, $this->productCdToClassName)) {
            return $this->productCdToClassName[$productCd];
        }
        return current($this->productCdToClassName);
    }
}
