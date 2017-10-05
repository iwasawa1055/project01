<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');

class ContactUs extends ApiModel
{
    public function __construct($name = 'ContactUs', $end = '/contact', $access_point_key = 'minikura_v3')
    {
        parent::__construct($name, $end, $access_point_key);
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
    }

    // 不具合報告の場合、取得した情報をtextにマージ
    public function editText($data)
    {
        if ($data['division'] === CONTACT_DIVISION_BUG) {
            $data['text'] .= "\n\n\n";
            $data['text'] .= "==== 不具合発生日時 ====\n";
            $data['text'] .= $data['bug_datetime'] . "\n\n";
            $data['text'] .= "==== 不具合発生 URL（ページ） ====\n";
            $data['text'] .= $data['bug_url'] . "\n\n";
            $data['text'] .= "==== ご利用環境（OS・ブラウザ）====\n";
            $data['text'] .= $data['bug_environment'] . "\n\n";
            $data['text'] .= "==== 具体的な操作と症状 ====\n";
            $data['text'] .= $data['bug_text'] . "\n\n";
        }
        unset($data['bug_datetime']);
        unset($data['bug_url']);
        unset($data['bug_environment']);
        unset($data['bug_text']);
        return $data;
    }


    public $validate = [
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
                'message' => 'お問い合わせ内容は1000文字以内で入力してください。お問い合わせ種別が「不具合報告」の場合、不具合報告の全ての内容を含めて1000文字以内で入力してください。',
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
}
