<?php

App::uses('AppValid', 'Lib');
App::uses('MinikuraController', 'Controller');
App::uses('DatePrivate', 'Model');
App::uses('TimePrivate', 'Model');
App::uses('InboundSelectedBox', 'Model');
App::uses('AmazonPayModel', 'Model');

class InboundBoxController extends MinikuraController
{
    const MODEL_NAME = 'Inbound';

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

        // 届け先追加を選択の場合は追加画面へ遷移
        if (Hash::get($data, 'address_id') === AddressComponent::CREATE_NEW_ADDRESS_ID) {
            CakeSession::write(self::MODEL_NAME . 'FORM', $this->request->data);
            return $this->redirect([
                'controller' => 'address', 'action' => 'add', 'customer' => true,
                '?' => ['return' => 'inboundbox']
            ]);
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

            // モデル取得
            $data = $this->Address->merge($data['address_id'], $data);
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

        if (!empty($validErrors)) {
            $this->set('validErrors', $validErrors);
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
                // ↓AmazonPayのエラーがどのような頻度で起きるか様子見するためのログ。消さないでー！
                CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));
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

        $amazon_pay_data = $this->request->data['amazon_pay_data'];

        $amazon_order_reference_id = $amazon_pay_data['amazon_order_reference_id'];

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

        $status = !empty($address['name']);

        return json_encode(compact('status', 'address'));
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

    /**
     * Direct Inbound set
     */
    private function _setDirectInbound($Order)
    {
        $Order['direct_inbound']['direct_inbound'] = (int)filter_input(INPUT_POST, 'direct_inbound');
        return $Order;
    }

    // 日付CD変換
    private function _convDatetimeCode ( $data_code ){

        // 時間CODE変換表
        $timeList = array( 2 => '午前中',
            //3 => '12～14時',
            4 => '14～16時',
            5 => '16～18時',
            6 => '18～20時',
            7 => '19～21時' );


        // 日付
        $date = substr( $data_code, 0, 10 );

        // 時間
        $time = substr( $data_code, 11, 1 );

        // 戻り値
        $datetime = date( "Y年m月d日", strtotime( $date ) );

        if( isset( $timeList[$time] )  ) $datetime .= ' '.$timeList[$time];
        return $datetime;
    }

}
