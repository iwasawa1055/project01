<?php

App::uses('ApiCachedModel', 'Model');

class ContactUs extends ApiModel
{
    public function __construct()
    {
        parent::__construct('ContactUs', '/contact');
    }

    public $validate = [
        'division' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'お問い合わせ種別は必須です',
             ],
        ],
        'text' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => 'お問い合わせ内容は必須です',
             ],
        ],
    ];
}
