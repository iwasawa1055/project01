<?php

App::uses('ApiModel', 'Model');

class PointUse extends ApiModel
{
    const POINT_USE_AVAILABLE_OR_MORE = 100;
    const POINT_USE_AVAILABLE_UNIT = 10;
    const POINT_USE_VALID_MESSAGE = 'ポイントは %d ポイント以上の残高かつ %d ポイント単位からのご利用となります。';

    public function __construct()
    {
        parent::__construct('PointUse', '/point_use', 'cpss_v5');
    }

    public $validate = [
        'use_point' => [
            'isStringInteger' => [
                'rule'     => 'isStringInteger',
                'allowEmpty' => true,
                'message'  => ['format', 'use_point'],
            ],
            'validPointUse' => [
                'rule'     => 'validPointUse',
            ],
        ],
    ];

    /**
     *・ポイント残高:100未満のユーザーの場合は、ポイントを利用できない
     *・ポイント残高:100以上のユーザーの場合は、ポイントを利用できる
     *・ポイントを利用する場合は、10ポイント単位
     *・ポイント残高を超える場合はバリデーションエラー
     */
    public function validPointUse()
    {
        $use_point = $this->data[$this->model_name]['use_point'];
        // ポイント入力なし
        if (empty($use_point)) {
            return true;
        }

        /* ポイント入力あり */

        // ポイント残高
        $point_balance = $this->data[$this->model_name]['point_balance'];

        // 残高ポイント:100未満
        if ($point_balance < self::POINT_USE_AVAILABLE_OR_MORE) {
            return $this->getValidMessage();
        }

        /* 残高ポイント:100以上 */

        // ポイントを利用する場合は、10ポイント単位
        if (($use_point % self::POINT_USE_AVAILABLE_UNIT) !== 0) {
            return $this->getValidMessage();
        }

        // 利用可能なポイント残高(1桁目切り捨て)
        $available_point_balance = floor(($point_balance) / self::POINT_USE_AVAILABLE_UNIT) * self::POINT_USE_AVAILABLE_UNIT;
        // 利用可能なポイント残高を超える場合はバリデーションエラー
        if ($available_point_balance < $use_point) {
            return $this->getValidMessage();
        }

        return true;
    }

    private function getValidMessage()
    {
        return sprintf(self::POINT_USE_VALID_MESSAGE, self::POINT_USE_AVAILABLE_OR_MORE, self::POINT_USE_AVAILABLE_UNIT);
    }
}
