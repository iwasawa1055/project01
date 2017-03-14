<?php

App::uses('ApiModel', 'Model');

class CorporateRegistInfo extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CorporateRegistInfo', '/corporate', 'minikura_v5');
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
    }

    public function regist()
    {
        $this->data[$this->model_name]['oem_key'] = $this->oem_key;
        $responses = $this->request('/corporate', $this->data[$this->model_name], 'POST');

        return $responses;
    }

    public $validate = [
        'company_name' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'company_name']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'company_name', 29]
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'company_name']
            ],
        ],
        'company_name_kana' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'company_name_kana']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'company_name_kana', 29]
            ],
            'isFwKana' => [
                'rule' => 'isFwKana',
                'message' => ['isFwKana', 'company_name_kana']
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'company_name_kana']
            ],
        ],
        'staff_name' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'staff_name']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'staff_name', 29]
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'staff_name']
            ],
        ],
        'staff_name_kana' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'staff_name_kana']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' => ['maxLength', 'staff_name_kana', 29]
            ],
            'isFwKana' => [
                'rule' => 'isFwKana',
                'message' => ['isFwKana', 'staff_name_kana']
            ],
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
                'message' => ['notBlank', 'staff_name_kana']
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
        'email' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'email'],
             ],
            'isMailAddress' => [
                'rule' => 'isMailAddress',
                'message' => ['format', 'email'],
            ],
        ],
        'password' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'password'],
             ],
            'isLoginPassword' => [
                'rule' => 'isLoginPassword',
                'message' => ['format', 'password'],
            ],
        ],
        'password_confirm' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'password_confirm'],
             ],
            'isLoginPassword' => [
                'rule' => 'isLoginPassword',
                'message' => ['format', 'password_confirm'],
            ],
            'confirmPassword' => [
                'rule' => 'confirmPassword',
                'message' => ['confirm', 'password_confirm'],
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
                'message' => ['format', 'postal']
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
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
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
            'checkFullWordSpace' => [
                'rule' => 'checkFullWordSpace',
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
        'payment_method' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'payment_method']
            ],
            'allowedChoice' => [
                'rule' => ['inList', ['0', '1']],
                'message' => ['format', 'payment_method']
            ],
        ],
        'newsletter' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'newsletter']
            ],
            'allowedChoice' => [
                'rule' => ['inList', ['0', '1']],
                'message' => ['format', 'newsletter']
            ],
        ],
    ];

    public function confirmPassword()
    {
        if ($this->data[$this->model_name]['password'] !== $this->data[$this->model_name]['password_confirm']) {
            return false;
        } else {
            return true;
        }
    }
}
