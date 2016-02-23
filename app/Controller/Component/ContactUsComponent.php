<?php

App::uses('ContactUs', 'Model');
App::uses('ContactUsCorporate', 'Model');

class ContactUsComponent extends Component
{
    private $set = null;

    public function init($division)
    {
        $this->set = ContactUsSet::create($division);
    }
    public function model($data)
    {
        return $this->set->getModel($data);
    }
}

abstract class ContactUsSet
{
    abstract public function getModel($data = []);
    public static function create($division)
    {
        $set = null;
        if ($division === CUSTOMER_DIVISION_CORPORATE) {
            $set = new SetCorporate();
        } elseif ($division === CUSTOMER_DIVISION_PRIVATE) {
            $set = new SetPrivate();
        }
        return $set;
    }
}
class SetCorporate extends ContactUsSet
{
    public function getModel($data = [])
    {
        $model = new ContactUsCorporate();
        $model->set([$model->getModelName() => $data]);
        return $model;
    }
}
class SetPrivate extends ContactUsSet
{
    public function getModel($data = [])
    {
        $model = new ContactUs();
        $model->set([$model->getModelName() => $data]);
        return $model;
    }
}
