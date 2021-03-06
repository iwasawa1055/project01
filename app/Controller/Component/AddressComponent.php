<?php

App::uses('CustomerAddress', 'Model');
App::uses('CustomerInfo', 'Model');
App::uses('CorporateInfo', 'Model');

class AddressComponent extends Component
{
    const CUSTOMER_INFO_ADDRESS_ID = '-10';
    const CREATE_NEW_ADDRESS_ID = '-99';

    private $list = null;

    public function initialize(Controller $controller)
    {
        $this->Controller = $controller;
    }

    public function get($force = false)
    {
        if ($this->Controller->Customer->isEntry()) {
            return [];
        }
        if (empty($this->list) || $force) {
            $ca = new CustomerAddress();
            $this->list = $ca->apiGetResults();

            // 契約情報を先頭に追加
            $info = $this->Controller->Customer->getInfo();
            $this->copy($info, $new);
            $new['address_id'] = self::CUSTOMER_INFO_ADDRESS_ID;
            array_unshift($this->list, $new);
        }

        return $this->list;
    }
    /**
     * 最終要素を返却
     * @return [type] [description]
     */
    public function last() {
        if (is_array($this->list) && 1 < count($this->list)) {
            return end($this->list);
        }
        return [];
    }
    public function find($id)
    {
        foreach ($this->list as $a) {
            if ($a['address_id'] === $id) {
                return $a;
            }
        }

        return [];
    }
    public function merge($id, $data)
    {
        $a = $this->find($id);
        if (!empty($a)) {
            $data['lastname'] = $a['lastname'];
            $data['lastname_kana'] = $a['lastname_kana'];
            $data['firstname'] = $a['firstname'];
            $data['firstname_kana'] = $a['firstname_kana'];
            $data['tel1'] = $a['tel1'];
            $data['postal'] = $a['postal'];
            $data['pref'] = $a['pref'];
            $data['address1'] = $a['address1'];
            $data['address2'] = $a['address2'];
            $data['address3'] = $a['address3'];
        }

        return $data;
    }
    private function copy($from, &$to)
    {
        if ($this->Controller->Customer->isPrivateCustomer()) {
            $to['lastname'] = $from['lastname'];
            $to['lastname_kana'] = $from['lastname_kana'];
            $to['firstname'] = $from['firstname'];
            $to['firstname_kana'] = $from['firstname_kana'];
        } else {
            $to['lastname'] = $from['company_name'];
            $to['lastname_kana'] = $from['company_name_kana'];
            $to['firstname'] = $from['staff_name'];
            $to['firstname_kana'] = $from['staff_name_kana'];
        }
        $to['tel1'] = $from['tel1'];
        $to['postal'] = $from['postal'];
        $to['pref'] = $from['pref'];
        $to['address1'] = $from['address1'];
        $to['address2'] = $from['address2'];
        $to['address3'] = $from['address3'];

        return $to;
    }
}
