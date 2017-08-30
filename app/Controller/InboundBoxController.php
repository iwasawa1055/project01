<?php

App::uses('MinikuraController', 'Controller');
App::uses('DatePrivate', 'Model');
App::uses('TimePrivate', 'Model');
App::uses('InboundSelectedBox', 'Model');
App::uses('AmazonPayModel', 'Model');
App::uses('PaymentAmazonPay', 'Model');
App::uses('PaymentAmazonKitAmazonPay', 'Model');

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

        $amazon_pay_user_info = CakeSession::read('login.amazon_pay.user_info');

        // 住所に関する情報保存
        $name = $amazon_pay_user_info['name'];
        $name = html_entity_decode($name);
        $name = mb_convert_kana($name, "s", "utf-8");

        // 空白で苗字名前がわかれているか？
        $set_name = array();
        if(strpos($name,' ') !== false){
            $set_name = explode(" ",$name);
        } else {
            // スペースで区切られていない
            $set_name[0] = $name;
            $set_name[1] = '＿';
        }

        $get_address = array();

        $get_address['lastname']        = $set_name[0];
        $get_address['lastname_kana']   = '　';
        $get_address['firstname']       = $set_name[1];
        $get_address['firstname_kana']  = '　';

        // amazon pay 情報取得
        // 定期購入ID取得
        $amazon_billing_agreement_id = "";
        $amazon_billing_agreement_id = filter_input(INPUT_POST, 'amazon_billing_agreement_id');

        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' !!!amazon_billing_agreement_id!!! ' . $amazon_billing_agreement_id);

        if($amazon_billing_agreement_id === null) {
            // 初回かリターン確認
            if(CakeSession::read('Order.amazon_pay.amazon_billing_agreement_id') != null) {
                CakeSession::write('Order.amazon_pay.amazon_billing_agreement_id', $amazon_billing_agreement_id);
            }
        }

        // 住所情報等を取得
        $this->loadModel('AmazonPayModel');
        $set_param = array();
        $set_param['amazon_billing_agreement_id'] = $amazon_billing_agreement_id;
        $set_param['address_consent_token'] = $this->Customer->getAmazonPayAccessKey();
        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->getBillingAgreementDetails($set_param);

        // GetBillingAgreementDetails
        if($res['ResponseStatus'] != '200') {

        }

        // 有効な定期購入IDを設定
        CakeSession::write('Order.amazon_pay.amazon_billing_agreement_id', $amazon_billing_agreement_id);

        if(!isset($res['GetBillingAgreementDetailsResult']['BillingAgreementDetails']['Destination']['PhysicalDestination'])) {

        }

        // 住所に関する箇所を取得
        $physicaldestination = $res['GetBillingAgreementDetailsResult']['BillingAgreementDetails']['Destination']['PhysicalDestination'];

        //$get_address = CakeSession::read('Address');

        // 住所情報セット
        $PostalCode = $this->_editPostalFormat($physicaldestination['PostalCode']);
        $get_address['postal']      = $PostalCode;
        $get_address['pref']        = $physicaldestination['StateOrRegion'];

        // city設定有無確認
        if(isset($physicaldestination['City'])) {
            $get_address['address1'] = $physicaldestination['City'];
            $get_address['address2'] = $physicaldestination['AddressLine1'];
            $get_address['address3'] = $physicaldestination['AddressLine2'];
        } else {
            $get_address['address1'] = $physicaldestination['AddressLine1'];
            $get_address['address2'] = $physicaldestination['AddressLine2'];
        }
        $get_address['tel1']        = $physicaldestination['Phone'];
        //$data['datetime_cd'] = $params['datetime_cd'];
        //$data['select_delivery_text'] = $this->_convDatetimeCode($params['datetime_cd']);


        CakeSession::write('InboundAddress', $get_address);

        // 預け入れ方法入力チェック
        if (empty($data['delivery_carrier'])) {
            $validErrors['Inbound']['delivery_carrier'] = __d('validation', 'notBlank', __d('validation', 'inbound_delivery_carrier'));
        } else {

            $this->Inbound->init($data);
            $this->set('dateList', $this->Inbound->date());
            $this->set('timeList', $this->Inbound->time());


            // モデル取得
            //暫定バリデーションNG回避対応
            $data['address_id'] = AddressComponent::CREATE_NEW_ADDRESS_ID;

            $data = array_merge_recursive($data, $get_address);

            $model = $this->Inbound->model($data);
            if (empty($model)) {
                $this->Flash->set(__('empty_session_data'));
                //return $this->redirect(['action' => 'add']);
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
