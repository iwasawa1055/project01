<?php

App::uses('ZendeskModel', 'Model');

class ZendeskContactUs extends ZendeskModel
{
    public function __construct($name = 'ZendeskContactUs')
    {
        parent::__construct($name);
    }

    public $validate = [
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


    /**
     * editAnnouncementText
     *     お知らせ内容をお問い合わせにマージ
     * @param array お知らせ内容
     */
    public function editAnnouncementText($data)
    {
        $annoucement = "";
        if (!empty($data['announcement_id'])) {
            $annoucement = "\n\n\n";
            $annoucement .= "メッセージ内容"."\n";
            $annoucement .= "**************************************************"."\n";
            $annoucement .= "メッセージID: ".$data['announcement_id']."\n";
            $annoucement .= $data['datetime'] . "\n\n";
            $annoucement .= $data['title'] . "\n\n";
            $annoucement .= $data['text'];
        }
        return $annoucement;
    }
}
