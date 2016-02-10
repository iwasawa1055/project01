<?php

App::uses('DatePickup', 'Model');
App::uses('TimePickup', 'Model');
App::uses('InboundManual', 'Model');
App::uses('InboundPrivate', 'Model');

class InboundComponent extends Component
{
    private $set = null;

    public function init($data)
    {
        $data = $this->convertData($data);
        $carrierCd = $data['carrier_cd'];
        $deliveryType = $data['delivery_type'];
        $this->set = InboundSet::create($carrierCd, $deliveryType);
    }
    private function convertData($data = [])
    {
        $a = explode('_', $data['delivery_carrier']);
        $carrierCd = Hash::get($a, 0);
        $deliveryType = Hash::get($a, 1);
        $data['delivery_type'] = $deliveryType;
        $data['carrier_cd'] = $carrierCd;
        return $data;
    }
    public function date()
    {
        return $this->set->getDate();
    }
    public function time()
    {
        return $this->set->getTime();
    }
    public function model($data)
    {
        $data = $this->convertData($data);
        return $this->set->getModel($data);
    }
}


abstract class InboundSet
{
    abstract public function getDate();
    abstract public function getTime();
    abstract public function getModel($data = []);
    public static function create($carrierCd, $deliveryType)
    {
        $set = null;
        if ($carrierCd === INBOUND_DELIVERY_PICKUP) {
            if ($deliveryType === INBOUND_CARRIER_YAMAYO) {
                $set = new SetInboundPrivateYamato();
            } elseif ($deliveryType === INBOUND_CARRIER_JPPOST) {
                $set = new SetInboundPrivateJppost();
            }
        } elseif ($carrierCd === INBOUND_DELIVERY_MANUAL) {
            $set = new SetInboundManual();
        }
        return $set;
    }
}
class SetInboundPrivateYamato extends InboundSet
{
    public function getDate()
    {
        $date = new DatePickup();
        $list = $date->apiGetResults();
        return $list;
    }
    public function getTime()
    {
        $date = new TimePickup();
        $list = $date->apiGetResults();
        return $list;
    }
    public function getModel($data = [])
    {
        $model = new InboundPrivate();
        $model->set([$model->getModelName() => $data]);
        return $model;
    }
}
class SetInboundPrivateJppost extends InboundSet
{
    public function getDate()
    {
        $date = new DatePrivate();
        $list = $date->apiGetResults(['calendar' => 2]);
        return $list;
    }
    public function getTime()
    {
        $date = new TimePrivate();
        $list = $date->apiGetResults(['calendar' => 7]);
        return $list;
    }
    public function getModel($data = [])
    {
        $model = new InboundPrivate();
        $model->set([$model->getModelName() => $data]);
        return $model;
    }
}
class SetInboundManual extends InboundSet
{
    public function getDate()
    {
        return [];
    }
    public function getTime()
    {
        return [];
    }
    public function getModel($data = [])
    {
        $model = new InboundManual();
        $model->set([$model->getModelName() => $data]);
        return $model;
    }
}
