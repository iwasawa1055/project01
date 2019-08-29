<?php
App::uses('MinikuraController', 'Controller');
App::uses('PickupYamato', 'Model');
App::uses('CustomerAddress', 'Model');
App::uses('PickupYamatoDateTime', 'Model');
App::uses('MtYmstpost', 'Model');

class PickupController extends MinikuraController
{
    const MODEL_NAME_CUSTOMER_ADDRESS = 'CustomerAddress';
    const MODEL_NAME_PICKUP_YAMATO = 'PickupYamato';

    const TIME_ZONE_1 = 1;          // 08:00:00
    const TIME_ZONE_2 = 2;          // 14:00:00
    const TIME_ZONE_3 = 3;          // 22:00:00

    /* 集荷時間帯指定区分 */
    const PICKUP_TIME_CODE_1 = 1;   // 指定なし
    const PICKUP_TIME_CODE_2 = 2;   // 午前
    const PICKUP_TIME_CODE_4 = 4;   // 14時～16時
    const PICKUP_TIME_CODE_5 = 5;   // 16時～18時
    const PICKUP_TIME_CODE_6 = 6;   // 18時～21時

    /* 集荷の住所 お届先追加時の値 */
    const CUSTOMER_ADDRESS_ADD = -99;

    /* Web出荷CSデータ締めの時間 */
    public $time_slot = [
        self::TIME_ZONE_1 => '08:00:00',
        self::TIME_ZONE_2 => '14:00:00',
        self::TIME_ZONE_3 => '22:00:00',
    ];

    public $time_text = [
        self::PICKUP_TIME_CODE_1 => '指定なし',
        self::PICKUP_TIME_CODE_2 => '午前',
        self::PICKUP_TIME_CODE_4 => '14時～16時',
        self::PICKUP_TIME_CODE_5 => '16時～18時',
        self::PICKUP_TIME_CODE_6 => '18時～21時',
    ];

