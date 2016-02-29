<?php

App::uses('CustomerAddress', 'Model');
App::uses('CustomerInfo', 'Model');
App::uses('CorporateInfo', 'Model');
App::uses('CustomerLogin', 'Model');



App::uses('CustomerEnvAuthed', 'Model');
App::uses('CustomerPassword', 'Model');


App::uses('EntryCustomerEnv', 'Model');
App::uses('EntryCustomerPassword', 'Model');

class CustomerComponent extends Component
{
    private $data = null;

    public function __construct()
    {
        $this->data = CustomerData::restore();
    }

    public function getEmailModel($data = [])
    {

    }

    public function getCardModel($data)
    {

    }

    public function getPasswordModel($data = [])
    {
        if ($this->isLogined()) {
            if ($this->isEntry()) {
                return new EntryCustomerPassword();
            } else {
                return new CustomerPassword();
            }
        }
        // $model->set($data);
        return null;
    }

    public function getContactModel($data)
    {

    }

    public function isLogined()
    {
        return !empty(CakeSession::read(CustomerLogin::SESSION_API_TOKEN));
    }

    public function getName()
    {
        if ($this->isLogined()) {
            return $this->data->getCustomerName();
        }
        return '';
    }
    public function isPrivateCustomer()
    {
        if ($this->isLogined()) {
            return $this->data->isPrivateCustomer();
        }
        return null;
    }
    public function getCorporatePayment()
    {
        if ($this->isLogined()) {
            return $this->data->getCorporatePayment();
        }
        return null;
    }
    public function hasCreditCard()
    {
        if ($this->isLogined()) {
            return $this->data->hasCreditCard();
        }
        return null;
    }
    public function isEntry()
    {
        if ($this->isLogined()) {
            return $this->data->isEntry();
        }
        return null;
    }
    public function isPaymentNG()
    {
        if ($this->isLogined()) {
            return $this->data->isPaymentNG();
        }
        return null;
    }
    public function setTokenAndSave($data)
    {
        return $this->data->setTokenAndSave($data);
    }

    public function postEnvAuthed()
    {
        if ($this->isLogined()) {
            if ($this->data->isEntry()) {
                $o = new EntryCustomerEnv();
                $env->apiPostEnv($this->data->getInfo()['email']);
            } else {
                $env = new CustomerEnvAuthed();
                $env->apiPostEnv($this->data->getInfo()['email']);
            }
        }
    }
}
