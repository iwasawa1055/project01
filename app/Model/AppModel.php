<?php

App::uses('Model', 'Model');
App::uses('AppValid', 'Lib');
App::uses('ArraySorter', 'Model');

class AppModel extends Model
{

    protected $model_name = null;

    /**
    * [__construct description].
    *
    * @param [type] $name             [description]
    * @param [type] $end              [description]
    * @param string $access_point_key API種別キー
    */
    public function __construct($name)
    {
        parent::__construct();
        $this->model_name = $name;
    }

    public function toArray()
    {
        return $this->data[$this->model_name];
    }

    /* sort */

    public function sort(&$list, $sortKey = [], $defaultSortKey = []) {
        $sortKeyList = array_merge($sortKey, $defaultSortKey);
        $sorter = new ArraySorter($sortKeyList);
        usort($list, [$sorter, 'cmp']);
    }

    /* paginate */

    public function paginateCount($conditions, $recursive)
    {
        //レコード件数を取得するコードを記述
        $count = count($conditions);
        return $count;
    }

    public function paginate($conditions, $fields, $order, $limit, $page, $recursive)
    {
        $start = ($page - 1) * $limit;
        $end = ($page) * $limit;
        $count = count($conditions);
        if ($count < $end) {
            $end = $count;
        }

        //レコードを取得するコードを記述
        $list = [];
        for ($i = 0; ($start + $i) < $end; $i++) {
            $list[$i] = $conditions[$start + $i];
        }

        return $list;
    }


    /* Valid */

    public function isStringInteger($_check)
    {
        $value = current($_check);
        return AppValid::isStringInteger($value);
    }

    public function isCreditCardNumber($_check)
    {
        $value = current($_check);
        return AppValid::isCreditCardNumber($value);
    }

    public function isCreditCardHolderName($_check)
    {
        $value = current($_check);
        return AppValid::isCreditCardHolderName($value);
    }

    public function isCreditCardSecurityCode($_check)
    {
        $value = current($_check);
        return AppValid::isCreditCardSecurityCode($value);
    }

    public function isCreditCardExpireReverse($_check)
    {
        $value = current($_check);
        return AppValid::isCreditCardExpireReverse($value);
    }

    public function isMailAddress($_check)
    {
        $value = current($_check);
        return AppValid::isMailAddress($value);
    }

    public function isPhoneNumberJp($_check)
    {
        $value = current($_check);
        return AppValid::isPhoneNumberJp($value);
    }

    public function isPostalCodeJp($_check)
    {
        $value = current($_check);
        return AppValid::isPostalCodeJp($value);
    }

    public function isDatetimeDelivery($_check)
    {
        $value = current($_check);
        return AppValid::isDatetimeDelivery($value);
    }
    public function isPrefNameJp($_check, $_pref_pos = null)
    {
        $value = current($_check);
        return Validation::inList($value, AppValid::$prefs);
    }
}