    // CustomerAddress List
    public $address_list = null;

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME_CUSTOMER_ADDRESS);
        $this->loadModel(self::MODEL_NAME_PICKUP_YAMATO);
        $this->set('action', $this->action);

        $this->address_list = $this->Address->get();
        $this->set('addressList', $this->Address->get());
    }

    /**
     * 集荷情報編集画面
     */
    public function edit()
    {
        // アマゾンペイメント対応
        if ($this->Customer->isAmazonPay()) {
            $this->redirect('/pickup/edit_amazon_pay/'.$this->request->params['id']);
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $isBack = Hash::get($this->request->query, 'back');

        // GET(集荷変更画面に遷移時)
        if (!$isBack && $this->request->is('get')) {
            // パラメータpickup_yamato_idがない場合はエラー
            if (isset($this->request->params['id']) && !$pickup_yamato_id = $this->request->params['id']) {
                new AppTerminalError(AppE::NOT_FOUND . 'pickup_yamato_id', 404);
            }
            $data['pickup_yamato_id'] = $pickup_yamato_id;
            CakeSession::write(self::MODEL_NAME_PICKUP_YAMATO, $data);
        }

        // POST(確認ボタン押下時)
        if ($this->request->is('post')) {
            // CustomerAddress情報
            $customer_address = Hash::get($this->request->data, self::MODEL_NAME_CUSTOMER_ADDRESS);
            $this->CustomerAddress->set($this->request->data);
            CakeSession::write(self::MODEL_NAME_CUSTOMER_ADDRESS, $customer_address);

            // PickupYamato情報
            $pickup_yamato = Hash::get($this->request->data, self::MODEL_NAME_PICKUP_YAMATO);
            $this->PickupYamato->set($this->request->data);
            $data = CakeSession::read(self::MODEL_NAME_PICKUP_YAMATO);
            $pickup_yamato['pickup_yamato_id'] = $data['pickup_yamato_id'];
            CakeSession::write(self::MODEL_NAME_PICKUP_YAMATO, $pickup_yamato);

            // お届け先追加選択時
            //if ((int)$customer_address['address_id'] === self::CUSTOMER_ADDRESS_ADD) {
            if ((int)$pickup_yamato['address_id'] === self::CUSTOMER_ADDRESS_ADD) {
                if (!$this->CustomerAddress->validates()) {
                    return $this->render('edit');
                }
            } else {
                $this->PickupYamato->validate['address_id'] = [
                    'notBlank' => [
                        'rule' => 'notBlank',
                        'required' => true,
                        'message' => ['notBlank', 'address_id'],
                ]];
                if (!$this->PickupYamato->validates()) {
                    return $this->render('edit');
                }
            }

            // 郵便番号チェックを行う
            $pickup_detail = $this->_getPickupDetail();
            $postal = $pickup_detail['postal'];
            $this->loadModel('MtYmstpost');
            $res = $this->MtYmstpost->getPostal(['postal' => $postal]);

            if ($res->status == 0 || count($res->results) == 0) {
                $this->Flash->set(__('集荷依頼ができない郵便番号を入力されています。お問い合わせください。'));
                CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));
                CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' postal ' . print_r($postal, true));
                return $this->render('edit');
            }

            // 日付のチェックを行う
            if (!$this->_checkPickupYamatoDateTime($pickup_yamato['pickup_date'], $pickup_yamato['pickup_time'])) {
                $this->Flash->set(__('選択した集荷日又は集荷時間は締め切られました。集荷日、集荷時間を選択し直してください。'));
                // redirect
                return $this->render('edit');
            }

            $this->redirect('/pickup/confirm');
        }

        // 戻るボタン押下時
        if ($isBack) {
            // session情報が取得できなかった時
            if (!$pickup_detail = $this->_getPickupDetail()) {
               $this->redirect('/announcement');
            }
            // お届先追加時
            if ((int)$pickup_detail['address_id'] === self::CUSTOMER_ADDRESS_ADD) {
                $this->request->data[self::MODEL_NAME_CUSTOMER_ADDRESS] = [
                    'address_id' => $pickup_detail['address_id'], 
                    'postal' => $pickup_detail['postal'], 
                    'pref' => $pickup_detail['pref'], 
                    'address1' => $pickup_detail['address1'], 
                    'address2' => $pickup_detail['address2'], 
                    'address3' => $pickup_detail['address3'], 
                    'tel1' => $pickup_detail['tel1'], 
                    'lastname' => $pickup_detail['lastname'], 
                    'lastname_kana' => $pickup_detail['lastname_kana'], 
                    'firstname' => $pickup_detail['firstname'], 
                    'firstname_kana' => $pickup_detail['firstname_kana'], 
                ];
            }

            $this->request->data[self::MODEL_NAME_PICKUP_YAMATO] = [
                'address_id' => $pickup_detail['address_id'], 
                'hidden_pickup_date' => $pickup_detail['pickup_date'], 
                'hidden_pickup_time_code' => $pickup_detail['pickup_time'], 
            ];

            // 編集画面に戻る
            return $this->render('edit');
        }

        // PickupYamato情報取得
        $pickup_yamato = new PickupYamato();
        $res = $pickup_yamato->apiGet([
            'pickup_yamato_id' => $pickup_yamato_id,
        ]);

        if (count($res->results) == 0) {
            new AppTerminalError(AppE::NOT_FOUND . 'pickup_yamato_id', 404);
        }

        // 現在登録している住所をセット
        $results = $res->results[0];
        $address = str_replace('-', '', $results['pickup_yamato_postcode']);
        $address .= $results['pickup_yamato_address1'];
        $address .= $results['pickup_yamato_address2'];

        $address_id = null;
        // selectboxにセットされている住所と登録住所を比較する
        foreach($this->address_list as $key => $val) {
            $compare_address = null;
            $compare_address = str_replace('-', '', $val['postal']);
            $compare_address .= $val['pref'];
            $compare_address .= $val['address1'];
            $compare_address .= $val['address2'];
            $compare_address .= $val['address3'];
            // 一致した場合はselectboxで一致した住所を選択された状態にする
            if ($address === $compare_address) {
                $address_id = $val['address_id'];
                break;
            }
         }

        $this->request->data[self::MODEL_NAME_PICKUP_YAMATO] = [
            'address_id' => $address_id, 
            'hidden_pickup_date' => $results['pickup_date'],
            'hidden_pickup_time_code' => $results['pickup_time_code'], 
        ];

    }


    /**
     * 集荷情報確認画面
     */
    public function confirm()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['Pickup/edit', 'Pickup/confirm', 'Pickup/complete'],
                true) === false
        ) {
            //* NG redirect
            $this->redirect(['controller' => 'announcement', 'action' => 'index']);
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        // 確認画面表示用
        if (!$pickup_confirm = $this->_getPickupDetail()) {
            $this->redirect('/announcement');
        }

        $this->set('pickup_confirm', $pickup_confirm);
    }

    /**
     * 集荷情報完了画面
     */
    public function complete()
    {
        $customer_address_data = CakeSession::read(self::MODEL_NAME_CUSTOMER_ADDRESS);
        $pickup_data = CakeSession::read(self::MODEL_NAME_PICKUP_YAMATO);

        // 日付のチェックを行う
        if (!$this->_checkPickupYamatoDateTime($pickup_data['pickup_date'], $pickup_data['pickup_time'])) {
            $this->Flash->set(__('選択した集荷日又は集荷時間は締め切られました。集荷日、集荷時間を選択し直してください。'));
            // redirect
            return $this->render('edit');
        }

        // お届先追加時
        if ((int)$pickup_data['address_id'] === self::CUSTOMER_ADDRESS_ADD) {
            $res = $this->CustomerAddress->apiPost($customer_address_data);
            if (!$res->isSuccess()) {
                $this->Flash->set(__('お届先の登録が出来ませんでした。再度登録をしてください。'));
                // redirect
                return $this->render('edit');
            }
        }

        // 集荷情報
        if (!$pickup_detail = $this->_getPickupDetail()) {
            $this->redirect('/announcement');
        }

        // PickupYamato
        $pickup_yamato = new PickupYamato();
        // 更新のパラメータ
        $res = $pickup_yamato->apiPatch([
            'pickup_yamato_id' => $pickup_data['pickup_yamato_id'],
            'pickup_yamato_name' => $pickup_detail['name'],
            'pickup_yamato_postcode' => str_replace('-', '', $pickup_detail['postal']),
            'pickup_yamato_address1' => $pickup_detail['pref'].$pickup_detail['address1'],
            'pickup_yamato_address2' => $pickup_detail['address2'].$pickup_detail['address3'],
            'pickup_yamato_telephone' => $pickup_detail['tel1'],
            'pickup_date' => str_replace('/', '-', $pickup_detail['pickup_date']),
            'pickup_time_code' => $pickup_detail['pickup_time'],
        ]);

        // 更新成功
        if ($res->isSuccess()) {
            return $this->render('complete');
        } else {
            $this->Flash->set(__('集荷変更処理が失敗しました。再度登録をしてください。'));
            // redirect
            return $this->render('edit');
        }
    }

    /**
     * 集荷情報編集画面(Amazon Pay)
     */
    public function edit_amazon_pay()
    {
        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $isBack = Hash::get($this->request->query, 'back');

        // GET(集荷変更画面に遷移時)
        if (!$isBack && $this->request->is('get')) {
            // パラメータpickup_yamato_idがない場合はエラー
            if (!$pickup_yamato_id = $this->request->params['id']) {
                new AppTerminalError(AppE::NOT_FOUND . 'pickup_yamato_id', 404);
            }

            // PickupYamato情報取得
            $pickup_yamato = new PickupYamato();
            $res = $pickup_yamato->apiGet([
                'pickup_yamato_id' => $pickup_yamato_id,
            ]);

            $pickup_detail['pickup_yamato_id'] = $pickup_yamato_id;
            $pickup_detail['pickup_date'] = $res->results[0]['pickup_date'];
            $pickup_detail['pickup_time'] = $res->results[0]['pickup_time_code'];
            CakeSession::write(self::MODEL_NAME_PICKUP_YAMATO, $pickup_detail);
        }

        // POST(確認ボタン押下時)
        if ($this->request->is('post')) {
            $data = CakeSession::read(self::MODEL_NAME_PICKUP_YAMATO);
            $pickup_yamato = Hash::get($this->request->data, self::MODEL_NAME_PICKUP_YAMATO);
            $this->PickupYamato->set($this->request->data);

            if (!$this->PickupYamato->validates()) {
                return $this->render('edit_amazon_pay');
            }

            $params = [
                'pickup_yamato_id'          => $data['pickup_yamato_id'],
                'pickup_date'               => $pickup_yamato['pickup_date'],
                'pickup_time'               => $pickup_yamato['pickup_time'],
                'lastname'                  => filter_input(INPUT_POST, 'lastname'),
                'firstname'                 => filter_input(INPUT_POST, 'firstname'),
                'amazon_order_reference_id' => filter_input(INPUT_POST, 'amazon_order_reference_id'),
            ];

            CakeSession::delete(self::MODEL_NAME_PICKUP_YAMATO);
            CakeSession::write(self::MODEL_NAME_PICKUP_YAMATO, $params);

            // 日付のチェックを行う
            if (!$this->_checkPickupYamatoDateTime($pickup_yamato['pickup_date'], $pickup_yamato['pickup_time'])) {
                $this->Flash->set(__('選択した集荷日又は集荷時間は締め切られました。集荷日、集荷時間を選択し直してください。'));
                // redirect
                return $this->render('edit_amazon_pay');
            }

            // 郵便番号チェックを行う
            $this->loadModel('AmazonPayModel');
            $set_param = array();
            $set_param['amazon_order_reference_id'] = $params['amazon_order_reference_id'];
            $set_param['address_consent_token'] = CakeSession::read(CustomerLogin::SESSION_AMAZON_PAY_ACCESS_KEY);
            $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');
            $res = $this->AmazonPayModel->getOrderReferenceDetails($set_param);
            $physicaldestination = $res['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination']['PhysicalDestination'];
            $physicaldestination = $this->AmazonPayModel->wrapPhysicalDestination($physicaldestination);
            $postal = $this->_editPostalFormat($physicaldestination['PostalCode']);
            $this->loadModel('MtYmstpost');
            $res = $this->MtYmstpost->getPostal(['postal' => $postal]);

            if ($res->status == 0 || count($res->results) == 0) {
                CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));
                CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' postal ' . print_r($postal, true));
                CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' set_param ' . print_r($set_param, true));
                $this->Flash->set(__('集荷依頼ができない郵便番号を入力されています。お問い合わせください。'));
                return $this->render('edit');
            }

            $this->redirect('/pickup/confirm_amazon_pay');
        }

        // 戻るボタン押下時
        if ($isBack) {
            // session情報が取得できなかった時
            if (!$pickup_detail = CakeSession::read(self::MODEL_NAME_PICKUP_YAMATO)) {
               $this->redirect('/announcement');
            }

            $this->request->data[self::MODEL_NAME_PICKUP_YAMATO] = [
                'hidden_pickup_date' => $pickup_detail['pickup_date'], 
                'hidden_pickup_time_code' => $pickup_detail['pickup_time'], 
            ];

            // 編集画面に戻る
            return $this->render('edit_amazon_pay');
        }

        $this->request->data[self::MODEL_NAME_PICKUP_YAMATO] = [
            'hidden_pickup_date' => $pickup_detail['pickup_date'], 
            'hidden_pickup_time_code' => $pickup_detail['pickup_time'], 
        ];
    }

    /**
     * 集荷情報確認画面(Amazon Pay)
     */
    public function confirm_amazon_pay()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['Pickup/edit_amazon_pay', 'Pickup/confirm_amazon_pay', 'Pickup/complete_amazon_pay'],
                true) === false
        ) {
            //* NG redirect
            $this->redirect(['controller' => 'announcement', 'action' => 'index']);
        }

        //* session referer set
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        $params = CakeSession::read(self::MODEL_NAME_PICKUP_YAMATO);

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
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));
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
        $data_amazon_pay['name']   = $params['lastname'].$params['firstname'];
        $data_amazon_pay['name_kana']  = ' ';
        $data_amazon_pay['lastname']   = $params['lastname'];
        $data_amazon_pay['firstname']  = $params['firstname'];
        $data_amazon_pay['lastname_kana']   = ' ';
        $data_amazon_pay['firstname_kana']  = ' ';
        $data_amazon_pay['pickup_yamato_id'] = $params['pickup_yamato_id'];
        $data_amazon_pay['pickup_date']   = $params['pickup_date'];
        $data_amazon_pay['pickup_time']   = $params['pickup_time'];
        $data_amazon_pay['pickup_date_text']   = str_replace('-', '/', $data_amazon_pay['pickup_date'].$this->_getWeek($data_amazon_pay['pickup_date']));
        $data_amazon_pay['pickup_time_text']   = $this->time_text[$data_amazon_pay['pickup_time']];

        CakeSession::delete(self::MODEL_NAME_PICKUP_YAMATO);
        CakeSession::write(self::MODEL_NAME_PICKUP_YAMATO, $data_amazon_pay);

        $this->set('pickup_confirm', $data_amazon_pay);
    }


    /**
     * 集荷情報完了画面(Amazon pay)
     */
    public function complete_amazon_pay()
    {
        $pickup_data = CakeSession::read(self::MODEL_NAME_PICKUP_YAMATO);

        // 日付のチェックを行う
        if (!$this->_checkPickupYamatoDateTime($pickup_data['pickup_date'], $pickup_data['pickup_time'])) {
            $this->Flash->set(__('選択した集荷日又は集荷時間は締め切られました。集荷日、集荷時間を選択し直してください。'));
            // redirect
            return $this->render('edit_amazon_pay');
        }

        // PickupYamato
        $pickup_yamato = new PickupYamato();
        // 更新のパラメータ
        $res = $pickup_yamato->apiPatch([
            'pickup_yamato_id' => $pickup_data['pickup_yamato_id'],
            'pickup_yamato_name' => $pickup_data['name'],
            'pickup_yamato_postcode' => str_replace('-', '', $pickup_data['postal']),
            'pickup_yamato_address1' => $pickup_data['pref'].$pickup_data['address1'],
            'pickup_yamato_address2' => $pickup_data['address2'].$pickup_data['address3'],
            'pickup_yamato_telephone' => $pickup_data['tel1'],
            'pickup_date' => $pickup_data['pickup_date'],
            'pickup_time_code' => $pickup_data['pickup_time'],
        ]);

        // 更新成功
        if ($res->isSuccess()) {
            return $this->render('complete');
        } else {
            $this->Flash->set(__('集荷変更処理が失敗しました。再度登録をしてください。'));
            // redirect
            return $this->render('edit_amazon_pay');
        }
    }

    /**
     * Sessionから画面表示用に配列に代入
     */
    private function _getPickupDetail()
    {
        $pickup_yamato = CakeSession::read(self::MODEL_NAME_PICKUP_YAMATO);
        $customer_address = CakeSession::read(self::MODEL_NAME_CUSTOMER_ADDRESS);

        if (!$pickup_yamato && !customer_address) {
            return false;
        }

        // 集荷の住所 お届先追加選択時
        //if ((int)$customer_address['address_id'] === self::CUSTOMER_ADDRESS_ADD) {
        if ((int)$pickup_yamato['address_id'] === self::CUSTOMER_ADDRESS_ADD) {
            $pickup['postal'] = $customer_address['postal'];
            $pickup['pref'] = $customer_address['pref'];
            $pickup['address1'] = $customer_address['address1'];
            $pickup['address2'] = $customer_address['address2'];
            $pickup['address3'] = $customer_address['address3'];
            $pickup['tel1'] = $customer_address['tel1'];
            $pickup['name'] = $customer_address['lastname'].$customer_address['firstname'];
            $pickup['name_kana'] = $customer_address['lastname_kana'].$customer_address['firstname_kana'];
            $pickup['lastname'] = $customer_address['lastname'];
            $pickup['lastname_kana'] = $customer_address['lastname_kana'];
            $pickup['firstname'] = $customer_address['firstname'];
            $pickup['firstname_kana'] = $customer_address['firstname_kana'];
        // 既存住所を選択時
        } else {
            $keys = array_keys(array_column($this->address_list, 'address_id'), $pickup_yamato['address_id']);
            // 住所リストから選択した住所を取得する
            $select_address = $this->address_list[$keys[0]];
            $pickup['postal'] = $select_address['postal'];
            $pickup['pref'] = $select_address['pref'];
            $pickup['address1'] = $select_address['address1'];
            $pickup['address2'] = $select_address['address2'];
            $pickup['address3'] = $select_address['address3'];
            $pickup['tel1'] = $select_address['tel1'];
            $pickup['name'] = $select_address['lastname'].$select_address['firstname'];
            $pickup['name_kana'] = $select_address['lastname_kana'].$select_address['firstname_kana'];
            $pickup['lastname'] = $select_address['lastname'];
            $pickup['lastname_kana'] = $select_address['lastname_kana'];
            $pickup['firstname'] = $select_address['firstname'];
            $pickup['firstname_kana'] = $select_address['firstname_kana'];
        }
        $pickup['address_id'] = $pickup_yamato['address_id'];
        $pickup['pickup_yamato_id'] = $pickup_yamato['pickup_yamato_id'];
        $pickup['pickup_date'] = $pickup_yamato['pickup_date'];
        $pickup['pickup_time'] = $pickup_yamato['pickup_time'];
        // 出荷日+曜日
        $pickup['pickup_date_text'] = str_replace('-', '/', $pickup_yamato['pickup_date'].$this->_getWeek($pickup_yamato['pickup_date']));
        // pickup_time_codeから表示用の文字列
        $pickup['pickup_time_text'] = $this->time_text[$pickup_yamato['pickup_time']];

        return $pickup;
    }

    /**
     * 日付から曜日を返却
     */
    public function _getWeek($date)
    {
        $datetime = new DateTime($date);
        $week = ['(日)', '(月)', '(火)', '(水)', '(木)', '(金)', '(土)'];
        $w = (int)$datetime->format('w');
        return $week[$w];
    }

    /**
     * 集荷日時間チェック
     */
    private function _checkPickupYamatoDateTime($pickup_date, $pickup_time_code)
    {
        // 集荷日の確認
        $pickup_yamato_datetime = new PickupYamatoDateTime();
        $datetime = $pickup_yamato_datetime->getPickupYamatoDateTime();

        if (!array_key_exists($pickup_date, $datetime->results)) {
            return false;
        }
        if (!array_key_exists($pickup_time_code, $datetime->results[$pickup_date])) {
            return false;
        }

        return true;
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

    public function getTimeText()
    {
        return $time_text = [
            self::PICKUP_TIME_CODE_1 => '指定なし',
            self::PICKUP_TIME_CODE_2 => '午前',
            self::PICKUP_TIME_CODE_4 => '14時～16時',
            self::PICKUP_TIME_CODE_5 => '16時～18時',
            self::PICKUP_TIME_CODE_6 => '18時～21時',
        ];
    }
}
