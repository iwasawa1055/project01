<?php

App::uses('ApiModel', 'Model');

class ContactUsCorporate extends ApiModel
{
    public function __construct()
    {
        parent::__construct('ContactUsCorporate', '/contact_corporate');
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
