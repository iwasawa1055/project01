<?php

App::uses('AppHttp', 'Lib');
App::uses('AppValid', 'Lib');
App::uses('ApiModel', 'Model');

class DatetimeDeliveryKit extends ApiModel
{
	public function __construct()
	{
		parent::__construct('DatetimeDeliveryKit', '/datetime_delivery_kit');
	}

	public $validate = [
        'postal' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => '郵便番号は必須です',
            ],
            'isPostalCodeJp' => [
                'rule' => 'isPostalCodeJp',
                'message' => '郵便番号の形式が正しくありません',
            ],
        ],
	];
}
