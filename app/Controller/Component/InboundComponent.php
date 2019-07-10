<?php

App::uses('DatePickup', 'Model');
App::uses('TimePickup', 'Model');
App::uses('InboundManual', 'Model');
App::uses('InboundPrivate', 'Model');
App::uses('InboundYamato', 'Model');
App::uses('ReInboundYamato', 'Model');
App::uses('PickupYamatoDateTime', 'Model');

class InboundComponent extends Component
{
    private $set = null;

    public function init($data)
    {
        $data = $this->convertData($data);
        $carrierCd = $data['carrier_cd'];
        $deliveryType = $data['delivery_type'];
        $boxType = $data['box_type'];
        $this->set = InboundSet::create($carrierCd, $deliveryType, $boxType);
    }
    private function convertData($data = [])
    {
        $a = explode('_', $data['delivery_carrier']);
        $deliveryType = Hash::get($a, 0);
        $carrierCd = Hash::get($a, 1);
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
    public function datetime()
    {
        return $this->set->getDateTime();
    }
    public function model($data)
    {
        $data = $this->convertData($data);
        return $this->set->getModel($data);
    }

    public static function createBoxParam($item)
    {
        $kitCd = self::getDefualt($item, 'kit_cd');
        $productCd = InfoBox::kitCd2ProductCd($kitCd);
        $boxId = self::getDefualt($item, 'box_id');
        // 「半角コロンまたはカンマ」をそれぞれ全角に自動変換
        $title = InfoBox::replaceBoxtitleChar(self::getDefualt($item, 'title'));
        $option = self::getDefualt($item, 'option');
        $wrapping_type = self::getDefualt($item, 'wrapping_type');
        return "${productCd}:${boxId}:${title}:${option}:${wrapping_type}";
    }

    public static function getDefualt($a, $k, $d = '')
    {
        if (array_key_exists($k, $a)) {
            return $a[$k];
        }
        return $d;
    }
}


abstract class InboundSet
{
    abstract public function getDate();
    abstract public function getTime();
    abstract public function getDateTime();
    abstract public function getModel($data = []);
    public static function create($carrierCd, $deliveryType, $boxType = null)
    {
        $set = null;
        if ($deliveryType === INBOUND_DELIVERY_PICKUP) {
            if ($carrierCd === INBOUND_CARRIER_YAMAYO) {
                $set = new SetInboundPrivateYamato();
            } elseif ($carrierCd === INBOUND_CARRIER_JPPOST) {
                $set = new SetInboundPrivateJppost();
            }
        } elseif ($deliveryType === INBOUND_DELIVERY_MANUAL) {
            $set = new SetInboundManual();
        }
        return $set;
    }
}
class SetInboundPrivateYamato extends InboundSet
{
    public function getDate()
    {
        $week = ['(日)', '(月)', '(火)', '(水)', '(木)', '(金)', '(土)'];

        $pickupYamato = new PickupYamatoDateTime();
        $datetime = $pickupYamato->getPickupYamatoDateTime();

        foreach($datetime->results as $key => $val) {
            $datetime = new DateTime($key);
            $list[] = [
                'date_cd' => $key,
                'text' => str_replace('-', '/', $key) . $week[(int)$datetime->format('w')],
            ];
        }
        return $list;
    }
    public function getTime()
    {
        $date = new TimePickup();
        $list = $date->apiGetResults();
        return $list;
    }
    public function getDateTime()
    {
        $pickupYamato = new PickupYamatoDateTime();
        $datetime = $pickupYamato->getPickupYamatoDateTime();
        return $datetime;
    }
    public function getModel($data = [])
    {
        if ($data['box_type'] == 'old') {
            $model = new ReInboundYamato();
        } else {
            $model = new InboundYamato();
        }
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
        $list = $date->apiGetResults(['time' => 7]);
        return $list;
    }
    public function getDateTime()
    {
        $date_list = $this->getDate();
        $time_list = $this->getTime();

        foreach($time_list as $key => $val) {
            $time[$val['time_cd']] = $val['text'];
        }

        $datetime = null;
        foreach($date_list as $key => $val) {
            $datetime[$val['date_cd']] = $time;
        }
        $results = $datetime;

        return compact('results');
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
    public function getDateTime()
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
