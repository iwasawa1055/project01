<?php

App::uses('Model', 'Model');
App::uses('AppValid', 'Lib');

/**
 * モデル基底クラス
 */
class AppModel extends Model
{
    /**
     * モデル名
     */
    protected $model_name = null;

    /**
     * 言語リソースのドメイン名
     */
    public $validationDomain = 'validation';

    /**
     * コンストラクタ
     * @param string $name モデル名
     */
    public function __construct($name)
    {
        parent::__construct();
        $this->model_name = $name;
    }

    /**
     * モデル名を取得
     * @return [type] [description]
     */
    public function getModelName() {
        return $this->model_name;
    }

    /**
     * データ配列を取得
     * @return array データ1次元配列
     */
    public function toArray()
    {
        if (array_key_exists($this->model_name, $this->data)) {
            return $this->data[$this->model_name];
        }
        return [];
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

    public function isDate($_check)
    {
        $value = current($_check);
        return AppValid::isDate($value);
    }

    public function isFwKana($_check)
    {
        $value = current($_check);
        return AppValid::isFwKana($value);
    }

    public function isLoginPassword($_check)
    {
        $value = current($_check);
        return AppValid::isLoginPassword($value);
    }

    public function isNotOnlySpace($_check)
    {
        $value = current($_check);
        return AppValid::isNotOnlySpace($value);
    }

}
