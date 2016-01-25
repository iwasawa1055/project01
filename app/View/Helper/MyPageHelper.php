<?php

class MyPageHelper extends AppHelper {

    private $KitCdToClassName = [
        '64' => 'hako-box',
        '65' => 'hako-box',
        '81' => 'hako-box',
        '66' => 'mono-box',
        '67' => 'mono-box',
        '82' => 'mono-box',
        '75' => 'cleaning-box',
    ];

    public function kitCdToClassName($kitCd) {
        if (array_key_exists($kitCd, $this->KitCdToClassName)) {
            return $this->KitCdToClassName[$kitCd];
        }
        return current($this->KitCdToClassName);
    }
}
