<?php

App::uses('ApiModel', 'Model');

class PaymentAccountTransferKit extends ApiModel
{
    public function __construct()
    {
        parent::__construct('PaymentAccountTransferKit', '/kit');
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
        'address_id' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_address']
            ],
        ],


        'kit' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit'],
            ],
        ],
        'lastname' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_lastname']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'kit_lastname', 29]
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'kit_lastname']
            ],
            'checkHalfWordSpace' => [
                'rule' => 'checkHalfWordSpace',
                'message' => ['notBlank', 'kit_lastname']
            ],
        ],
        'lastname_kana' => [
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'kit_lastname_kana', 29]
            ],
            'isFwKana' => [
                'rule' => 'isFwKana',
                'message' => ['isFwKana', 'kit_lastname_kana']
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'kit_lastname_kana']
            ],
            'checkHalfWordSpace' => [
                'rule' => 'checkHalfWordSpace',
                'message' => ['notBlank', 'kit_lastname_kana']
            ],
        ],
        'firstname' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_firstname']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'kit_firstname', 29]
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'kit_firstname']
            ],
            'checkHalfWordSpace' => [
                'rule' => 'checkHalfWordSpace',
                'message' => ['notBlank', 'kit_firstname']
            ],
        ],
        'firstname_kana' => [
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'kit_firstname_kana', 29]
            ],
            'isFwKana' => [
                'rule' => 'isFwKana',
                'message' => ['isFwKana', 'kit_firstname_kana']
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'kit_firstname_kana']
            ],
            'checkHalfWordSpace' => [
                'rule' => 'checkHalfWordSpace',
                'message' => ['notBlank', 'kit_firstname_kana']
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
        'pref' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_pref']
            ],
            'isPrefNameJp' => [
                'rule' => 'isPrefNameJp',
                'message' => ['format', 'kit_pref']
            ],
        ],
        'address1' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_address1']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 8],
                'message' => ['maxLength', 'kit_address1', 8]
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'kit_address1']
            ],
            'checkHalfWordSpace' => [
                'rule' => 'checkHalfWordSpace',
                'message' => ['notBlank', 'kit_address1']
            ],
        ],
        'address2' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'kit_address2']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 18],
                'message' => ['maxLength', 'kit_address2', 18]
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'kit_address2']
            ],
            'checkHalfWordSpace' => [
                'rule' => 'checkHalfWordSpace',
                'message' => ['notBlank', 'kit_address2']
            ],
        ],
        'address3' => [
            'maxLength' => [
                'rule' => ['maxLength', 30],
                'allowEmpty' => true,
                'message' => ['maxLength', 'kit_address3', 30]
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
