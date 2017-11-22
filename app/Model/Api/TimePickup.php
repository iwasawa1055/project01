<?php

App::uses('ApiCachedModel', 'Model');

class TimePickup extends ApiCachedModel
{
    public function __construct()
    {
        parent::__construct('TIME_PICKUP_CACHE', 0, 'TimePickup', '/time_pickup');
    }

    public function apiGetResults($arg = [])
    {
        $response = parent::apiGetResults($arg);

        if (!empty($response)) {
            foreach ($response as $key => $val) {
                // time_cd 3を削除
                if ($val['time_cd'] == '3') {
                    unset($response[$key]);
                }
            }
        }
        return $response;
    }
}
