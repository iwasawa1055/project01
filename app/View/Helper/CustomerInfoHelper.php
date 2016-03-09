<?php

class CustomerInfoHelper extends Helper
{
    public function setPrefAddress1($data)
    {
        return h($data['pref'].$data['address1']);
    }

    public function setName($data)
    {
        // 姓　名（セイ　名）
        return h("{$data['lastname']}　{$data['firstname']}（{$data['lastname_kana']}　{$data['firstname_kana']}）");
    }

    public function setBirth($data)
    {
        // year年month月day日
        return h("{$data['birth_year']}年{$data['birth_month']}月{$data['birth_day']}日");
    }
}
