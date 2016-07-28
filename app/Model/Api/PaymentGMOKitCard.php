<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');

class PaymentGMOKitCard extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PaymentGMOKitCard', '/kit_card', 'gmopayment_v4');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
        (new InfoBox())->deleteCache();
    }

    public $validate = [
        'mono_num' => [
            'checkNotEmpty' => [
                'rule' => 'checkNotEmpty',
                'message' => ['checkNotEmpty', 'box'],
            ],
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'allowEmpty' => true,
                'message' => ['format', 'kit_mono_num'],
            ],
        ],
        'mono_appa_num' => [
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'allowEmpty' => true,
                'message' => ['format', 'kit_mono_num'],
            ],
        ],
        'mono_book_num' => [
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'allowEmpty' => true,
                'message' => ['format', 'kit_mono_num'],
            ],
        ],
        'hako_num' => [
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'allowEmpty' => true,
                'message' => ['format', 'kit_hako_num'],
            ],
        ],
        'hako_appa_num' => [
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'allowEmpty' => true,
                'message' => ['format', 'kit_hako_num'],
            ],
        ],
        'hako_book_num' => [
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'allowEmpty' => true,
                'message' => ['format', 'kit_hako_num'],
            ],
        ],
        'cleaning_num' => [
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'allowEmpty' => true,
                'message' => ['format', 'kit_cleaning_num'],
            ],
        ],

        'card_seq' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'card_seq'],
            ],
            'isStringInteger' => [
                'rule'     => 'isStringInteger',
                'message'  => ['format', 'card_seq'],
            ],
        ],
        'security_cd' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'security_cd'],
            ],
            'isCreditCardSecurityCode' => [
                'rule' => 'isCreditCardSecurityCode',
                'message' => ['format', 'security_cd'],
            ],
        ],
        'kit' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit'],
            ],
        ],
        'name' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_address_name']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 59],
                'message' => ['maxLength', 'kit_address_name', 59]
            ],
        ],
        'tel1' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_tel1']
            ],
            'isPhoneNumberJp' => [
                'rule' => 'isPhoneNumberJp',
                'message' => ['format', 'kit_tel1']
            ],
        ],
        'postal' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_postal']
            ],
            'isPostalCodeJp' => [
                'rule' => 'isPostalCodeJp',
                'message' => ['format', 'kit_postal']
            ],
        ],
        'address' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_address']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 60],
                'message' => ['maxLength', 'kit_address', 60]
            ],
        ],
        'datetime_cd' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_datetime']
            ],
            'isDatetimeDelivery' => [
                'rule' => 'isDatetimeDelivery',
                'message' => ['format', 'kit_datetime']
            ],
        ],
        'address_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_address_name']
            ],
        ],
    ];

    public function checkNotEmpty()
    {
        if (!empty($this->data[$this->model_name]['mono_num']) ||
            !empty($this->data[$this->model_name]['mono_appa_num']) ||
            !empty($this->data[$this->model_name]['mono_book_num']) ||
            !empty($this->data[$this->model_name]['hako_num']) ||
            !empty($this->data[$this->model_name]['hako_appa_num']) ||
            !empty($this->data[$this->model_name]['hako_book_num']) ||
            !empty($this->data[$this->model_name]['cleaning_num'])) {
            return true;
        } else {
            return false;
        }
    }
}
