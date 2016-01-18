<?php

class OrderHelper extends Helper
{
    public function kitOrderNum()
    {
        $data = [];
        for ($i = 1; $i <= 20; ++$i) {
            $data[$i] = $i.'箱';
        }

        return $data;
    }
}
