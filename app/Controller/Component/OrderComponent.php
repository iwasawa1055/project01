<?php

App::uses('DatetimeDeliveryKit', 'Model');
App::uses('PaymentAccountTransferKit', 'Model');
App::uses('PaymentGMOKitCard', 'Model');

class OrderComponent extends Component
{
    private $set = null;

    public function init($division, $hasCreditCard)
    {
        $this->set = OrderSet::create($division, $hasCreditCard);
    }
    public function model($data)
    {
        return $this->set->getModel($data);
    }
    public function setAddress($data, $address)
    {
        return $this->set->setAddress($data, $address);
    }
}

abstract class OrderSet
{
    abstract public function getModel($data = []);
    abstract public function setAddress($data, $address);
    public static function create($division, $hasCreditCard)
    {
        $set = null;
        if ($division === CUSTOMER_DIVISION_CORPORATE) {
            if ($hasCreditCard) {
                $set = new SetCreditCard();
            } else {
                $set = new SetAccountTransfer();
            }
        } elseif ($division === CUSTOMER_DIVISION_PRIVATE) {
            $set = new SetCreditCard();
        }
        return $set;
    }
}
class SetAccountTransfer extends OrderSet
{
    public function getModel($data = [])
    {
        $model = new PaymentAccountTransferKit();
        $model->set([$model->getModelName() => $data]);
        return $model;
    }
    public function setAddress($data, $address)
    {
        if (!empty($address)) {
            $data['lastname'] = $address['lastname'];
            $data['lastname_kana'] = $address['lastname_kana'];
            $data['firstname'] = $address['firstname'];
            $data['firstname_kana'] = $address['firstname_kana'];
            $data['tel1'] = $address['tel1'];
            $data['postal'] = $address['postal'];
            $data['pref'] = $address['pref'];
            $data['address1'] = $address['address1'];
            $data['address2'] = $address['address2'];
            $data['address3'] = $address['address3'];
        }

        $model = new PaymentAccountTransferKit();
        $model->set([$model->getModelName() => $data]);
        return $model;
    }
}
class SetCreditCard extends OrderSet
{
    public function getModel($data = [])
    {
        $model = new PaymentGMOKitCard();
        $model->set([$model->getModelName() => $data]);
        return $model;
    }
    public function setAddress($data, $address)
    {
        if (!empty($address)) {
            $data['name'] = "{$address['lastname']}　{$address['firstname']}";
            $data['tel1'] = $address['tel1'];
            $data['postal'] = $address['postal'];
            $data['address'] = "{$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}";
        }

        $model = new PaymentGMOKitCard();
        $model->set([$model->getModelName() => $data]);
        return $model;
    }
}
