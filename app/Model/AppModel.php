<?php

App::uses('Model', 'Model');
App::uses('AppValid', 'Lib');

class AppModel extends Model
{
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
}
