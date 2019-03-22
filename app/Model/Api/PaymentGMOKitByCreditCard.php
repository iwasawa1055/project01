<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');
App::uses('InfoBox', 'Model');

class PaymentGMOKitByCreditCard extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PaymentGMOKitByCreditCard', '/kit_by_credit_card', 'gmopayment_v4');
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
                'message' => ['format', 'kit_mono_regular_num'],
            ],
        ],
        'mono_appa_num' => [
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'allowEmpty' => true,
                'message' => ['format', 'kit_mono_wide_num'],
            ],
        ],
        'hako_num' => [
            'checkNotEmpty' => [
                'rule' => 'checkNotEmpty',
                'message' => ['checkNotEmpty', 'box'],
            ],
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'allowEmpty' => true,
                'message' => ['format', 'kit_hako_regular_num'],
            ],
        ],
        'hako_appa_num' => [
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'allowEmpty' => true,
                'message' => ['format', 'kit_hako_wide_num'],
            ],
        ],
        'hako_book_num' => [
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'allowEmpty' => true,
                'message' => ['format', 'kit_hako_book_num'],
            ],
        ],
        'cleaning_num' => [
            'checkNotEmpty' => [
                'rule' => 'checkNotEmpty',
                'message' => ['checkNotEmpty', 'box'],
            ],
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'allowEmpty' => true,
                'message' => ['format', 'kit_cleaning_num'],
            ],
        ],
        'library_num' => [
            'checkNotEmpty' => [
                'rule' => 'checkNotEmpty',
                'message' => ['checkNotEmpty', 'box'],
            ],
            'isStringInteger' => [
                'rule' => 'isStringInteger',
                'allowEmpty' => true,
                'message' => ['format', 'kit_library_num'],
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
            'isNotOnlySpace' => [
                'rule' => 'isNotOnlySpace',
                'message' => ['notBlank', 'staff_name']
            ],
        ],
        'lastname' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'lastname']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'lastname', 29]
            ],
            'isNotOnlySpace' => [
                'rule' => 'isNotOnlySpace',
                'message' => ['notBlank', 'firstname']
            ],
        ],
        'firstname' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'firstname']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'firstname', 29]
            ],
            'isNotOnlySpace' => [
                'rule' => 'isNotOnlySpace',
                'message' => ['notBlank', 'firstname']
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
            'isNotOnlySpace' => [
                'rule' => 'isNotOnlySpace',
                'message' => ['notBlank', 'staff_name']
            ],
        ],
        'pref' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'pref']
            ],
            'isPrefNameJp' => [
                'rule' => 'isPrefNameJp',
                'message' => ['format', 'pref']
            ],
        ],
        'address1' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'address1']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 8],
                'message' => ['maxLength', 'address1', 8]
            ],
            'isNotOnlySpace' => [
                'rule' => 'isNotOnlySpace',
                'message' => ['notBlank', 'address1']
            ],
        ],
        'address2' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'address2']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 18],
                'message' => ['maxLength', 'address2', 18]
            ],
            'isNotOnlySpace' => [
                'rule' => 'isNotOnlySpace',
                'message' => ['notBlank', 'address2']
            ],
        ],
        'address3' => [
            'maxLength' => [
                'rule' => ['maxLength', 30],
                'allowEmpty' => true,
                'message' => ['maxLength', 'address3', 30]
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
            !empty($this->data[$this->model_name]['library_num']) ||
            !empty($this->data[$this->model_name]['cleaning_num'])) {
            return true;
        } else {
            return false;
        }
    }
}
