<?php

App::uses('CustomerAddress', 'Model');
App::uses('CustomerInfo', 'Model');
App::uses('CustomerInfoV3', 'Model');
App::uses('CorporateInfo', 'Model');
App::uses('CustomerLogin', 'Model');

App::uses('CustomerEnvAuthed', 'Model');
App::uses('CustomerPassword', 'Model');
App::uses('ContactUs', 'Model');
App::uses('CustomerEmail', 'Model');

App::uses('EntryCustomerEnv', 'Model');
App::uses('EntryCustomerPassword', 'Model');
App::uses('EntryContactUs', 'Model');
App::uses('EntryCustomerEmail', 'Model');
App::uses('CustomerData', 'Model');

class CustomerComponent extends Component
{
    private $data = null;

    public function __construct()
    {
        $this->data = CustomerData::restore();
    }

    /**
     * カスタマー状態を見てメールアドレス操作モデルを取得
     * @param  array $data モデルデータ（モデル名不要）
     * @return object
     */
    public function getEmailModel($data = [])
    {
        $model = null;
        if ($this->isLogined()) {
            if ($this->isEntry()) {
                $model = new EntryCustomerEmail();
            } else {
                $model = new CustomerEmail();
            }
        }
        if (!empty($model)) {
            $model->set([$model->getModelName() => $data]);
        }
        return $model;
    }

    /**
     * カスタマー状態を見てパスワード操作モデルを取得
     * @param  array $data モデルデータ（モデル名不要）
     * @return object
     */
    public function getPasswordModel($data = [])
    {
        $model = null;
        if ($this->isLogined()) {
            if ($this->isEntry()) {
                $model = new EntryCustomerPassword();
            } else {
                $model = new CustomerPassword();
            }
        }
        if (!empty($model)) {
            $model->set([$model->getModelName() => $data]);
        }
        return $model;
    }


    /**
     * カスタマー状態を見て契約情報取得モデルを取得
     * @return object
     */
    public function getInfoGetModel()
    {
        $model = null;
        if ($this->isLogined()) {
            if ($this->isPrivateCustomer()) {
                $model = new CustomerInfo();
            } else {
                $model = new CorporateInfo();
            }
        }
        return $model;
    }

    /**
     * カスタマー状態を見て契約情報登録モデルを取得
     * @param  array $data モデルデータ（モデル名不要）
     * @return object
     */
    public function getInfoPostModel($data = [])
    {
        $model = null;
        if ($this->isLogined()) {
            if ($this->isPrivateCustomer()) {
                $model = new CustomerInfo();
            }
        }
        if (!empty($model)) {
            $model->set([$model->getModelName() => $data]);
        }
        return $model;
    }

    /**
     * カスタマー状態を見て契約情報更新モデルを取得
     * @param  array $data モデルデータ（モデル名不要）
     * @return object
     */
    public function getInfoPatchModel($data = [])
    {
        $model = null;
        if ($this->isLogined()) {
            if ($this->isPrivateCustomer()) {
                $model = new CustomerInfoV3();
            }
        }
        if (!empty($model)) {
            $model->set([$model->getModelName() => $data]);
        }
        return $model;
    }

    /**
     * カスタマー状態を見て契約情報操作モデルを取得
     * @param  array $data モデルデータ（モデル名不要）
     * @return object
     */
    public function getContactModel($data = [])
    {
        $model = null;
        if ($this->isLogined()) {
            if ($this->isEntry()) {
                $model = new EntryContactUs();
            } elseif ($this->isPrivateCustomer()) {
                $model = new ContactUs();
            } else {
                $model = new ContactUsCorporate();
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

    public function canOrderKit()
    {
        if (!$this->isEntry() &&
            ($this->hasCreditCard() || $this->getCorporatePayment() === ACCOUNT_SITUATION_REGISTRATION)) {
            return true;
        }
        return false;
    }
    public function canInbound()
    {
        return $this->canOrderKit();
    }
    public function canOutbound()
    {
        return $this->canOrderKit();
    }

    public function setTokenAndSave($data)
    {
        return $this->data->setTokenAndSave($data);
    }
    public function setInfoAndSave($data)
    {
        return $this->data->setInfoAndSave($data);
    }
    public function getInfo()
    {
        return $this->data->getInfo();
    }
    public function getToken()
    {
        return $this->data->token;
    }
    public function setPassword($data)
    {
        return $this->data->setPassword($data);
    }
    public function getPassword()
    {
        return $this->data->getPassword();
    }
    public function switchEntryToCustomer()
    {
        return $this->data->switchEntryToCustomer();
    }
    public function getDefaultCard()
    {
        return $this->data->getDefaultCard();
    }
    public function reloadInfo()
    {
        return $this->data->reloadInfo();
    }

    public function postEnvAuthed()
    {
        if ($this->isLogined()) {
            if ($this->data->isEntry()) {
                $o = new EntryCustomerEnv();
                $o->apiPostEnv($this->getInfo()['email']);
            } else {
                $o = new CustomerEnvAuthed();
                $o->apiPostEnv($this->getInfo()['email']);
            }
        }
    }
}
