<?php

App::uses('ZendeskModel', 'Model');

class ZendeskInquiry extends ZendeskModel
{
    public function __construct($name = 'ZendeskInquiry')
    {
        parent::__construct($name);
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
        'email' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'email']
             ],
            'isMailAddress' => [
                'rule' => 'isMailAddress',
                'message' => ['format', 'email']
            ],
        ],
        'division' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'contact_division'],
             ],
        ],
        'comment' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'contact_text'],
             ],
            'isNot4ByteString' => [
                'rule' => 'isNot4ByteString',
                'message' => ['isNot4ByteString', 'contact_text'],
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


    /**
     * editContactUsComment
     *     不具合報告 コメントマージ
     * @param array お問い合わせ内容
     */
    public function editContactUsComment($data)
    {
        if ($data['division'] === CONTACT_DIVISION_BUG) {
            $data['comment'] .= "\n\n\n";
            $data['comment'] .= "==== 不具合発生日時 ====\n";
            $data['comment'] .= $data['bug_datetime'] . "\n\n";
            $data['comment'] .= "==== 不具合発生 URL（ページ） ====\n";
            $data['comment'] .= $data['bug_url'] . "\n\n";
            $data['comment'] .= "==== ご利用環境（OS・ブラウザ）====\n";
            $data['comment'] .= $data['bug_environment'] . "\n\n";
            $data['comment'] .= "==== 具体的な操作と症状 ====\n";
            $data['comment'] .= $data['bug_text'] . "\n\n";
        }

        unset($data['bug_datetime']);
        unset($data['bug_url']);
        unset($data['bug_environment']);
        unset($data['bug_text']);
        return $data;
    }
}
