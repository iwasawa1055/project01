<?php

App::uses('AppModel', 'Model');

class GlobalSreach extends AppModel
{

    public function __construct()
    {
        parent::__construct('GlobalSreach');
    }

    // DB処理を行わない
    public $useTable = false;

    public $validate = [
        'keyword' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => ['notBlank', 'keyword'],
            ],
            'maxLength' => [
                'rule' => ['minLength', 3],
                'message' => ['minLength', 'keyword', 3],
            ],
        ],
    ];
}
