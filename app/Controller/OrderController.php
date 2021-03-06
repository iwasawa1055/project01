<?php

App::uses('MinikuraController', 'Controller');
App::uses('CustomerKitPrice', 'Model');
App::uses('PaymentGMOKitByCreditCard', 'Model');
App::uses('AmazonPayModel', 'Model');
App::uses('PaymentAmazonKitAmazonPay', 'Model');


App::uses('AppValid', 'Lib');
App::uses('KitDeliveryDatetime', 'Model');
App::uses('EmailModel', 'Model');
App::uses('FirstKitPrice', 'Model');
App::uses('AppCode', 'Lib');

class OrderController extends MinikuraController
{
    const MODEL_NAME_DATETIME                   = 'DatetimeDeliveryKit';
    const MODEL_NAME_KIT_BY_AMAZON              = 'PaymentAmazonKitAmazonPay';
    const MODEL_NAME_KIT_BY_CREDIT_CARD         = 'PaymentGMOKitByCreditCard';
    const MODEL_NAME_NEKOPOS_KIT_BY_AMAZON      = 'PaymentNekoposKitAmazonPay';
    const MODEL_NAME_NEKOPOS_KIT_BY_CREDIT_CARD = 'PaymentNekoposKitByCreditCard';
    const MODEL_NAME_KIT_BY_BANK                = 'PaymentAccountTransferKit';
    const MODEL_NAME_CREDIT_CARD                = 'PaymentGMOCreditCard';
    const MODEL_NAME_CREDIT_CARD_CHECK          = 'PaymentGMOCreditCardCheck';

    /** layout */
    public $layout = 'order';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        // 法人口座未登録用遷移
        if ($this->action !== 'cannot' && !$this->Customer->isEntry() && !$this->Customer->canOrderKit()) {
            return $this->redirect(['action' => 'cannot']);
        }

        $this->Order = $this->Components->load('Order');
        $this->Order->init($this->Customer->getToken()['division'], $this->Customer->hasCreditCard());
        $this->loadModel(self::MODEL_NAME_DATETIME);
        $this->set('validErrors', []);

