<?php

App::uses('Model', 'Model');
App::uses('AppValid', 'Lib');

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
}
