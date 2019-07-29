<?php

App::uses('MinikuraController', 'Controller');

App::uses('UnusedGiftInfo', 'Model');
App::uses('ReceiveGiftByCreditCard', 'Model');
App::uses('ReceiveGiftByAmazonPay', 'Model');
App::uses('AmazonPayModel', 'Model');

class ReceiveController extends MinikuraController
{
    const MODEL_NAME_RECEIVE_GIFT_BY_CARD   = 'ReceiveGiftByCreditCard';
    const MODEL_NAME_RECEIVE_GIFT_BY_AMAZON = 'ReceiveGiftByAmazonPay';
    const MODEL_NAME_CHECK_GIFT             = 'UnusedGiftInfo';

    /** layout */
    public $layout = 'order';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    /**
     * アクセス拒否
     */
    protected function isAccessDeny()
    {
        if (!$this->Customer->canOrderKit() && ($this->action === 'complete_card' || $this->action === 'complete_bank')) {
            return true;
        }
        return false;
    }

    /**
     * 入力フォーム選択
     */
    public function gift_add()
    {
        // session delete
        $allow_action_list = [
            'Receive/gift_add',
            'Receive/gift_input_card',
            'Receive/gift_confirm_card',
            'Receive/gift_input_amazon_pay',
            'Receive/gift_confirm_amazon_pay',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->_cleanKitOrderSession();
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // ギフトコード
        $gift_cd = '';
        if (isset($_GET['gift_cd'])) {
            $gift_cd = $_GET['gift_cd'];
        } elseif (CakeSession::read('app.data.gift_cd')) {
            $gift_cd = CakeSession::read('app.data.gift_cd');
        }
        CakeSession::Write('app.data.gift_cd', $gift_cd);

        // entry user
        if ($this->Customer->isEntry()) {
            return $this->redirect(['controller' => 'customer/register', 'action' => 'add_personal']);
        }

        // corporate user
        if (!$this->Customer->isPrivateCustomer()) {
            return $this->redirect('/');
        }

        // amazon payment user
        if ($this->Customer->isAmazonPay()) {
            // gift_cd
            $data = CakeSession::read(self::MODEL_NAME_RECEIVE_GIFT_BY_AMAZON);
            $data['gift_cd'] = CakeSession::read('app.data.gift_cd');
            CakeSession::write(self::MODEL_NAME_RECEIVE_GIFT_BY_AMAZON, $data);

            CakeSession::write('order_type', 'amazon');
            $this->redirect('/gift/receive/input_amazon_pay');
        }

        // gift_cd
        $data = CakeSession::read(self::MODEL_NAME_RECEIVE_GIFT_BY_CARD);
        $data['gift_cd'] = CakeSession::read('app.data.gift_cd');
        CakeSession::write(self::MODEL_NAME_RECEIVE_GIFT_BY_CARD, $data);

        // credit card user
        CakeSession::write('order_type', 'card');
        $this->redirect('/gift/receive/input_card');
    }

    /**
     * ギフト受け取り入力フォーム
     */
    public function gift_input_card()
    {
        // session delete
        $allow_action_list = [
            'Receive/gift_add',
            'Receive/gift_input_card',
            'Receive/gift_confirm_card',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'receive', 'action' => 'gift_add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_RECEIVE_GIFT_BY_CARD);

        if ($this->request->is('get')) {

            $this->request->data[self::MODEL_NAME_RECEIVE_GIFT_BY_CARD] = CakeSession::read(self::MODEL_NAME_RECEIVE_GIFT_BY_CARD);

            // 住所一覧
            $address_list = $this->Address->get();
            $set_address_list = array();
            if (is_array($address_list)) {
                foreach ($address_list as $address) {
                    $set_address_list[$address['address_id']]  = h("〒{$address['postal']}");
                    $set_address_list[$address['address_id']] .= h(" {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}");
                    $set_address_list[$address['address_id']] .= h("　{$address['lastname']}　{$address['firstname']}");
                }
                // 新規住所追加用
                $set_address_list['add']  = 'お届先を追加する';
            }

            CakeSession::write('address_list', $set_address_list);

            $this->set('address_list', CakeSession::read('address_list'));

            $this->set(self::MODEL_NAME_RECEIVE_GIFT_BY_CARD, $this->request->data);

        } elseif ($this->request->is('post')) {

            $data = $this->request->data[self::MODEL_NAME_RECEIVE_GIFT_BY_CARD];

            /** セッションデータ */
            CakeSession::write(self::MODEL_NAME_RECEIVE_GIFT_BY_CARD, $data);

            $this->ReceiveGiftByCreditCard->set($data);

            /** 受け取り情報バリデーション */
            $error_flag = false;

            $validation_item[] = 'gift_cd';
            $validation_item[] = 'address_id';
            // お届け先入力時
            if ($data['address_id'] == 'add') {
                $validation_item[] = 'lastname';
                $validation_item[] = 'firstname';
                $validation_item[] = 'tel1';
                $validation_item[] = 'postal';
                $validation_item[] = 'pref';
                $validation_item[] = 'address1';
                $validation_item[] = 'address2';
                $validation_item[] = 'address3';
            }
            if (!$this->ReceiveGiftByCreditCard->validates(['fieldList' => $validation_item])) {
                $error_flag = true;
            }

           if (!$error_flag) {
               // integrity check gift code
               $this->loadModel(self::MODEL_NAME_CHECK_GIFT);
               $result_gift_data = $this->UnusedGiftInfo->apiGet(['gift_cd' => $data['gift_cd']]);
               $data['kit_list'] = [];
               if ($result_gift_data->isSuccess()) {
                   $result_gift_data = json_decode(json_encode($result_gift_data), true);
                   if (empty($result_gift_data['results'])) {
                       $error_flag = true;
                       $this->ReceiveGiftByCreditCard->validationErrors['gift_cd'][0] = '該当するギフトコードが存在しません';
                   } else {
                       foreach ($result_gift_data['results'] as $gift_data) {
                           $data['kit_list'][] = [
                               'kit_cd'   => $gift_data['kit_cd'],
                               'kit_cnt'  => $gift_data['count'],
                           ];
                       }
                   }
               }
           }

            if ($error_flag) {
                $this->set('address_list', CakeSession::read('address_list'));
                return $this->render('gift_input_card');
            }

            CakeSession::write(self::MODEL_NAME_RECEIVE_GIFT_BY_CARD, $data);

            return $this->redirect(['controller' => 'receive', 'action' => 'confirm_card']);

        }

    }

    /**
     * ギフト受け取り確認フォーム
     */
    public function gift_confirm_card()
    {
        // check access source actions
        $allow_action_list = [
            'Receive/gift_input_card',
            'Receive/gift_confirm_card',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'order', 'action' => 'gift_add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $data = CakeSession::read(self::MODEL_NAME_RECEIVE_GIFT_BY_CARD);

        // 既存アドレス使用時
        if ($data['address_id'] !== 'add') {
            $address_list = $this->Address->get();
            $target_index = array_search($data['address_id'], array_column($address_list, 'address_id'));
            $address_data = $address_list[$target_index];
            $data['name']    = $address_data['lastname'] . '　' . $address_data['firstname'];
            $data['address'] = $address_data['pref'] . $address_data['address1'] . $address_data['address2'] . $address_data['address3'];
            $data['postal'] = $address_data['postal'];
            $data['tel1']   = $address_data['tel1'];
            $data = array_merge($data, $address_data);
        } else {
            $data['name']    = $data['lastname'] . '　' . $data['firstname'];
            $data['address'] = $data['pref'] . $data['address1'] . $data['address2'] . $data['address3'];
        }

        CakeSession::write(self::MODEL_NAME_RECEIVE_GIFT_BY_CARD, $data);

        $this->set(self::MODEL_NAME_RECEIVE_GIFT_BY_CARD, $data);
    }

    /**
     * ギフト受け取り完了フォーム
     */
    public function gift_complete_card()
    {
        // check access source actions
        $allow_action_list = [
            'Receive/gift_confirm_card',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'receive', 'action' => 'gift_add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_RECEIVE_GIFT_BY_CARD);

        $data = CakeSession::read(self::MODEL_NAME_RECEIVE_GIFT_BY_CARD);

        /** ギフトを受け取り */
        $tmp_kit = [];
        foreach ($data['kit_list'] as $kit_data) {
            $tmp_kit[] = $kit_data['kit_cd'] . ':' . $kit_data['kit_cnt'];
        }
        $kit = implode(",", $tmp_kit);
        $api_parm = [
            'gift_cd'       => $data['gift_cd'],
            'kit'           => $kit,
            'delivery_name' => $data['name'],
            'tel1'          => $data['tel1'],
            'postal'        => $data['postal'],
            'pref'          => $data['pref'],
            'address1'      => $data['address1'],
            'address2'      => $data['address2'],
            'address3'      => $data['address3'],
        ];
        $result_gift_data = $this->ReceiveGiftByCreditCard->apiPost($api_parm);
        if (!$result_gift_data->isSuccess()) {
            $this->ReceiveGiftByCreditCard->validationErrors['gift_cd'][0] = '該当するギフトコードが存在しません';
            $this->set('address_list', CakeSession::read('address_list'));
            return $this->render('gift_input_card');
        }

        $this->set(self::MODEL_NAME_RECEIVE_GIFT_BY_CARD, $data);

        $this->_cleanKitOrderSession();
    }

    /**
     * ギフト受け取り入力フォーム
     */
    public function gift_input_amazon_pay()
    {
        // session delete
        $allow_action_list = [
            'Receive/gift_add',
            'Receive/gift_input_amazon_pay',
            'Receive/gift_confirm_amazon_pay',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'receive', 'action' => 'gift_add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_RECEIVE_GIFT_BY_AMAZON);

        if ($this->request->is('get')) {

            $this->request->data[self::MODEL_NAME_RECEIVE_GIFT_BY_AMAZON] = CakeSession::read(self::MODEL_NAME_RECEIVE_GIFT_BY_AMAZON);

            $this->set(self::MODEL_NAME_RECEIVE_GIFT_BY_AMAZON, $this->request->data);

        } elseif ($this->request->is('post')) {

            $data = $this->request->data[self::MODEL_NAME_RECEIVE_GIFT_BY_AMAZON];

            /** データ整形 */
            // Amazonより取得した個人情報よりデータ整形
            $this->_setAmazonCustomerData($data);

            /** セッションデータ */
            CakeSession::write(self::MODEL_NAME_RECEIVE_GIFT_BY_AMAZON, $data);

            $this->ReceiveGiftByAmazonPay->set($data);

            /** 受け取り情報バリデーション */
            $validation_item[] = 'gift_cd';
            $validation_item[] = 'lastname';
            $validation_item[] = 'firstname';
            $validation_item[] = 'tel1';
            $validation_item[] = 'postal';
            $validation_item[] = 'pref';
            $validation_item[] = 'address1';
            $validation_item[] = 'address2';
            $validation_item[] = 'address3';

            $error_flag = false;
            if (!$this->ReceiveGiftByAmazonPay->validates(['fieldList' => $validation_item])) {
                $error_flag = true;
            }

            if (!$error_flag) {
                // integrity check gift code
                $this->loadModel(self::MODEL_NAME_CHECK_GIFT);
                $result_gift_data = $this->UnusedGiftInfo->apiGet(['gift_cd' => $data['gift_cd']]);
                $data['kit_list'] = [];
                if ($result_gift_data->isSuccess()) {
                    $result_gift_data = json_decode(json_encode($result_gift_data), true);
                    if (empty($result_gift_data['results'])) {
                        $error_flag = true;
                        $this->ReceiveGiftByAmazonPay->validationErrors['gift_cd'][0] = '該当するギフトコードが存在しません';
                    } else {
                        foreach ($result_gift_data['results'] as $gift_data) {
                            $data['kit_list'][] = [
                                'kit_cd'   => $gift_data['kit_cd'],
                                'kit_cnt'  => $gift_data['count'],
                            ];
                        }
                    }
                }
            }

            if ($error_flag) {
                $this->set('address_list', CakeSession::read('address_list'));
                return $this->render('gift_input_amazon_pay');
            }

            CakeSession::write(self::MODEL_NAME_RECEIVE_GIFT_BY_AMAZON, $data);

            return $this->redirect(['controller' => 'receive', 'action' => 'confirm_amazon_pay']);

        }

    }

    /**
     * ギフト受け取り確認フォーム
     */
    public function gift_confirm_amazon_pay()
    {
        // check access source actions
        $allow_action_list = [
            'Receive/gift_input_amazon_pay',
            'Receive/gift_confirm_amazon_pay',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'order', 'action' => 'gift_add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $data = CakeSession::read(self::MODEL_NAME_RECEIVE_GIFT_BY_AMAZON);

        $data['address'] = $data['pref'] . $data['address1'] . $data['address2'] . $data['address3'];

        CakeSession::write(self::MODEL_NAME_RECEIVE_GIFT_BY_AMAZON, $data);

        $this->set(self::MODEL_NAME_RECEIVE_GIFT_BY_AMAZON, $data);
    }

    /**
     * ギフト受け取り完了フォーム
     */
    public function gift_complete_amazon_pay()
    {
        // check access source actions
        $allow_action_list = [
            'Receive/gift_confirm_amazon_pay',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'receive', 'action' => 'gift_add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_RECEIVE_GIFT_BY_AMAZON);

        $data = CakeSession::read(self::MODEL_NAME_RECEIVE_GIFT_BY_AMAZON);

        /** ギフトを受け取り */
        $tmp_kit = [];
        foreach ($data['kit_list'] as $kit_data) {
            $tmp_kit[] = $kit_data['kit_cd'] . ':' . $kit_data['kit_cnt'];
        }
        $api_parm = [
            'gift_cd'       => $data['gift_cd'],
            'kit'           => implode(",", $tmp_kit),
            'delivery_name' => $data['name'],
            'tel1'          => $data['tel1'],
            'postal'        => $data['postal'],
            'pref'          => $data['pref'],
            'address1'      => $data['address1'],
            'address2'      => $data['address2'],
            'address3'      => $data['address3'],
        ];

        $result_gift_data = $this->ReceiveGiftByAmazonPay->apiPost($api_parm);

        if (!$result_gift_data->isSuccess()) {
            $this->ReceiveGiftByAmazonPay->validationErrors['gift_cd'][0] = '該当するギフトコードが存在しません';
            return $this->render('gift_input_amazon_pay');
        }

        $this->set(self::MODEL_NAME_RECEIVE_GIFT_BY_AMAZON, $data);

        $this->_cleanKitOrderSession();
    }


    /**
     * ajax 住所リスト変更による配送日時取得
     */
    public function gift_as_get_gift_data()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        $this->autoRender = false;

        $status = false;

        // integrity check gift code
        $this->loadModel(self::MODEL_NAME_CHECK_GIFT);

        $result_gift_data = $this->UnusedGiftInfo->apiGet(['gift_cd' => $this->request->data['gift_cd']]);
        $result = [];
        if ($result_gift_data->isSuccess()) {
            $result_gift_data = json_decode(json_encode($result_gift_data), true);
            if (!empty($result_gift_data['results'])) {
                $status = true;
                foreach ($result_gift_data['results'] as $gift_data) {
                    $result[] = [
                        'kit_cd'  => $gift_data['kit_cd'],
                        'kit_cnt' => $gift_data['count'],
                    ];
                }
            }
        }

        return json_encode(compact('status', 'result'));
    }

    /**
     * AmazonPayment情報取得設定
     */
    private function _setAmazonCustomerData(&$_data)
    {
        $this->loadModel('AmazonPayModel');

        $tmp_data = $_data;

        $tmp_data['address_consent_token'] = $this->Customer->getAmazonPayAccessKey();
        $tmp_data['mws_auth_token']        = Configure::read('app.amazon_pay.client_id');

        $result = $this->AmazonPayModel->getOrderReferenceDetails($tmp_data);

        if($result['ResponseStatus'] != '200') {
            CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($result, true));
            $this->Flash->validation('Amazon Pay からの情報取得に失敗しました。再度お試し下さい。', ['key' => 'customer_amazon_pay_info']);
            $this->redirect(['controller' => 'receive', 'action' => 'input_amazon_pay']);
        }

        // Amazonより個人情報を取得
        $physicaldestination = $result['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination']['PhysicalDestination'];
        $physicaldestination = $this->AmazonPayModel->wrapPhysicalDestination($physicaldestination);

        $amazon_physical_name_list = AMAZON_CHANGE_PHYSICALDESTINATION_NAME_ARRAY;
        foreach ($amazon_physical_name_list as $amazon_name => $data_name) {
            switch (true) {
                case $amazon_name === 'PostalCode':
                    $_data[$data_name] = $this->_editPostalFormat($physicaldestination[$amazon_name]);
                    break;
                default:
                    $_data[$data_name] = $physicaldestination[$amazon_name];
                    break;
            }
        }
    }

    /**
     * orderで使用しているセッションを削除
     */
    private function _cleanKitOrderSession()
    {
        CakeSession::delete(self::MODEL_NAME_RECEIVE_GIFT_BY_CARD);
        CakeSession::delete(self::MODEL_NAME_RECEIVE_GIFT_BY_AMAZON);
        CakeSession::delete('address_list');
    }

}
