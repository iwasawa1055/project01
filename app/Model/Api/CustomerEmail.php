<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');

class CustomerEmail extends ApiModel
{
    public function __construct($name = 'CustomerEmail', $end = '/email', $access_point_key = 'minikura_v3')
    {
        parent::__construct($name, $end, $access_point_key);
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
    }

    public $validate = [
        'email' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => ['notBlank', 'email'],
             ],
            'isMailAddress' => [
                'rule' => 'isMailAddress',
                'message' => ['format', 'email'],
            ],
        ],
        'email_confirm' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => ['notBlank', 'email_confirm'],
             ],
            'isMailAddress' => [
                'rule' => 'isMailAddress',
                'message' => ['notBlank', 'email_confirm'],
            ],
            'confirmEmail' => [
                'rule' => 'confirmEmail',
                'message' => ['confirm', 'email'],
            ],
        ],
    ];

    public function confirmEmail()
    {
        if ($this->data[$this->model_name]['email'] !== $this->data[$this->model_name]['email_confirm']) {
            return false;
        } else {
            return true;
        }
    }
}
