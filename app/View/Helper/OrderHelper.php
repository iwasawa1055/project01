<?php

class OrderHelper extends Helper
{
    public function kitOrderNum()
    {
        $data = [];
        for ($i = 1; $i <= 20; ++$i) {
            $data[$i] = $i.'箱';
        }

        return $data;
    }

    public function setPayment($payment_cards)
    {
        $data = [];
        if (is_array($payment_cards)) {
            foreach ($payment_cards as $card) {
                $data[$card['card_seq']] = "{$card['card_no']}　{$card['holder_name']}";
            }
        }

        return $data;
    }

    public function setAddress($addresses)
    {
        $data = [];
        if (is_array($addresses)) {
            foreach ($addresses as $address) {
                $data[$address['address_id']] = "〒{$address['postal']} {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}　{$address['lastname']}　{$address['firstname']}";
            }
        }

        return $data;
    }

    public function setDatetime($datetimes)
    {
        $data = [];
        if (is_array($datetimes)) {
            foreach ($datetimes as $datetime) {
                $data[$datetime['datetime_cd']] = "{$datetime['text']}";
            }
        }

        return $data;
    }
}