        // 配送先
        $this->set('address', $this->Address->get());
        $this->set('default_payment', $this->Customer->getDefaultCard());
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
    public function add()
    {
        // session delete
        $allow_action_list = [
            'Order/add',
            'Order/input_card',
            'Order/input_bank',
            'Order/input_amazon_pay',
            'Order/confirm_card',
            'Order/confirm_bank',
            'Order/confirm_amazon_pay',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->_cleanKitOrderSession();
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // entry user
        if ($this->Customer->isEntry()) {
            return $this->redirect(['controller' => 'customer/register', 'action' => 'add_personal']);
        }

        // Amazon Payment
        if ($this->Customer->isAmazonPay()) {
            CakeSession::write('order_type', 'amazon');
            $this->redirect('/order/input_amazon_pay');
        }

        // card or bank
        if ($this->Customer->isPrivateCustomer()) {
            // カード情報取得
            $card_data = $this->Customer->getDefaultCard();
            CakeSession::write('card_data', $card_data);
            CakeSession::write('order_type', 'card');
            $this->redirect('/order/input_card');
        } else {
            // 法人 法人カードの場合 account_situationは空白
            if (empty($this->Customer->getInfo()['account_situation'])) {
                // カード情報取得
                $card_data = $this->Customer->getDefaultCard();
                CakeSession::write('card_data', $card_data);
                CakeSession::write('order_type', 'card');
                $this->redirect('/order/input_card');
            }
            CakeSession::write('order_type', 'bank');
            $this->redirect('/order/input_bank');
        }
    }

    /**
     * クレジットカード入力フォーム
     */
    public function input_card()
    {
        // check access source actions
        $allow_action_list = [
            'Order/add',
            'Order/input_card',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'order', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_KIT_BY_CREDIT_CARD);
        $this->loadModel(self::MODEL_NAME_NEKOPOS_KIT_BY_CREDIT_CARD);

        $this->set('delivery_datetime_list', CakeSession::read('delivery_datetime_list'));

        // カードエリア出力
        $card_flag = false;
        $order_total_data = CakeSession::read('order_total_data');
        $card_data = CakeSession::read('card_data');
        if (!empty($order_total_data['price']) || empty($card_data)) {
            $card_flag = true;
        }
        $this->set('card_flag', $card_flag);

        if ($this->request->is('get')) {

            $this->request->data[self::MODEL_NAME_KIT_BY_CREDIT_CARD] = CakeSession::read(self::MODEL_NAME_KIT_BY_CREDIT_CARD);

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

            $this->set('card_data', CakeSession::read('card_data'));
            $this->set('address_list', CakeSession::read('address_list'));

            $this->PaymentGMOKitByCreditCard->set($this->request->data);

        } elseif ($this->request->is('post')) {

            $data = $this->request->data[self::MODEL_NAME_KIT_BY_CREDIT_CARD];

            // 注文情報
            $kit_list = array();
            $order_total_data = array();
            $order_list = $this->_setOrderList($data, $order_total_data, $kit_list);
            CakeSession::write('order_total_data', $order_total_data);

            // カードエリア出力
            $card_flag = false;
            if (!empty($order_total_data['price']) || empty($card_data)) {
                $card_flag = true;
            }
            $this->set('card_flag', $card_flag);

            /** セッションデータ */
            CakeSession::write(self::MODEL_NAME_KIT_BY_CREDIT_CARD, $data);
            CakeSession::write(self::MODEL_NAME_NEKOPOS_KIT_BY_CREDIT_CARD, $data);

            $this->PaymentGMOKitByCreditCard->set($data);
            $this->PaymentNekoposKitByCreditCard->set($data);

            $error_flag = false;

            /** サービスの申し込み者情報バリデーション */
            $validation_item[] = 'address_id';
            if (!empty($order_total_data['price'])) {
                $validation_item[] = 'security_cd';
            }
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
            // ハンガーのみ指定以外の場合はdatetime_cdのチェックを実施(ハンガー3つ以上の場合はdatetime_cdチェック対象)
            if (!empty($kit_list['other']) || (empty($kit_list['other']) && empty($kit_list['hanger'])) || (isset($kit_list['hanger'][KIT_CD_CLOSET]) && $kit_list['hanger'][KIT_CD_CLOSET] > 2)) {
                $validation_item[] = 'datetime_cd';
            }
            if (!$this->PaymentGMOKitByCreditCard->validates(['fieldList' => $validation_item])) {
                $error_flag = true;
            }

            // 通常項目 or キット未選択
            if (!empty($kit_list['other']) || (empty($kit_list['other']) && empty($kit_list['hanger']))) {
                $this->_setKitData($data, $kit_list['other']);
                CakeSession::write(self::MODEL_NAME_KIT_BY_CREDIT_CARD, $data);
                $validation_item = [
                    'mono_num',
                    'mono_appa_num',
                    'hako_num',
                    'hako_appa_num',
                    'hako_book_num',
                    'cleaning_num',
                    'library_num',
                ];
                if (!$this->PaymentGMOKitByCreditCard->validates(['fieldList' => $validation_item])) {
                    $error_flag = true;
                }
            }
            // ハンガー項目 or キット未選択
            if (!empty($kit_list['hanger']) || (empty($kit_list['other']) && empty($kit_list['hanger']))) {
                $this->_setKitData($data, $kit_list['hanger']);
                CakeSession::write(self::MODEL_NAME_NEKOPOS_KIT_BY_CREDIT_CARD, $data);
                // バリデーション項目
                $validation_item = [
                    'hanger_num',
                ];
                if (!$this->PaymentNekoposKitByCreditCard->validates(['fieldList' => $validation_item])) {
                    $error_flag = true;
                }
            }

            // 登録したカードを変更するにチェックをつけて、POSTした場合、登録を促す
            if ($data['select-card'] !== 'as-card' || empty(CakeSession::read('card_data'))) {
                $this->PaymentGMOKitByCreditCard->validationErrors['card_no'][0] = 'カードを変更・登録する場合はこの画面でカードを登録を完了させて下さい';
                $error_flag = true;
            }

            if ($error_flag) {
                $this->set('address_list', CakeSession::read('address_list'));
                $this->set('card_data', CakeSession::read('card_data'));
                return $this->render('input_card');
            }

            CakeSession::write('order_list', $order_list);

            return $this->redirect(['controller' => 'order', 'action' => 'confirm_card']);

        }
    }

    /**
     * クレジットカード確認フォーム
     */
    public function confirm_card()
    {
        // check access source actions
        $allow_action_list = [
            'Order/input_card',
            'Order/confirm_card',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'order', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        /** セッションデータリスト */
        $data_list = array(
            self::MODEL_NAME_KIT_BY_CREDIT_CARD         => CakeSession::read(self::MODEL_NAME_KIT_BY_CREDIT_CARD),
            self::MODEL_NAME_NEKOPOS_KIT_BY_CREDIT_CARD => CakeSession::read(self::MODEL_NAME_NEKOPOS_KIT_BY_CREDIT_CARD),
        );

        /** 表示用データ整形 */
        foreach ($data_list as $key => $data) {
            // 配送日時テキスト
            $data['select_delivery_text'] = $this->_convDatetimeCode($data['datetime_cd']);
            // 既存アドレス使用時
            if ($data['address_id'] !== 'add') {
                $address_list = $this->Address->get();
                $target_index = array_search($data['address_id'], array_column($address_list, 'address_id'));
                $address_data = $address_list[$target_index];
                $data['name']    = $address_data['lastname'] . '　' . $address_data['firstname'];
                $data['address'] = $address_data['pref'] . $address_data['address1'] . $address_data['address2'] . $address_data['address3'];
            } else {
                $data['name']    = $data['lastname'] . '　' . $data['firstname'];
                $data['address'] = $data['pref'] . $data['address1'] . $data['address2'] . $data['address3'];
            }
            CakeSession::write($key, $data);
            // 出力時のみ用に郵便番号を保持
            if ($data['address_id'] !== 'add') {
                $data['postal'] = $address_data['postal'];
                $data['tel1']   = $address_data['tel1'];
            }
            $this->set($key, $data);
        }

        // サービス無料期限
        $free_limit_date = $this->Common->getServiceFreeLimit(date('Y-m-d'));

        $this->set('free_limit_date', $free_limit_date);
        $this->set('card_data', CakeSession::read('card_data'));
        $this->set('order_list', CakeSession::read('order_list'));
        $this->set('order_total_data', CakeSession::read('order_total_data'));
    }

    /**
     * クレジットカード完了フォーム
     */
    public function complete_card()
    {
        // check access source actions
        $allow_action_list = [
            'Order/confirm_card',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'order', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        /** セッションデータリスト */
        $data_list = array(
            'other'  => CakeSession::read(self::MODEL_NAME_KIT_BY_CREDIT_CARD),
            'hanger' => CakeSession::read(self::MODEL_NAME_NEKOPOS_KIT_BY_CREDIT_CARD),
        );

        /** 登録用データ整形 */
        foreach ($data_list as &$data) {
            // 既存アドレス使用時
            if ($data['address_id'] !== 'add') {
                $address_list = $this->Address->get();
                $target_index = array_search($data['address_id'], array_column($address_list, 'address_id'));
                $data = array_merge($data, $address_list[$target_index]);
            }
            $data['card_seq']       = 0;
            $data['lastname_kana']  = '　';
            $data['firstname_kana'] = '　';
        }

        /** 住所登録 */
        if ($data_list['other']['address_id'] == 'add') {
            if (isset($data_list['other']['insert_address_flag']) && $data_list['other']['insert_address_flag']) {
                $this->_postCustomerAddress($data_list['other'], 'input_card');
            }
        }

        /** 決済 */
        // 通常
        if (isset($data_list['other']['kit'])) {
            // アドレスの処理(API側でパースした際に12文字目がスペースのみで終わらないように変換をかける)
            if(mb_strlen($data_list['other']['address']) === 12  && mb_substr($data_list['other']['address'], 11, 1) === '　'){ //合計12文字で最後が全角スペースで終わる場合
                $data_list['other']['address'] = mb_substr($data_list['other']['address'], 0, 11); //12文字目の全角スペースを除いた先頭から11文字を返す
            }
            // API用にデータを整形
            unset($data_list['other']['address1'], $data_list['other']['address2'], $data_list['other']['address3']);

            $result = $this->_postPaymentCreditCard($data_list['other']);
        }
        // ハンガー
        if (isset($data_list['hanger']['kit'])) {
            // アドレスの処理(API側でパースした際に12文字目がスペースのみで終わらないように変換をかける)
            if(mb_strlen($data_list['hanger']['address']) === 12  && mb_substr($data_list['hanger']['address'], 11, 1) === '　'){ //合計12文字で最後が全角スペースで終わる場合
                $data_list['hanger']['address'] = mb_substr($data_list['hanger']['address'], 0, 11); //12文字目の全角スペースを除いた先頭から11文字を返す
            }
            // API用にデータを整形
            unset($data_list['hanger']['address1'], $data_list['hanger']['address2'], $data_list['hanger']['address3']);

            if ($data_list['hanger']['hanger_num'] > 2) {
                $result = $this->_postPaymentCreditCard($data_list['hanger']);
            } else {
                $result = $this->_postPaymentNekoposCreditCard($data_list['hanger']);
            }

        }

        // CriteoとA8用のコンバージョン測定用json
        $tmp_order_list_criteo_array = [];
        $order_list_criteo_array = [];
        $order_list_a8_array = [];
        $order_list = CakeSession::read('order_list');

        foreach ($order_list as $key => $val) {
            foreach ($val as $k1 => $v1) {
                foreach ($v1 as $k2 => $v2) {
                    $tmp_order_list_criteo_array[$k1][] = ['id' => $k2, 'price' => '', 'quantity' => (int)$v2['number']];
                }
            }
        }
        foreach ($tmp_order_list_criteo_array as $tk => $tv) {
            $num = 0;
            foreach ($tv as $tk1 => $tv1) {
                $num += $tv1['quantity'];
            }
            $price = ($tv[0]['id'] == KIT_CD_CLEANING_PACK) ? (PRODUCT_DATA_ARRAY[$tk]['box_price']): PRODUCT_DATA_ARRAY[$tk]['monthly_price'];
            $order_list_criteo_array[] = ['id' => $tk, 'price' => $price, 'quantity' => (int)$num];
        }
        foreach ($order_list_criteo_array as $key => $var) {
            $order_list_a8_array[] = ['code' => $var['id'], 'price' => (int)$var['price'], 'quantity' => (int)$var['quantity']];
        }

        $this->set('order_id', $result->results['order_id']);
        $this->set('order_list_criteo_json', json_encode($order_list_criteo_array));
        $this->set('order_list_a8_json', json_encode($order_list_a8_array));
        $this->set('card_data', CakeSession::read('card_data'));
        $this->set('order_list', CakeSession::read('order_list'));
        $this->set('order_total_data', CakeSession::read('order_total_data'));
        $this->set(self::MODEL_NAME_KIT_BY_CREDIT_CARD, $data_list['other']);

        $this->_cleanKitOrderSession();
    }

    /**
     * 銀行入力フォーム
     */
    public function input_bank()
    {
        // check access source actions
        $allow_action_list = [
            'Order/add',
            'Order/input_bank',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'order', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_KIT_BY_BANK);

        $this->set('delivery_datetime_list', CakeSession::read('delivery_datetime_list'));

        if ($this->request->is('get')) {

            // セッションから入力値を取得
            $this->request->data[self::MODEL_NAME_KIT_BY_BANK] = CakeSession::read(self::MODEL_NAME_KIT_BY_BANK);

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

            $this->PaymentAccountTransferKit->set($this->request->data);

        } elseif ($this->request->is('post')) {

            $data = $this->request->data[self::MODEL_NAME_KIT_BY_BANK];

            // 注文情報
            $kit_list = array();
            $order_total_data = array();
            $order_list = $this->_setOrderList($data, $order_total_data, $kit_list);
            $this->_setKitData($data, $kit_list['other']);

            /** セッションデータ */
            CakeSession::write(self::MODEL_NAME_KIT_BY_BANK, $data);

            $this->PaymentAccountTransferKit->set($data);

            /** サービスの申し込み者情報バリデーション */
            $validation_item = [
                'mono_num',
                'mono_appa_num',
                'hako_num',
                'hako_appa_num',
                'hako_book_num',
                'cleaning_num',
                'address_id',
                'datetime_cd',
            ];
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

            if (!$this->PaymentAccountTransferKit->validates(['fieldList' => $validation_item])) {
                $this->set('address_list', CakeSession::read('address_list'));
                return $this->render('input_bank');
            }

            CakeSession::write('order_list', $order_list);
            CakeSession::write('order_total_data', $order_total_data);

            return $this->redirect(['controller' => 'order', 'action' => 'confirm_bank']);
        }
    }

    /**
     * 銀行確認フォーム
     */
    public function confirm_bank()
    {
        // check access source actions
        $allow_action_list = [
            'Order/input_bank',
            'Order/confirm_bank',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'order', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $data = CakeSession::read(self::MODEL_NAME_KIT_BY_BANK);

        /** データ整形 */
        // 配送日時テキスト
        $data['select_delivery_text'] = $this->_convDatetimeCode($data['datetime_cd']);
        // 既存アドレス使用時
        if ($data['address_id'] !== 'add') {
            $address_list = $this->Address->get();
            $target_index = array_search($data['address_id'], array_column($address_list, 'address_id'));
            $address_data = $address_list[$target_index];
            $data['name']    = $address_data['lastname'] . '　' . $address_data['firstname'];
            $data['address'] = $address_data['pref'] . $address_data['address1'] . $address_data['address2'] . $address_data['address3'];
        } else {
            $data['name']    = $data['lastname'] . '　' . $data['firstname'];
            $data['address'] = $data['pref'] . $data['address1'] . $data['address2'] . $data['address3'];
        }
        CakeSession::write(self::MODEL_NAME_KIT_BY_BANK, $data);
        // 出力時のみ用データ
        if ($data['address_id'] !== 'add') {
            $data['postal'] = $address_data['postal'];
            $data['tel1']   = $address_data['tel1'];
        }
        $this->set(self::MODEL_NAME_KIT_BY_BANK, $data);

        // サービス無料期限
        $free_limit_date = $this->Common->getServiceFreeLimit(date('Y-m-d'));

        $this->set('free_limit_date', $free_limit_date);
        $this->set('order_list', CakeSession::read('order_list'));
        $this->set('order_total_data', CakeSession::read('order_total_data'));
    }

    /**
     * 銀行完了フォーム
     */
    public function complete_bank()
    {
        // check access source actions
        $allow_action_list = [
            'Order/confirm_bank',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'order', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // セッションから入力値を取得
        $data = CakeSession::read(self::MODEL_NAME_KIT_BY_BANK);

        // 既存アドレス使用時
        if ($data['address_id'] !== 'add') {
            $address_list = $this->Address->get();
            $target_index = array_search($data['address_id'], array_column($address_list, 'address_id'));
            $data = array_merge($data, $address_list[$target_index]);
        }
        $data['card_seq'] = 0;

        /** 住所登録 */
        if ($data['address_id'] == 'add') {
            if (isset($data['insert_address_flag']) && $data['insert_address_flag']) {
                $this->_postCustomerAddress($data, 'input_bank');
            }
        }

        /** 決済 */
        // アドレスの処理(API側でパースした際に12文字目がスペースのみで終わらないように変換をかける)
        if(mb_strlen($data['address']) === 12  && mb_substr($data['address'], 11, 1) === '　'){ //合計12文字で最後が全角スペースで終わる場合
            $data['address'] = mb_substr($data['address'], 0, 11); //12文字目の全角スペースを除いた先頭から11文字を返す
        }
        $result = $this->_postPaymentBank($data);

        // CriteoとA8用のコンバージョン測定用json
        $tmp_order_list_criteo_array = [];
        $order_list_criteo_array = [];
        $order_list_a8_array = [];
        $order_list = CakeSession::read('order_list');

        foreach ($order_list as $key => $val) {
            foreach ($val as $k1 => $v1) {
                foreach ($v1 as $k2 => $v2) {
                    $tmp_order_list_criteo_array[$k1][] = ['id' => $k2, 'price' => '', 'quantity' => (int)$v2['number']];
                }
            }
        }
        foreach ($tmp_order_list_criteo_array as $tk => $tv) {
            $num = 0;
            foreach ($tv as $tk1 => $tv1) {
                $num += $tv1['quantity'];
            }
            $price = ($tv[0]['id'] == KIT_CD_CLEANING_PACK) ? (PRODUCT_DATA_ARRAY[$tk]['box_price']): PRODUCT_DATA_ARRAY[$tk]['monthly_price'];
            $order_list_criteo_array[] = ['id' => $tk, 'price' => $price, 'quantity' => (int)$num];
        }
        foreach ($order_list_criteo_array as $key => $var) {
            $order_list_a8_array[] = ['code' => $var['id'], 'price' => (int)$var['price'], 'quantity' => (int)$var['quantity']];
        }

        $this->set('order_id', $result->results[0]['order_id']);
        $this->set('order_list_criteo_json', json_encode($order_list_criteo_array));
        $this->set('order_list_a8_json', json_encode($order_list_a8_array));

        $this->set('order_list', CakeSession::read('order_list'));
        $this->set('order_total_data', CakeSession::read('order_total_data'));
        $this->set(self::MODEL_NAME_KIT_BY_BANK, $data);

        $this->_cleanKitOrderSession();
    }

    /*
     * AmazonPayment入力フォーム
     */
    public function input_amazon_pay()
    {
        // check access source actions
        $allow_action_list = [
            'Order/add',
            'Order/input_amazon_pay',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'order', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_KIT_BY_AMAZON);
        $this->loadModel(self::MODEL_NAME_NEKOPOS_KIT_BY_AMAZON);

        $this->set('delivery_datetime_list', CakeSession::read('delivery_datetime_list'));

        if ($this->request->is('get')) {

            $this->request->data[self::MODEL_NAME_KIT_BY_AMAZON] = CakeSession::read(self::MODEL_NAME_KIT_BY_AMAZON);

            $this->PaymentAmazonKitAmazonPay->set($this->request->data);

        } elseif ($this->request->is('post')) {

            $data = $this->request->data[self::MODEL_NAME_KIT_BY_AMAZON];

            /** データ整形 */
            // Amazonより取得した個人情報よりデータ整形
            $this->_setAmazonCustomerData($data);

            // その他
            $data['amazon_user_id']       = CakeSession::read('login.amazon_pay.user_info.user_id');
            $data['access_token']         = $this->Customer->getAmazonPayAccessKey();
            $data['address']              = $data['pref'] . $data['address1'] . $data['address2'] . $data['address3'];
            if (isset($data['datetime_cd'])) {
                $data['select_delivery_text'] = $this->_convDatetimeCode($data['datetime_cd']);
            }

            // 注文情報
            $kit_list = array();
            $order_total_data = array();
            $order_list = $this->_setOrderList($data, $order_total_data, $kit_list);

            /** セッションデータ */
            CakeSession::write(self::MODEL_NAME_KIT_BY_AMAZON, $data);
            CakeSession::write(self::MODEL_NAME_NEKOPOS_KIT_BY_AMAZON, $data);

            $this->PaymentAmazonKitAmazonPay->set($data);
            $this->PaymentNekoposKitAmazonPay->set($data);

            $error_flag = false;

            /** サービスの申し込み者情報バリデーション */
            $validation_item = [
                'access_token',
                'amazon_user_id',
                'amazon_order_reference_id',
                'name',
                'tel1',
                'postal',
                'address',
            ];
            // ハンガーのみ指定以外の場合はdatetime_cdのチェックを実施(ハンガー3つ以上の場合はdatetime_cdチェック対象)
            if (!empty($kit_list['other']) || (empty($kit_list['other']) && empty($kit_list['hanger'])) || (isset($kit_list['hanger'][KIT_CD_CLOSET]) && $kit_list['hanger'][KIT_CD_CLOSET] > 2)) {
                $validation_item[] = 'datetime_cd';
            }
            if (!$this->PaymentAmazonKitAmazonPay->validates(['fieldList' => $validation_item])) {
                $error_flag = true;
            }

            // 通常項目 or キット未選択
            if (!empty($kit_list['other']) || (empty($kit_list['other']) && empty($kit_list['hanger']))) {
                $this->_setKitData($data, $kit_list['other']);
                CakeSession::write(self::MODEL_NAME_KIT_BY_AMAZON, $data);
                $validation_item = [
                    'mono_num',
                    'mono_appa_num',
                    'hako_num',
                    'hako_appa_num',
                    'hako_book_num',
                    'cleaning_num',
                    'library_num',
                ];
                if (!$this->PaymentAmazonKitAmazonPay->validates(['fieldList' => $validation_item])) {
                    $error_flag = true;
                }
            }
            // ハンガー項目 or キット未選択
            if (!empty($kit_list['hanger']) || (empty($kit_list['other']) && empty($kit_list['hanger']))) {
                $this->_setKitData($data, $kit_list['hanger']);
                CakeSession::write(self::MODEL_NAME_NEKOPOS_KIT_BY_AMAZON, $data);
                // バリデーション項目
                $validation_item = [
                    'hanger_num',
                ];
                if (!$this->PaymentNekoposKitAmazonPay->validates(['fieldList' => $validation_item])) {
                    $error_flag = true;
                }
            }
            if ($error_flag) {
                $this->set('address_list', CakeSession::read('address_list'));
                $this->set('card_data', CakeSession::read('card_data'));
                return $this->render('input_amazon_pay');
            }

            CakeSession::write('order_list', $order_list);
            CakeSession::write('order_total_data', $order_total_data);

            return $this->redirect(['controller' => 'order', 'action' => 'confirm_amazon_pay']);

        }
    }

    /**
     * AmazonPayment確認フォーム
     */
    public function confirm_amazon_pay()
    {
        // check access source actions
        $allow_action_list = [
            'Order/input_amazon_pay',
            'Order/confirm_amazon_pay',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'order', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // サービス無料期限
        $free_limit_date = $this->Common->getServiceFreeLimit(date('Y-m-d'));

        $this->set('free_limit_date', $free_limit_date);
        $this->set('order_list', CakeSession::read('order_list'));
        $this->set('order_total_data', CakeSession::read('order_total_data'));
        $this->set(self::MODEL_NAME_KIT_BY_AMAZON, CakeSession::read(self::MODEL_NAME_KIT_BY_AMAZON));
    }

    /**
     * AmazonPayment完了フォーム
     */
    public function complete_amazon_pay()
    {
        // check access source actions
        $allow_action_list = [
            'Order/confirm_amazon_pay',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'order', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // セッションから入力値を取得
        $other_data  = CakeSession::read(self::MODEL_NAME_KIT_BY_AMAZON);
        $hanger_data = CakeSession::read(self::MODEL_NAME_NEKOPOS_KIT_BY_AMAZON);

        /** 決済 */
        // 通常
        if (isset($other_data['kit'])) {
            $result = $this->_postPaymentAmazon($other_data);
        }
        // ハンガー
        if (isset($hanger_data['kit'])) {
            if ($hanger_data['hanger_num'] > 2) {
                $result = $this->_postPaymentAmazon($hanger_data);
            } else {
                $result = $this->_postPaymentNekoposAmazon($hanger_data);
            }
        }

        // CriteoとA8用のコンバージョン測定用json
        $tmp_order_list_criteo_array = [];
        $order_list_criteo_array = [];
        $order_list_a8_array = [];
        $order_list = CakeSession::read('order_list');

        foreach ($order_list as $key => $val) {
            foreach ($val as $k1 => $v1) {
                foreach ($v1 as $k2 => $v2) {
                    $tmp_order_list_criteo_array[$k1][] = ['id' => $k2, 'price' => '', 'quantity' => (int)$v2['number']];
                }
            }
        }
        foreach ($tmp_order_list_criteo_array as $tk => $tv) {
            $num = 0;
            foreach ($tv as $tk1 => $tv1) {
                $num += $tv1['quantity'];
            }
            $price = ($tv[0]['id'] == KIT_CD_CLEANING_PACK) ? (PRODUCT_DATA_ARRAY[$tk]['box_price']): PRODUCT_DATA_ARRAY[$tk]['monthly_price'];
            $order_list_criteo_array[] = ['id' => $tk, 'price' => $price, 'quantity' => (int)$num];
        }
        foreach ($order_list_criteo_array as $key => $var) {
            $order_list_a8_array[] = ['code' => $var['id'], 'price' => (int)$var['price'], 'quantity' => (int)$var['quantity']];
        }

        $this->set('order_id', $result->results['order_id']);
        $this->set('order_list_criteo_json', json_encode($order_list_criteo_array));
        $this->set('order_list_a8_json', json_encode($order_list_a8_array));

        $this->set('order_list', CakeSession::read('order_list'));
        $this->set('order_total_data', CakeSession::read('order_total_data'));
        $this->set(self::MODEL_NAME_KIT_BY_AMAZON, CakeSession::read(self::MODEL_NAME_KIT_BY_AMAZON));

        $this->_cleanKitOrderSession();
    }

    /**
     * 口座申請中(KE)の状態のユーザー
     * */
    public function cannot()
    {
    }

    public function as_register_credit_card()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        $this->autoRender = false;

        $this->loadModel(self::MODEL_NAME_CREDIT_CARD);

        $gmo_token = $this->request->data['gmo_token'];
        if(!empty($gmo_token)){
            $gmo_token = implode(',',$gmo_token);
        }
        $credit_data[self::MODEL_NAME_CREDIT_CARD]['gmo_token'] = $gmo_token;
        $this->PaymentGMOCreditCard->set($credit_data);
        $result = $this->PaymentGMOCreditCard->apiPost($this->PaymentGMOCreditCard->toArray());

        // set session (card data)
        CakeSession::write('card_data', $this->Customer->getDefaultCard());

        return json_encode($result);
    }

    public function as_update_credit_card()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        $this->autoRender = false;

        $this->loadModel(self::MODEL_NAME_CREDIT_CARD);

        $gmo_token = $this->request->data['gmo_token'];
        if(!empty($gmo_token)){
            $gmo_token = implode(',',$gmo_token);
        }
        $credit_data[self::MODEL_NAME_CREDIT_CARD]['gmo_token'] = $gmo_token;
        $this->PaymentGMOCreditCard->set($credit_data);
        $result = $this->PaymentGMOCreditCard->apiPut($this->PaymentGMOCreditCard->toArray());

        // set session (card data)
        CakeSession::write('card_data', $this->Customer->getDefaultCard());

        return json_encode($result);
    }

    public function as_check_credit_card()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        $this->autoRender = false;

        $this->loadModel(self::MODEL_NAME_CREDIT_CARD_CHECK);

        $gmo_token = $this->request->data['gmo_token'];
        if(!empty($gmo_token)){
            $gmo_token = implode(',',$gmo_token);
        }
        $credit_data['gmo_token'] = $gmo_token;
        $result = $this->PaymentGMOCreditCardCheck->getCreditCardCheck($credit_data);
        return json_encode($result);
    }

    /**
     * ajax 住所リスト変更による配送日時取得
     */
    public function as_get_datetime_by_address_id()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        $this->autoRender = false;

        $postal = '';
        $status = false;

        $address_id = $this->request->data['address_id'];
        $address = $this->Address->find($address_id);
        if (!empty($address) && !empty($address['postal'])) {
            $postal = $address['postal'];
        }

        $result = $this->_getAddressDatetime($postal);
        if ($result->isSuccess()) {
            $status = true;

            // 未選択項目のdetetimeを空にする
            $result = json_decode(json_encode($result), true);
            $target_index = array_search('0000-00-00', array_column($result['results'], 'datetime_cd'));
            $result['results'][$target_index]['datetime_cd'] = '';

            // session保持
            $delivery_datetime_list = array();
            foreach ($result['results'] as $tmp_datatime_data) {
                $delivery_datetime_list[$tmp_datatime_data['datetime_cd']] = $tmp_datatime_data['text'];
            }
            CakeSession::write('delivery_datetime_list', $delivery_datetime_list);
        }

        return json_encode(compact('status', 'result'));
    }

   /**
     * ajax 指定IDの配送日時情報取得 amazon pay
     */
    public function as_get_address_datetime_by_amazon()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        // 画面描画しない
        $this->autoRender = false;

        $postal = "";

        $amazon_order_reference_id  = filter_input(INPUT_POST, 'amazon_order_reference_id');
        if($amazon_order_reference_id === null) {
            return json_encode(['status' => false]);
        }

        // モデルロード
        $this->loadModel('AmazonPayModel');

        $set_param = array();
        $set_param['amazon_order_reference_id'] = $amazon_order_reference_id;

        $set_param['address_consent_token'] = $this->Customer->getAmazonPayAccessKey();

        $set_param['mws_auth_token'] = Configure::read('app.amazon_pay.client_id');

        $res = $this->AmazonPayModel->getOrderReferenceDetails($set_param);

        // GetOrderReferenceDetails
        if($res['ResponseStatus'] != '200') {
            return json_encode(['status' => false]);
        }

        if(!isset($res['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination']['PhysicalDestination']['PostalCode'])) {
            return json_encode(['status' => false]);
        }

        $postal = $res['GetOrderReferenceDetailsResult']['OrderReferenceDetails']['Destination']['PhysicalDestination']['PostalCode'];
        $status = false;
        $result = $this->_getAddressDatetime($postal);
        if ($result->isSuccess()) {
            $status = true;

            // 未選択項目のdetetimeを空にする
            $result = json_decode(json_encode($result), true);
            $target_index = array_search('0000-00-00', array_column($result['results'], 'datetime_cd'));
            $result['results'][$target_index]['datetime_cd'] = '';

            // session保持
            $delivery_datetime_list = array();
            foreach ($result['results'] as $tmp_datatime_data) {
                $delivery_datetime_list[$tmp_datatime_data['datetime_cd']] = $tmp_datatime_data['text'];
            }
            CakeSession::write('delivery_datetime_list', $delivery_datetime_list);
        }
        return json_encode(compact('status', 'result'));
    }

    /**
     * ajax 郵便番号変更による配送日時取得
     */
    public function as_get_datetime_by_postal()
    {
        if (!$this->request->is('ajax')) {
            return false;
        }

        $this->autoRender = false;

        $postal = filter_input(INPUT_POST, 'postal');
        $status = false;

        $result = $this->_getAddressDatetime($postal);

        if ($result->isSuccess()) {
            $status = true;

            // 未選択項目のdetetimeを空にする
            $result = json_decode(json_encode($result), true);
            $target_index = array_search('0000-00-00', array_column($result['results'], 'datetime_cd'));
            $result['results'][$target_index]['datetime_cd'] = '';

            // session保持
            $delivery_datetime_list = array();
            foreach ($result['results'] as $tmp_datatime_data) {
                $delivery_datetime_list[$tmp_datatime_data['datetime_cd']] = $tmp_datatime_data['text'];
            }
            CakeSession::write('delivery_datetime_list', $delivery_datetime_list);
        }

        return json_encode(compact('status', 'result'));
    }

    /**
     * 指定郵便番号の配送日時情報取得
     */
    private function _getAddressDatetime($postal)
    {
        // ハイフンチェック
        if (mb_strlen($postal) > 7) {
            // ハイフン部分を削除 macの場合全角ハイフンの文字コードが異なるため単純な全角半角変換ができない
            $postal = mb_substr($postal,0, 3) . mb_substr($postal, 4, 4);
        }
        $postal = mb_convert_kana($postal, 'nhk', "utf-8");

        $this->loadModel('DatetimeDeliveryKit');

        $result = $this->DatetimeDeliveryKit->apiGet(['postal' => $postal,]);

        return $result;
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
            $this->redirect('/order/input_amazon_pay');
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
     * kit情報取得設定
     */
    private function _setKitData(&$_data, $_kit_data)
    {
        $kit_params = array();
        foreach ($_kit_data as $code => $value) {
            $kit_params[] = $code . ':' .$value;
        }
        $_data['kit'] = implode(',', $kit_params);
    }

    /**
     * 会員住所登録
     */
    private function _postCustomerAddress($_data, $error_render)
    {
        $this->loadModel('CustomerAddress');
        // データ整形
        $_data['tel1'] = self::_wrapConvertKana($_data['tel1']);

        $this->CustomerAddress->set($_data);
        if (!$this->CustomerAddress->validates()) {
            return $this->render($error_render);
        }
        $result = $this->CustomerAddress->apiPost($this->CustomerAddress->toArray());
        if (!$result->isSuccess()) {
            return $this->render($error_render);
        }
    }

    /**
     * カード決済(通常)
     */
    private function _postPaymentCreditCard($_data)
    {
        $this->loadModel(self::MODEL_NAME_KIT_BY_CREDIT_CARD);

        // データ整形
        $_data['security_cd'] = self::_wrapConvertKana($_data['security_cd']);
        $_data['tel1']        = self::_wrapConvertKana($_data['tel1']);

        $this->PaymentGMOKitByCreditCard->set($_data);
        $result_kit_card = $this->PaymentGMOKitByCreditCard->apiPost($this->PaymentGMOKitByCreditCard->toArray());

        if ($result_kit_card->status !== '1') {
            $this->Flash->validation($result_kit_card->error_message, ['key' => 'customer_kit_card_info']);
            return $this->redirect(['controller' => 'order', 'action' => 'input_card']);
        }

        return $result_kit_card;
    }

    /**
     * カード決済(ネコポス)
     */
    private function _postPaymentNekoposCreditCard($_data)
    {
        $this->loadModel(self::MODEL_NAME_NEKOPOS_KIT_BY_CREDIT_CARD);

        // データ整形
        $_data['security_cd'] = self::_wrapConvertKana($_data['security_cd']);
        $_data['tel1']        = self::_wrapConvertKana($_data['tel1']);

        $this->PaymentNekoposKitByCreditCard->set($_data);
        $result_kit_card = $this->PaymentNekoposKitByCreditCard->apiPost($this->PaymentNekoposKitByCreditCard->toArray());

        if ($result_kit_card->status !== '1') {
            $this->Flash->validation($result_kit_card->error_message, ['key' => 'customer_kit_card_info']);
            return $this->redirect(['controller' => 'order', 'action' => 'input_card']);
        }

        return $result_kit_card;
    }

    /**
     * 銀行口座決済
     */
    private function _postPaymentBank($_data)
    {
        $this->loadModel('PaymentAccountTransferKit');

        // データ整形
        $_data['tel1'] = self::_wrapConvertKana($_data['tel1']);

        $this->PaymentAccountTransferKit->set($_data);
        $result_kit_payment_transfer = $this->PaymentAccountTransferKit->apiPost($this->PaymentAccountTransferKit->toArray());
        if ($result_kit_payment_transfer->status !== '1') {
            if ($result_kit_payment_transfer->http_code === 400) {
                $this->Flash->validation('サービスの申し込みエラー', ['key' => 'customer_kit_card_info']);
            } else {
                $this->Flash->validation($result_kit_payment_transfer->message, ['key' => 'customer_kit_card_info']);
            }
            // 暫定
            return $this->redirect(['controller' => 'order', 'action' => 'input_bank']);
        }

        return $result_kit_payment_transfer;
    }

    /**
     * AmazonPayment決済
     */
    private function _postPaymentAmazon($_data)
    {
        $this->loadModel('PaymentAmazonKitAmazonPay');

        // データ整形
        $_data['tel1'] = self::_wrapConvertKana($_data['tel1']);

        $this->PaymentAmazonKitAmazonPay->set($_data);
        $result_kit_amazon_pay = $this->PaymentAmazonKitAmazonPay->apiPost($this->PaymentAmazonKitAmazonPay->toArray());
        if ($result_kit_amazon_pay->status !== '1') {
            if ($result_kit_amazon_pay->http_code === 400) {
                $this->Flash->validation(AMAZON_PAY_ERROR_PAYMENT_FAILURE_RETRY, ['key' => 'customer_kit_card_info']);
            } else {
                $this->Flash->validation($result_kit_amazon_pay->message, ['key' => 'customer_kit_card_info']);
            }
            $this->redirect('/order/input_amazon_pay');
        }

        return $result_kit_amazon_pay;
    }

    /**
     * AmazonPayment決済(ネコポス)
     */
    private function _postPaymentNekoposAmazon($_data)
    {
        $this->loadModel('PaymentNekoposKitAmazonPay');

        // データ整形
        $_data['tel1'] = self::_wrapConvertKana($_data['tel1']);

        $this->PaymentNekoposKitAmazonPay->set($_data);
        $result_kit_amazon_pay = $this->PaymentNekoposKitAmazonPay->apiPost($this->PaymentNekoposKitAmazonPay->toArray());
        if ($result_kit_amazon_pay->status !== '1') {
            if ($result_kit_amazon_pay->http_code === 400) {
                $this->Flash->validation(AMAZON_PAY_ERROR_PAYMENT_FAILURE_RETRY, ['key' => 'customer_kit_card_info']);
            } else {
                $this->Flash->validation($result_kit_amazon_pay->message, ['key' => 'customer_kit_card_info']);
            }
            $this->redirect('/order/input_amazon_pay');
        }

        return $result_kit_amazon_pay;
    }
    /**
     * 注文内容の作成
     */
    private function _setOrderList(&$_data, &$_order_total_data, &$_kit_list)
    {
        // kitコード 表示kit名取得
        $kit_code = KIT_CODE_DISP_NAME_ARRAY;
        // 金額取得API
        $kit_price = new CustomerKitPrice();
        // 決済時に使用するkitパラメータ
        $kit_param_list = [];
        // 金額集計
        $order_list = [];
        // 合計情報
        $_order_total_data['number'] = 0;
        $_order_total_data['price']  = 0;
        // kit情報
        $_kit_list['other'] = [];
        $_kit_list['hanger'] = [];

        foreach ($_data as $key => $value) {
            if (array_key_exists ($key, $kit_code)) {
                if ($value != 0 ) {
                    $code = $kit_code[$key]['code'];
                    // gvido用のコードを変換
                    $customer_info = $this->Customer->getInfo();
                    if (isset($customer_info['alliance_cd'])) {
                        if ($customer_info["alliance_cd"] == 'gvido' && $kit_code[$key]['code'] == KIT_CD_LIBRARY_DEFAULT) {
                            $code = KIT_CD_LIBRARY_GVIDO;
                        }
                    }
                    // 注文タイプ判別
                    $order_type = 'other';
                    if ($code === KIT_CD_CLOSET) {
                        $order_type = 'hanger';
                    } elseif ($code === KIT_CD_CLEANING_PACK) {
                        $order_type = 'cleaning';
                    }
                    $order_list[$order_type][$kit_code[$key]['product_cd']][$code] = [
                        'number'   => $value,
                        'kit_name' => $kit_code[$key]['name']
                    ];
                    $_order_total_data['number']  += $value;
                    $kit_param_list[] = $code . ':' .$value;
                    // クリーニングはネコポスでないのでここでまとめる
                    if ($code === KIT_CD_CLEANING_PACK) {
                        $order_type = 'other';
                    }
                    $_kit_list[$order_type][$code] = $value;
                }
            }
        }

        // 合計金額
        $r = $kit_price->apiGet(['kit' => implode(',', $kit_param_list)]);
        if ($r->isSuccess()) {
            $_order_total_data['price'] = $r->results[0]['total_price'] * 1;
        }

        return $order_list;
    }

    /*
     * 日付CD変換
     */
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

    /**
     * orderで使用しているセッションを削除
     */
    private function _cleanKitOrderSession()
    {
        CakeSession::delete(self::MODEL_NAME_KIT_BY_CREDIT_CARD);
        CakeSession::delete(self::MODEL_NAME_KIT_BY_BANK);
        CakeSession::delete(self::MODEL_NAME_KIT_BY_AMAZON);
        CakeSession::delete(self::MODEL_NAME_NEKOPOS_KIT_BY_AMAZON);
        CakeSession::delete(self::MODEL_NAME_NEKOPOS_KIT_BY_CREDIT_CARD);
        CakeSession::delete('card_data');
        CakeSession::delete('order_list');
        CakeSession::delete('address_list');
        CakeSession::delete('order_total_data');
        CakeSession::delete('delivery_datetime_list');
    }

}
