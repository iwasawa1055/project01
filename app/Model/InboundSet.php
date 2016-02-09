<?php

App::uses('DatePickup', 'Model');
App::uses('TimePickup', 'Model');

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
                $set = new InboundPrivateYamato();
            } elseif ($deliveryType === INBOUND_CARRIER_JPPOST) {
                $set = new InboundPrivateJppost();
            }
        } elseif ($carrierCd === INBOUND_DELIVERY_MANUAL) {
            $set = new InboundManual();
        }
        return $set;
    }
}
class InboundPrivateYamato extends InboundSet
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
class InboundPrivateJppost extends InboundSet
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
class InboundManual extends InboundSet
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
