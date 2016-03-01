<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');

class CustomerPassword extends ApiModel
{
    public function __construct($name = 'CustomerPassword', $end = '/password', $access_point_key = 'minikura_v3')
    {
        parent::__construct($name, $end, $access_point_key);
    }

    protected function triggerDataChanged()
    {
        parent::triggerDataChanged();
        (new Announcement())->deleteCache();
    }

    public $validate = [
        'password' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => ['notBlank', 'current_password'],
             ],
        ],
        'new_password' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => ['notBlank', 'new_password'],
             ],
            'isLoginPassword' => [
                'rule' => 'isLoginPassword',
                'message' => ['format', 'new_password'],
            ],
        ],
        'new_password_confirm' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'message' => ['notBlank', 'new_password_confirm'],
             ],
            'isLoginPassword' => [
                'rule' => 'isLoginPassword',
                'message' => ['format', 'new_password_confirm'],
            ],
            'confirmPassword' => [
                'rule' => 'confirmPassword',
                'message' => ['confirm', 'new_password_confirm'],
            ],
        ],
    ];

    public function confirmPassword()
    {
        if ($this->data[$this->model_name]['new_password'] !== $this->data[$this->model_name]['new_password_confirm']) {
            return false;
        } else {
            return true;
        }
    }
}
