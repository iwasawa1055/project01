<?php

App::uses('CustomerAddress', 'Model');
App::uses('CustomerInfo', 'Model');
App::uses('CorporateInfo', 'Model');
App::uses('CustomerLogin', 'Model');

App::uses('CustomerEnvAuthed', 'Model');
App::uses('CustomerPassword', 'Model');
App::uses('ContactUs', 'Model');

App::uses('EntryCustomerEnv', 'Model');
App::uses('EntryCustomerPassword', 'Model');
App::uses('EntryContactUs', 'Model');

class CustomerComponent extends Component
{
    private $data = null;

    public function __construct()
    {
        $this->data = CustomerData::restore();
    }

    public function getEmailModel()
    {
    }

    public function getCardModel()
    {
    }

    public function getPasswordModel()
    {
        if ($this->isLogined()) {
            if ($this->isEntry()) {
                return new EntryCustomerPassword();
            } else {
                return new CustomerPassword();
            }
        }
        return null;
    }

    public function getContactModel($data = [])
    {
        $model = null;
        if ($this->isLogined()) {
            if ($this->isEntry()) {
                $model = new EntryContactUs();
            } else if ($this->toke['division'] === CUSTOMER_DIVISION_CORPORATE) {
                $model = new ContactUsCorporate();
            } else {
                $model = new ContactUs();
            }
        }
        if (!empty($model)) {
            $model->set([$model->getModelName() => $data]);
        }
        return $model;
    }

    public function isLogined()
    {
        if (empty(CakeSession::read(CustomerLogin::SESSION_API_TOKEN))) {
            return false;
        }
        return true;
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
