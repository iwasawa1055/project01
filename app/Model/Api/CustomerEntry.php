<?php

App::uses('ApiModel', 'Model');
App::uses('Announcement', 'Model');

class CustomerEntry extends ApiModel
{
    public function __construct()
    {
        parent::__construct('CustomerEntry', '/entry', 'minikura_v5');
    }

    public function entry()
    {
        $this->data[$this->model_name]['oem_key'] = $this->oem_key;
        $responses = $this->request('/entry', $this->data[$this->model_name], 'POST');

        return $responses;
    }
	/*
	* 暫定 nike_snkrs
	*/
    public function entry_sneakers()
    {
		//* nike_snkrs oem_key
		/* oem_key,alliance_cdが確定しないとasteriaエラー出るので、コメントアウトしておく
		$this->oem_key = Configure::read('api.sneakers.oem_key');
		*/
        $this->data[$this->model_name]['oem_key'] = $this->oem_key;
        $responses = $this->request('/entry', $this->data[$this->model_name], 'POST');

        return $responses;
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
                'required' => true,
                'message' => ['notBlank', 'email'],
             ],
            'isMailAddress' => [
                'rule' => 'isMailAddress',
                'message' => ['format', 'email'],
            ],
        ],
        'password' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'password'],
             ],
            'isLoginPassword' => [
                'rule' => 'isLoginPassword',
                'message' => ['format', 'password'],
            ],
        ],
        'password_confirm' => [
            'notBlank' => [
                'rule' => 'notBlank',
                'required' => true,
                'message' => ['notBlank', 'password_confirm'],
             ],
            'isLoginPassword' => [
                'rule' => 'isLoginPassword',
                'message' => ['format', 'password_confirm'],
            ],
            'confirmPassword' => [
                'rule' => 'confirmPassword',
                'message' => ['confirm', 'password_confirm'],
            ],
        ],
        'newsletter' => [
            'allowedChoice' => [
                'rule' => ['inList', ['0', '1']],
                'message' => ['format', 'newsletter'],
            ],
        ],
    ];

    public function confirmPassword()
    {
        if ($this->data[$this->model_name]['password'] !== $this->data[$this->model_name]['password_confirm']) {
            return false;
        } else {
            return true;
        }
    }
}
