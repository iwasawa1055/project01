<?php

App::uses('ApiModel', 'Model');

class Inquiry extends ApiModel
{
    public function __construct($name = 'Inquiry', $end = '/contact', $access_point_key = 'minikura_v3')
    {
        parent::__construct($name, $end, $access_point_key);
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
        'text' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'contact_text'],
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

    public function apiPost($data)
    {
        if (array_key_exists($this->model_name, $data)) {
            $data = $data[$this->model_name];
        }
        $data['oem_key'] = $this->oem_key;
        $d = $this->request($this->end_point, $data, 'POST');

        return $d;
    }

    public function editText($data)
    {
        if ($data['Inquiry']['division'] === CONTACT_DIVISION_BUG) {
            $data['Inquiry']['text'] .= "\n\n\n";
            $data['Inquiry']['text'] .= "==== 不具合発生日時 ====\n";
            $data['Inquiry']['text'] .= $data['Inquiry']['bug_datetime'] . "\n\n";
            $data['Inquiry']['text'] .= "==== 不具合発生 URL（ページ） ====\n";
            $data['Inquiry']['text'] .= $data['Inquiry']['bug_url'] . "\n\n";
            $data['Inquiry']['text'] .= "==== ご利用環境（OS・ブラウザ）====\n";
            $data['Inquiry']['text'] .= $data['Inquiry']['bug_environment'] . "\n\n";
            $data['Inquiry']['text'] .= "==== 具体的な操作と症状 ====\n";
            $data['Inquiry']['text'] .= $data['Inquiry']['bug_text'] . "\n\n";
        }

        unset($data['Inquiry']['bug_datetime']);
        unset($data['Inquiry']['bug_url']);
        unset($data['Inquiry']['bug_environment']);
        unset($data['Inquiry']['bug_text']);
        return $data;
    }
}
