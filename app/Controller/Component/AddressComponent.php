<?php

App::uses('CustomerAddress', 'Model');

class AddressComponent extends Component
{
    private $list = null;

    public function get()
    {
        // TODO 契約情報も取得する
        if (empty($this->list)) {
            $ca = new CustomerAddress();
            $this->list = $ca->apiGetResults();
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
}
