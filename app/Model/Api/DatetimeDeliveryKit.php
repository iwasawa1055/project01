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
                'required' => true,
                'message' => ['notBlank', 'postal']
            ],
            'isPostalCodeJp' => [
                'rule' => 'isPostalCodeJp',
                'message' => ['format', 'postal']
            ],
        ],
	];
}
