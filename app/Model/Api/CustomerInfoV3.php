<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');

class CustomerInfoV3 extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CustomerInfoV3', '/user');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
    }

    public $validate = [
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
                'message' => ['notBlank', 'lastname']
            ],
        ],
        'lastname_kana' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'lastname_kana']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'lastname_kana', 29]
            ],
            'isFwKana' => [
                'rule' => 'isFwKana',
                'message' => ['isFwKana', 'lastname_kana']
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
        'firstname_kana' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'firstname_kana']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'firstname_kana', 29]
            ],
            'isFwKana' => [
                'rule' => 'isFwKana',
                'message' => ['isFwKana', 'firstname_kana']
            ],
        ],
        'gender' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'gender'],
            ],
            'allowedChoice' => [
                'rule' => ['inList', ['m', 'f']],
                'message' => ['format', 'gender'],
            ],
        ],
        'birth' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'birth'],
            ],
            'isDate' => [
                'rule' => 'isDate',
                'message' => ['format', 'birth'],
            ],
        ],
        'tel1' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'tel1']
            ],
            'isPhoneNumberJp' => [
                'rule' => 'isPhoneNumberJp',
                'message' => ['format', 'tel1']
            ],
        ],
        'postal' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'postal']
            ],
            'isPostalCodeJp' => [
                'rule' => 'isPostalCodeJp',
                'message' => ['format_format', 'postal', '例、110-0001']
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
        'newsletter' => [
            'allowedChoice' => [
                'rule' => ['inList', ['0', '1']],
                'message' => ['format', 'newsletter']
            ],
        ],
    ];
}
