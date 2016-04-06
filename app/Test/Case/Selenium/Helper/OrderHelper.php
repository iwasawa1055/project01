<?php
require_once 'SeleniumHelper.php';

class OrderHelper extends SeleniumHelper
{
    public function accessAdd()
    {
        $this->test->urlAndWait('/order/add');
    }

    public function inputAddFrom($data)
    {
        if (array_key_exists('mono_num', $data)) {
            $this->test->selectOption('#OrderKitMonoNum', $data['mono_num']);
        }
        if (array_key_exists('mono_appa_num', $data)) {
            $this->test->selectOption('#OrderKitMonoAppaNum', $data['mono_appa_num']);
        }
        if (array_key_exists('mono_book_num', $data)) {
            $this->test->selectOption('#OrderKitMonoBookNum', $data['mono_book_num']);
        }
        if (array_key_exists('hako_num', $data)) {
            $this->test->selectOption('#OrderKitHakoNum', $data['hako_num']);
        }
        if (array_key_exists('hako_appa_num', $data)) {
            $this->test->selectOption('#OrderKitHakoAppaNum', $data['hako_appa_num']);
        }
        if (array_key_exists('hako_book_num', $data)) {
            $this->test->selectOption('#OrderKitHakoBookNum', $data['hako_book_num']);
        }
        if (array_key_exists('cleaning_num', $data)) {
            $this->test->selectOption('#OrderKitCleaningNum', $data['cleaning_num']);
        }
        if (array_key_exists('security_cd', $data)) {
            $this->test->firstEl('#OrderKitSecurityCd')->clear();
            $this->test->firstEl('#OrderKitSecurityCd')->value($data['security_cd']);
        }
        if (array_key_exists('address_id', $data)) {
            $this->test->selectOption('#OrderKitAddressId', $data['address_id']);
        }
        if (array_key_exists('datetime_cd', $data)) {
            $this->test->selectOption('#OrderKitDatetimeCd', $data['datetime_cd'], 5);
        }
    }
    public function getAddFrom()
    {
        $data = [
            'mono_num' => $this->test->firstEl('#OrderKitMonoNum')->value(),
            'mono_appa_num' => $this->test->firstEl('#OrderKitMonoAppaNum')->value(),
            'mono_book_num' => $this->test->firstEl('#OrderKitMonoBookNum')->value(),
            'hako_num' => $this->test->firstEl('#OrderKitHakoNum')->value(),
            'hako_appa_num' => $this->test->firstEl('#OrderKitHakoAppaNum')->value(),
            'hako_book_num' => $this->test->firstEl('#OrderKitHakoBookNum')->value(),
            'cleaning_num' => $this->test->firstEl('#OrderKitCleaningNum')->value(),
            'security_cd' => $this->test->firstEl('#OrderKitSecurityCd')->value(),
            'address_id' => $this->test->selectEl('#OrderKitAddressId')->value(),
            'datetime_cd' => $this->test->firstEl('#OrderKitDatetimeCd')->value(),
        ];
        return $data;
    }

    public function clickAddClear()
    {
        $this->clickElByText('クリア', '#OrderKitAddForm a.btn');
    }

    public function clickAddSubmit()
    {
        $this->test->firstEl('#OrderKitAddForm button[type=submit]')->click();
        $this->test->waitPageLoad();
    }

    public function checkConfirmCheckbox()
    {
        $this->test->firstEl('.agree-before-submit[type="checkbox"]')->click();
    }
    public function clickConfirmSubmit()
    {
        $this->test->firstEl('#confirmForm button[type=submit]')->click();
        $this->test->waitPageLoad();
    }

    public function clickConfirmReturn()
    {
        $this->clickElByText('戻る', "#confirmForm a.btn");
    }

    public function clickCompleteReturn()
    {
        $this->clickElByText('マイページへ戻る');
    }
}
