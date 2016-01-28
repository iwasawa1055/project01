<?php

class CustomerInfoHelper extends Helper
{
    public function setPrefAddress1($customer_info)
    {
        return $customer_info['pref'].$customer_info['address1'];
    }

    public function setName($customer_info)
    {
        return $customer_info['lastname'].'　'.$customer_info['firstname'].'（'.$customer_info['lastname_kana'].'　'.$customer_info['firstname_kana'].'）';
    }
}
