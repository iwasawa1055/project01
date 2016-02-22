<?php

App::uses('CustomerAddress', 'Model');
App::uses('CustomerInfo', 'Model');
App::uses('CorporateInfo', 'Model');

class AddressComponent extends Component
{

    const CUSTOMER_INFO_ADDRESS_ID = '-10';

    private $list = null;

    public function get($isPrivateCustomer = true)
    {
        if (empty($this->list)) {
            $ca = new CustomerAddress();
            $this->list = $ca->apiGetResults();

            if ($isPrivateCustomer) {
                // 契約情報を先頭に追加
                $info = new CustomerInfo();
                $res = $info->apiGet();
                if ($res->isSuccess() && count($res->results) === 1) {
                    $new['address_id'] = self::CUSTOMER_INFO_ADDRESS_ID;
                    $this->copy($res->results[0], $new);
                    array_unshift($this->list, $new);
                }
            } else {
                // 契約情報を先頭に追加
                $info = new CorporateInfo();
                $res = $info->apiGet();
                if ($res->isSuccess() && count($res->results) === 1) {
                    $new['address_id'] = self::CUSTOMER_INFO_ADDRESS_ID;
                    $this->copy_corporate($res->results[0], $new);
                    array_unshift($this->list, $new);
                }
            }
        }
        return $this->list;
    }
    public function find($id)
    {
        foreach ($this->list as $a) {
            if ($a['address_id'] === $id) {
                return $a;
            }
        }
        return null;
    }
    public function merge($id, $data)
    {
        $a = $this->find($id);
        if (!empty($a)) {
            $this->copy($a, $data);
        }
        return $data;
    }
    private function copy($from, &$to)
    {
        $to['lastname'] = $from['lastname'];
        $to['lastname_kana'] = $from['lastname_kana'];
        $to['firstname'] = $from['firstname'];
        $to['firstname_kana'] = $from['firstname_kana'];
        $to['tel1'] = $from['tel1'];
        $to['postal'] = $from['postal'];
        $to['pref'] = $from['pref'];
        $to['address1'] = $from['address1'];
        $to['address2'] = $from['address2'];
        $to['address3'] = $from['address3'];
        return $to;
    }
    private function copy_corporate($from, &$to)
    {
        $to['lastname'] = $from['company_name'];
        $to['lastname_kana'] = $from['company_name_kana'];
        $to['firstname'] = $from['staff_name'];
        $to['firstname_kana'] = $from['staff_name_kana'];
        $to['tel1'] = $from['tel1'];
        $to['postal'] = $from['postal'];
        $to['pref'] = $from['pref'];
        $to['address1'] = $from['address1'];
        $to['address2'] = $from['address2'];
        $to['address3'] = $from['address3'];
        return $to;
    }
}
