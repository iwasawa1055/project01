<?php

App::uses('Inquiry', 'Model');

class EntryContactUs extends Inquiry
{
    public function __construct()
    {
        parent::__construct('EntryContactUs', '/entry_contact', 'minikura_v5');
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
        ],
        'firstname_kana' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'firstname_kana']
            ],
            'maxLength' => [
                'rule' => ['maxLength', 29],
                'message' =>  ['maxLength', 'firstname_kana', 29]
            ],
            'isFwKana' => [
                'rule' => 'isFwKana',
                'message' => ['isFwKana', 'firstname_kana']
            ],
        ],
        'division' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'contact_division'],
             ],
        ],
        'text' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'contact_text'],
             ],
            'maxLength' => [
                'rule' => ['maxLength', 1000],
                //'message' => ['maxLength', 'contact_text', 1000]
                'message' => 'お問い合わせ内容は1000文字以内で入力してください。「不具合報告」の場合、不具合報告の内容も含めて1000文字以内で入力してください。',
            ],
        ],
        'bug_datetime' => [
        ],
        'bug_url' => [
        ],
        'bug_environment' => [
        ],
        'bug_text' => [
        ],
    ];
}
