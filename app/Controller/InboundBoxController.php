<?php

App::uses('AppValid', 'Lib');
App::uses('MinikuraController', 'Controller');
App::uses('DatePrivate', 'Model');
App::uses('TimePrivate', 'Model');
App::uses('InboundSelectedBox', 'Model');
App::uses('AmazonPayModel', 'Model');
App::uses('CustomerAddress', 'Model');
App::uses('MtYmstpost', 'Model');

class InboundBoxController extends MinikuraController
{
    const MODEL_NAME = 'Inbound';
    const MODEL_NAME_CUSTOMER_ADDRESS = 'CustomerAddress';
    public $layout = 'style';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->Inbound = $this->Components->load('Inbound');

        $list = $this->InfoBox->getListForInbound();
        $this->set('boxList', $list);

        $this->set('addressList', $this->Address->get());
        $this->set('dateList', []);
        $this->set('timeList', []);
    }

    /**
     * アクセス拒否
     */
    protected function isAccessDeny()
    {
        return !$this->Customer->canInbound();
    }

    /**
     *
     */
    public function getInboundDatetime()
    {
        $this->autoRender = false;
        if (!$this->request->is('ajax')) {
            return false;
        }
        $this->Inbound->init(Hash::get($this->request->data, self::MODEL_NAME));
        $result['date'] = $this->Inbound->date();
        $result['time'] = $this->Inbound->time();
        $result['datetime'] = $this->Inbound->datetime();
        $status = !empty($result);
        return json_encode(compact('status', 'result'));
    }

    /**
     *
     */
    public function add()
    {
        // アマゾンペイメント対応
        if ($this->Customer->isAmazonPay()) {
            $this->redirect(['controller' => 'inbound_box', 'action'=>'add_amazon_pay']);
        }

        $isBack = Hash::get($this->request->query, 'back');
        $data = CakeSession::read(self::MODEL_NAME . 'FORM');
        if ($isBack && !empty($data)) {
            // 前回追加選択は最後のお届け先を選択
            if (Hash::get($data[self::MODEL_NAME], 'address_id') === AddressComponent::CREATE_NEW_ADDRESS_ID) {
                $data[self::MODEL_NAME]['address_id'] = Hash::get($this->Address->last(), 'address_id', '');
            }
            $this->request->data = $data;
            $this->Inbound->init(Hash::get($this->request->data, self::MODEL_NAME));
            $this->set('dateList', $this->Inbound->date());
            $this->set('timeList', $this->Inbound->time());
        }
        CakeSession::delete(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME . 'FORM');
    }

    /**
     *
     */
    public function add_amazon_pay()
    {
        $isBack = Hash::get($this->request->query, 'back');
        $data = CakeSession::read(self::MODEL_NAME . 'FORM');

        if ($isBack && !empty($data)) {
            $this->request->data = $data;
            $this->Inbound->init(Hash::get($this->request->data, self::MODEL_NAME));

            $this->set('dateList', $this->Inbound->date());
            $this->set('timeList', $this->Inbound->time());
        }
        CakeSession::delete(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME . 'FORM');
    }

    /**
     *
     */
    public function confirm()
    {
        $data = Hash::get($this->request->data, self::MODEL_NAME);
        if (empty($data)) {
            return $this->render('add');
        }

        if (isset($data['box_type']) && $data['box_type'] == 'old') {
            $list = $this->InfoBox->getListForInboundOldBox();
            $this->set('boxList', $list);
        }

        // 届け先追加を選択の場合は追加画面へ遷移
        $is_address_error = false;
        if (Hash::get($data, 'address_id') === 'add') {
            $this->loadModel('CustomerAddress');
            $this->CustomerAddress->data['CustomerAddress'] = $this->request->data['CustomerAddress'];
            if (!$this->CustomerAddress->validates()) {
                $is_address_error = false;
            }
            if (isset($this->CustomerAddress->data['CustomerAddress']['resister']) && $this->CustomerAddress->data['CustomerAddress']['resister'] == '1') {
                $this->CustomerAddress->apiPost($this->request->data['CustomerAddress']);
                $list = $this->Address->get(true);

                $data['address_id'] = end($list)['address_id'];
                $this->request->data['Inbound']['address_id'] = end($list)['address_id'];
                $this->set('addressList', $list);
            }
        }

        $dataBoxList = [];
        if (isset($data['box_list'])) {
            $dataBoxList = $data['box_list'];
        }
        unset($data['box_list']);

        $validErrors = [];

        // 選択されたボックスを処理
        $selectedList = [];
        foreach ($dataBoxList as $item) {
            if (!empty($item['checkbox'])) {
                $boxModel = new InboundSelectedBox();
                $boxModel->set([$boxModel->getModelName() => $item]);
                if (!$boxModel->validates()) {
                    $validErrors['box_list'][$item['box_id']] = $boxModel->validationErrors;
                }
                $selectedList[] = InboundComponent::createBoxParam($item);
            }
        }
        // 選択なしはエラー表示
        if (empty($selectedList)) {
            $validErrors['Inbound']['box'] = __d('validation', 'select', __d('validation', 'box'));
        }
        $data['box'] = implode(',', $selectedList);

        // 預け入れ方法入力チェック
        if (empty($data['delivery_carrier'])) {
            $validErrors['Inbound']['delivery_carrier'] = __d('validation', 'notBlank', __d('validation', 'inbound_delivery_carrier'));
        } else {

            $this->Inbound->init($data);
            $this->set('dateList', $this->Inbound->date());
            $this->set('timeList', $this->Inbound->time());

            // モデル取得
            $data = $this->Address->merge($data['address_id'], $data);
            if (Hash::get($data, 'address_id') === 'add') {
                $data["lastname"]       = $this->request->data['CustomerAddress']["lastname"];
                $data["lastname_kana"]  = $this->request->data['CustomerAddress']["lastname_kana"];
                $data["firstname"]      = $this->request->data['CustomerAddress']["firstname"];
                $data["firstname_kana"] = $this->request->data['CustomerAddress']["firstname_kana"];
                $data["tel1"]           = $this->request->data['CustomerAddress']["tel1"];
                $data["postal"]         = $this->request->data['CustomerAddress']["postal"];
                $data["pref"]           = $this->request->data['CustomerAddress']["pref"];
                $data["address1"]       = $this->request->data['CustomerAddress']["address1"];
                $data["address2"]       = $this->request->data['CustomerAddress']["address2"];
                $data["address3"]       = $this->request->data['CustomerAddress']["address3"];
            }
            $model = $this->Inbound->model($data);

            if (empty($model)) {
                $this->Flash->set(__('empty_session_data'));
                return $this->redirect(['action' => 'add']);
            }

            if ($model->validates()) {
                CakeSession::write(self::MODEL_NAME, $model->data);
                CakeSession::write(self::MODEL_NAME . 'FORM', $this->request->data);
            } else {
                $validErrors['Inbound'] = $model->validationErrors;
            }
        }

        if (!empty($validErrors) || $is_address_error) {
            if (!empty($validErrors)) {
                $this->set('validErrors', $validErrors);
                CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' post params ' . print_r($_POST, true));
                CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' /inbound/box validation error ' . print_r($validErrors, true));
            }
            return $this->render('add');
        }
    }


    /**
     *
     */
    public function confirm_amazon_pay()
    {
        $data = Hash::get($this->request->data, self::MODEL_NAME);
        if (empty($data)) {
            return $this->render('add_amazon_pay');
        }

        if (isset($data['box_type']) && $data['box_type'] == 'old') {
            $list = $this->InfoBox->getListForInboundOldBox();
            $this->set('boxList', $list);
        }

        $dataBoxList = $data['box_list'];
        unset($data['box_list']);

        $validErrors = [];

        // 選択されたボックスを処理
        $selectedList = [];
        foreach ($dataBoxList as $item) {
            if (!empty($item['checkbox'])) {
                $boxModel = new InboundSelectedBox();
                $boxModel->set([$boxModel->getModelName() => $item]);
                if (!$boxModel->validates()) {
                    $validErrors['box_list'][$item['box_id']] = $boxModel->validationErrors;
                }
                $selectedList[] = InboundComponent::createBoxParam($item);
            }
        }
        // 選択なしはエラー表示
        if (empty($selectedList)) {
            $validErrors['Inbound']['box'] = __d('validation', 'select', __d('validation', 'box'));
        }
        $data['box'] = implode(',', $selectedList);

        // 預け入れ方法入力チェック
        if (empty($data['delivery_carrier'])) {
            $validErrors['Inbound']['delivery_carrier'] = __d('validation', 'notBlank', __d('validation', 'inbound_delivery_carrier'));
        } else {

            $this->Inbound->init($data);
            $this->set('dateList', $this->Inbound->date());
            $this->set('timeList', $this->Inbound->time());

            // デフォルト値
            $data['address_id'] = '-10';

            // モデル取得
            $data = $this->Address->merge($data['address_id'], $data);

            // amazonpayデータ 入力データ取得
            $params = [
                'lastname'                  => filter_input(INPUT_POST, 'lastname'),
                'firstname'                 => filter_input(INPUT_POST, 'firstname'),
                'amazon_order_reference_id' => filter_input(INPUT_POST, 'amazon_order_reference_id'),
            ];

            // 住所情報等を取得
            $this->loadModel('AmazonPayModel');
            $set_param = array();
            $set_param['amazon_order_reference_id'] = $params['amazon_order_reference_id'];
            $set_param['address_consent_token'] = CakeSession::read(CustomerLogin::SESSION_AMAZON_PAY_ACCESS_KEY);
            $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

            $res = $this->AmazonPayModel->getOrderReferenceDetails($set_param);
            // GetOrderReferenceDetails
            if($res['ResponseStatus'] != '200') {
                $this->Flash->validation('Amazon Pay からの情報取得に失敗しました。再度お試し下さい。', ['key' => 'customer_amazon_pay_info']);
                return $this->redirect(['action' => 'add_amazon_pay']);
            }

            $physicaldestination = $res['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination']['PhysicalDestination'];
            $physicaldestination = $this->AmazonPayModel->wrapPhysicalDestination($physicaldestination);

            $data_amazon_pay = array();

            $PostalCode = $this->_editPostalFormat($physicaldestination['PostalCode']);
            $data_amazon_pay['postal']      = $PostalCode;
            $data_amazon_pay['pref']        = $physicaldestination['StateOrRegion'];

            $data_amazon_pay['address1'] = $physicaldestination['AddressLine1'];
            $data_amazon_pay['address2'] = $physicaldestination['AddressLine2'];
            $data_amazon_pay['address3'] = $physicaldestination['AddressLine3'];
            $data_amazon_pay['tel1']        = $physicaldestination['Phone'];

            // 名前上書き
            $data['lastname']   = $params['lastname'];
            $data['firstname']  = $params['firstname'];

            // カナ一時的に全角空白で対応
            $data['lastname_kana'] = '　';
            $data['firstname_kana'] = '　';

            $data = array_merge($data, $data_amazon_pay);

            // 一時セッション保持
            CakeSession::write('InboundAddress', $data);

            //バリデーション表示用
            $validation = AppValid::validate($data_amazon_pay);
            if (!empty($validation)) {
                $this->Flash->validation(AMAZON_PAY_ERROR_URGING_INPUT, ['key' => 'customer_amazon_pay_info']);
            }

            $model = $this->Inbound->model($data);
            if (empty($model)) {
                $this->Flash->set(__('empty_session_data'));
                return $this->redirect(['action' => 'add_amazon_pay']);
            }

            if ($model->validates()) {
                CakeSession::write(self::MODEL_NAME, $model->data);
                CakeSession::write(self::MODEL_NAME . 'FORM', $this->request->data);
            } else {
                $validErrors['Inbound'] = $model->validationErrors;
            }

            // 郵便番号チェック
            $this->loadModel('MtYmstpost');
            $res = $this->MtYmstpost->getPostal(['postal' => $data_amazon_pay['postal']]);

            if ($res->status == 0 || count($res->results) == 0) {
                CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));
                CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' postal ' . print_r($data_amazon_pay['postal'], true));
                $validErrors['Inbound']['postal'] = ['集荷依頼ができない郵便番号を入力されています。お問い合わせください。'];
            }
        }

        if (!empty($validErrors)) {
            $this->set('validErrors', $validErrors);
            return $this->render('add_amazon_pay');
        }
    }


    /**
     *
     */
    public function complete()
    {
        $data = CakeSession::read(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME . 'FORM');

        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }

        $post_data = Hash::get($this->request->data, self::MODEL_NAME);

        // cleaning_pack
        if (isset($post_data['keeping_type'])) {
            if (isset($data['InboundManual'])) {
                $model_name = "InboundManual";
            } else {
                if (isset($data['ReInboundYamato'])) {
                    $model_name = "ReInboundYamato";
                } else {
                    $model_name = "InboundYamato";
                }
            }
            $box_text = $data[$model_name]["box"];
            $replace_box_text = '';
            $arr_box_text = explode(',', $box_text);
            foreach ($arr_box_text as $var) {
                $arr_seperate_colon = explode(':', $var);
                if ($arr_seperate_colon[0] == PRODUCT_CD_CLEANING_PACK) {
                    $replace_box_text .= implode(':', $arr_seperate_colon) . ':' . $post_data['keeping_type'] . ',';
                } else {
                    $replace_box_text .= implode(':', $arr_seperate_colon) . ':,';
                }
            }
            $data[$model_name]["box"] = rtrim($replace_box_text, ',');
        }

        $data = current($data);
        $this->Inbound->init($data);
        $model = $this->Inbound->model($data);
        if (!empty($model) && $model->validates()) {
            // api
            $res = $model->apiPost($model->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'add']);
            }
        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }
    }

    /**
     *
     */
    public function complete_amazon_pay()
    {
        $data = CakeSession::read(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME);
        CakeSession::delete(self::MODEL_NAME . 'FORM');
        CakeSession::delete('InboundAddress');

        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add_amazon_pay']);
        }

        $post_data = Hash::get($this->request->data, self::MODEL_NAME);

        // cleaning_pack
        if (isset($post_data['keeping_type'])) {
            if (isset($data['InboundManual'])) {
                $model_name = "InboundManual";
            } else {
                if (isset($data['ReInboundYamato'])) {
                    $model_name = "ReInboundYamato";
                } else {
                    $model_name = "InboundYamato";
                }
            }
            $box_text = $data[$model_name]["box"];
            $replace_box_text = '';
            $arr_box_text = explode(',', $box_text);
            foreach ($arr_box_text as $var) {
                $arr_seperate_colon = explode(':', $var);
                if ($arr_seperate_colon[0] == PRODUCT_CD_CLEANING_PACK) {
                    $replace_box_text .= implode(':', $arr_seperate_colon) . ':' . $post_data['keeping_type'] . ',';
                } else {
                    $replace_box_text .= implode(':', $arr_seperate_colon) . ':,';
                }
            }
            $data[$model_name]["box"] = rtrim($replace_box_text, ',');
        }

        $data = current($data);

        $this->Inbound->init($data);
        $model = $this->Inbound->model($data);
        if (!empty($model) && $model->validates()) {
            // api
            $res = $model->apiPost($model->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->set($res->error_message);
                return $this->redirect(['action' => 'add']);
            }
        } else {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['action' => 'add']);
        }
    }

    /**
     *
     */
    public function as_getInboundDatetime()
    {
        $this->autoRender = false;
        if (!$this->request->is('ajax')) {
            return false;
        }

        $ret_status = true;
        $result = array();

        // 集荷日未ログイン取得 text項目がないため生成
        $result_date = $this->_getInboundDate();
        if ($result_date->status === "1") {
            $week = array("日", "月", "火", "水", "木", "金", "土");
            foreach($result_date->results as $key => $value) {
                $datetime = new DateTime($value['date_cd']);
                $w = (int)$datetime->format('w');

                $result_date->results[$key]['text'] = $datetime->format('Y年m月d日 (' . $week[$w] .')');;

            }
            $result['date'] = $result_date->results;

        } else {
            $ret_status = false;
        }

        // 集荷時間未ログイン取得 text項目がないため生成
        $result_time = $this->_getInboundTime();
        if ($result_time->status === "1") {

            $time_text = array('','希望なし','午前中','12～14時','14～16時','16～18時','18～21時');

            foreach ($result_time->results as $key => $value) {

                $result_time->results[$key]['text'] = $time_text[$value['time_cd']];
            }

            $result['time'] = $result_time->results;

        } else {
            $ret_status = false;
        }


        $status = $ret_status;
        return json_encode(compact('status', 'result'));
    }

    /**
     *
     */
    public function getAmazonUserInfoDetail()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        $this->autoRender = false;
        $amazon_order_reference_id = $this->request->data['amazon_order_reference_id'];

        $this->loadModel('AmazonPayModel');
        $set_param = array();
        $set_param['amazon_order_reference_id'] = $amazon_order_reference_id;
        $set_param['address_consent_token'] = $this->Customer->getAmazonPayAccessKey();
        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->getOrderReferenceDetails($set_param);
        // 住所に関する箇所を取得
        $physicaldestination = $res['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination']['PhysicalDestination'];

        $address = array();
        $address['name'] = $physicaldestination['Name'];

        $name = array();
        $name = $this->AmazonPayModel->devideUserName($address['name']);

        if (empty($name['lastname']) || empty($name['firstname']))
        {
            $status = false;
        } else {
            $status =  true;
        }

        return json_encode(compact('status', 'name'));
    }

    public function as_get_new_box()
    {
        $this->autoRender = false;
        $list = $this->InfoBox->getListForInbound();

        foreach ($list as &$data) {
            $data['free_limit_date'] = '';
            $current_time = time();
            $limit_time   = strtotime($this->Common->getServiceFreeLimit($data['order_date'], 'Y-m-d h:m:s'));
            $start_time   = strtotime(START_BOX_FREE);
            $order_time   = strtotime($data['order_date']);

            // 購入日とサービス開始日時
            if ($start_time > $order_time) {
               continue;
            }
            // 現在日時と無料期限
            if ($current_time > $limit_time) {
                continue;
            }

            $data['free_limit_date'] = $this->Common->getServiceFreeLimit($data['order_date'], 'Y/m/d');
        }

        return json_encode($list);
    }

    public function as_get_old_box()
    {
        $this->autoRender = false;
        $list = $this->InfoBox->getListForInboundOldBox();
        return json_encode($list);
    }

    /**
     * ヤマト運輸の配送日情報取得
     */
    private function _getInboundDate()
    {
        $result = array();

        $this->PickupDateModel = new PickupDate();

        $result = $this->PickupDateModel->getPickupDate();

        return $result;
    }

    /**
     * ヤマト運輸の配送時間情報取得
     */
    private function _getInboundTime()
    {
        $result = array();

        $PickupTimeModel = new PickupTime();

        $result = $PickupTimeModel->getPickupTime();

        return $result;
    }
}
