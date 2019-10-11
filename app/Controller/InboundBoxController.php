<?php

App::uses('AppValid', 'Lib');
App::uses('MinikuraController', 'Controller');
App::uses('DatePrivate', 'Model');
App::uses('TimePrivate', 'Model');
App::uses('InboundSelectedBox', 'Model');
App::uses('AmazonPayModel', 'Model');
App::uses('CustomerAddress', 'Model');
App::uses('MtYmstpost', 'Model');
App::uses('InboundBase', 'Model');

class InboundBoxController extends MinikuraController
{
    const MODEL_NAME_INBOUND_BASE = 'InboundBase';

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
     * 入力フォーム選択
     */
    public function add()
    {
        // session delete
        $allow_action_list = [
            'InboundBox/add',
            'InboundBox/input',
            'InboundBox/input_amazon_pay',
            'InboundBox/attention',
            'InboundBox/attention_amazon_pay',
            'InboundBox/confirm',
            'InboundBox/confirm_amazon_pay',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->_cleanInboundBoxSession();
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // Amazon Payment user
        if ($this->Customer->isAmazonPay()) {
            $this->redirect(['controller' => 'inbound_box', 'action'=>'input_amazon_pay']);
        }

        // card or bank user
        $this->redirect(['controller' => 'inbound_box', 'action'=>'input']);
    }

    /**
     * 入力フォーム
     */
    public function input()
    {
        // check access source actions
        $allow_action_list = [
            'InboundBox/add',
            'InboundBox/input',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'inbound_box', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // 確認画面から戻る際のフラグ
        CakeSession::write('attention_flag', false);

        $this->loadModel(self::MODEL_NAME_INBOUND_BASE);

        $new_box_list = $this->InfoBox->getListForInbound();
        $old_box_list = $this->InfoBox->getListForInboundOldBox();
        // 無料期限
        foreach ($new_box_list as &$box_info) {
            $this->_setFreeLimitDate($box_info);
        }
        foreach ($old_box_list as &$box_info) {
            $this->_setFreeLimitDate($box_info);
        }

        // view data
        $this->set('new_box_list', $new_box_list);
        $this->set('old_box_list', $old_box_list);

        if ($this->request->is('get')) {

            $this->request->data[self::MODEL_NAME_INBOUND_BASE] = CakeSession::read(self::MODEL_NAME_INBOUND_BASE);
            $this->InboundBase->set($this->request->data);
            $this->set('box_list_data', CakeSession::read('box_list_data'));

        } elseif ($this->request->is('post')) {
            $validErrors = [];

            $data = $this->request->data['InboundBase'];

            $box_list_data = [];
            if (isset($this->request->data['BoxList'])) {
                // ボックス情報
                $box_list_data = $this->request->data['BoxList'];
                $this->_setSelectedBoxList($data, $box_list_data, $validErrors);

                // 使用選択ボックス
                $box_use_flag = [
                    PRODUCT_CD_MONO          => false,
                    PRODUCT_CD_HAKO          => false,
                    PRODUCT_CD_CLEANING_PACK => false,
                    PRODUCT_CD_LIBRARY       => false,
                    PRODUCT_CD_CLOSET        => false,
                ];
                // 選択ボックスタイプ別情報取得
                if ($data['box_type'] == 'new') {
                    $tmp_box_list = $this->InfoBox->getListForInbound();
                } else {
                    $tmp_box_list = $this->InfoBox->getListForInboundOldBox();
                }
                // 選択ボックス
                foreach ($box_list_data[$data['box_type']] as $box_id => $item) {
                    // 存在チェック
                    $key_index = array_search($box_id, array_column($tmp_box_list, 'box_id'));
                    if ($key_index === false) {
                        $validErrors['Inbound']['box'] = '対象データに不備がありました。';
                        break;
                    } else {
                        $box_use_flag[$tmp_box_list[$key_index]['product_cd']] = true;
                    }
                }
                CakeSession::write('box_use_flag', $box_use_flag);
            }

            // 配送関連情報
            $this->_setDeliveryData($data, $validErrors);

            CakeSession::write(self::MODEL_NAME_INBOUND_BASE, $data);
            CakeSession::write('box_list_data', $box_list_data);

            if (!empty($validErrors)) {
                $this->InboundBase->set($data);
                $this->InboundBase->validationErrors = $validErrors;
                $this->set('box_list_data', CakeSession::read('box_list_data'));
                $this->set('address_list', CakeSession::read('address_list'));
                return $this->render('input');
            }

            // ボックスタイプ別遷移先変更
            $attention_prefix_list = [
                'MC',
                'MG',
                'CL',
            ];
            foreach ($attention_prefix_list as $prefix) {
                if(strpos($data['box'], $prefix) !== false){
                    return $this->redirect(['controller' => 'InboundBox', 'action' => 'attention']);
                }
            }
            return $this->redirect(['controller' => 'InboundBox', 'action' => 'confirm']);
        }
    }


    /*
     * ボックス超過確認
     * ※クローゼット、クリーニング、ギフトの場合のみ
     */
    public function attention()
    {
        // check access source actions
        $allow_action_list = [
            'InboundBox/input',
            'InboundBox/attention',
            'InboundBox/confirm',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'inbound_box', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // 確認画面から戻る際のフラグ
        CakeSession::write('attention_flag', true);

        if ($this->request->is('get')) {
            // 選択ボックス
            $target_box_list = [];
            $other_box_list  = [];
            $data = CakeSession::read(self::MODEL_NAME_INBOUND_BASE);
            $box_list_data = CakeSession::read('box_list_data');
            if ($data['box_type'] == 'new') {
                $tmp_box_list = $this->InfoBox->getListForInbound();
            } else {
                $tmp_box_list = $this->InfoBox->getListForInboundOldBox();
            }
            foreach ($box_list_data[$data['box_type']] as $box_id => $item) {
                if (isset($item['checkbox'])) {
                    // 存在チェック
                    $key_index = array_search($box_id, array_column($tmp_box_list, 'box_id'));
                    if ($key_index === false) {
                        $validErrors['Inbound']['box'] = '対象データに不備がありました。';
                        break;
                    } else {
                        $tmp_box_list[$key_index]['title'] = $item['title'];
                        if (in_array($tmp_box_list[$key_index]['product_cd'], array_keys(EXCESS_ATTENTION_PRODUCT_CD))) {
                            $target_box_list[] = $tmp_box_list[$key_index];
                        } else {
                            $other_box_list[] = $tmp_box_list[$key_index];
                        }
                    }
                }
            }

            // set data
            $this->set('target_box_list', $target_box_list);
            $this->set('other_box_list', $other_box_list);
            $this->set('box_use_flag', CakeSession::read('box_use_flag'));

        } elseif ($this->request->is('post')) {
            return $this->redirect(['controller' => 'InboundBox', 'action' => 'confirm']);
        }

    }

    /**
     * 確認フォーム
     */
    public function confirm()
    {
        // check access source actions
        $allow_action_list = [
            'InboundBox/input',
            'InboundBox/attention',
            'InboundBox/confirm',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'inbound_box', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $validErrors = [];
        $box_list = [];

        $data = CakeSession::read(self::MODEL_NAME_INBOUND_BASE);
        $box_list_data = CakeSession::read('box_list_data');

        // 選択ボックスタイプ別情報取得
        if ($data['box_type'] == 'new') {
            $tmp_box_list = $this->InfoBox->getListForInbound();
        } else {
            $tmp_box_list = $this->InfoBox->getListForInboundOldBox();
        }

        // 選択ボックス
        foreach ($box_list_data[$data['box_type']] as $box_id => $item) {
            if (isset($item['checkbox'])) {
                // 存在チェック
                $key_index = array_search($box_id, array_column($tmp_box_list, 'box_id'));
                if ($key_index === false) {
                    $validErrors['Inbound']['box'] = '対象データに不備がありました。';
                    break;
                } else {
                    $tmp_box_list[$key_index]['title'] = $item['title'];
                    $tmp_box_list[$key_index]['wrapping_type'] = (isset($item['wrapping_type']) && $item['wrapping_type'] == 1) ? 1 : 0;
                    $box_list[] = $tmp_box_list[$key_index];
                }
            }
        }

        if (!empty($validErrors)) {
            $this->set('validErrors', $validErrors);
            $this->redirect('/inbound_box/add');
        }

        // set data
        $this->Inbound->init($data);
        $this->set('dateList', $this->Inbound->date());
        $this->set('timeList', $this->Inbound->time());
        $this->set('data', $data);
        $this->set('box_list', $box_list);
        $this->set('box_use_flag', CakeSession::read('box_use_flag'));
        $this->set('attention_flag', CakeSession::read('attention_flag'));
    }

    /**
     * 完了フォーム
     */
    public function complete()
    {
        // check access source actions
        $allow_action_list = [
            'InboundBox/confirm',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'inbound_box', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $data = CakeSession::read(self::MODEL_NAME_INBOUND_BASE);

        // insert address
        if ($data['address_id'] == 'add' && isset($data['resister'])) {
            $this->_postCustomerAddress($data, 'input_card');
        }

        // cleaning_pack
        $post_data = $this->request->data;
        if (isset($post_data['InboundBase'])) {
            $this->_setCleaningPack($data, $post_data['InboundBase']);
        }

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
     * amazon pay 入力フォーム
     */
    public function input_amazon_pay()
    {
        // check access source actions
        $allow_action_list = [
            'InboundBox/add',
            'InboundBox/input_amazon_pay',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'inbound_box', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // 確認画面から戻る際のフラグ
        CakeSession::write('attention_flag', false);

        $this->loadModel(self::MODEL_NAME_INBOUND_BASE);

        $new_box_list = $this->InfoBox->getListForInbound();
        $old_box_list = $this->InfoBox->getListForInboundOldBox();
        // 無料期限
        foreach ($new_box_list as &$box_info) {
            $this->_setFreeLimitDate($box_info);
        }
        foreach ($old_box_list as &$box_info) {
            $this->_setFreeLimitDate($box_info);
        }

        // view data
        $this->set('new_box_list', $new_box_list);
        $this->set('old_box_list', $old_box_list);

        if ($this->request->is('get')) {

            $this->request->data[self::MODEL_NAME_INBOUND_BASE] = CakeSession::read(self::MODEL_NAME_INBOUND_BASE);
            $this->InboundBase->set($this->request->data);
            $this->set('box_list_data', CakeSession::read('box_list_data'));

        } elseif ($this->request->is('post')) {

            $validErrors = [];

            $data = $this->request->data['InboundBase'];

            $box_list_data = [];
            if (isset($this->request->data['BoxList'])) {
                // ボックス情報
                $box_list_data = $this->request->data['BoxList'];
                $this->_setSelectedBoxList($data, $box_list_data, $validErrors);

                // 使用選択ボックス
                $box_use_flag = [
                    PRODUCT_CD_MONO          => false,
                    PRODUCT_CD_HAKO          => false,
                    PRODUCT_CD_CLEANING_PACK => false,
                    PRODUCT_CD_LIBRARY       => false,
                    PRODUCT_CD_CLOSET        => false,
                ];
                // 選択ボックスタイプ別情報取得
                if ($data['box_type'] == 'new') {
                    $tmp_box_list = $this->InfoBox->getListForInbound();
                } else {
                    $tmp_box_list = $this->InfoBox->getListForInboundOldBox();
                }
                // 選択ボックス
                foreach ($box_list_data[$data['box_type']] as $box_id => $item) {
                    // 存在チェック
                    $key_index = array_search($box_id, array_column($tmp_box_list, 'box_id'));
                    if ($key_index === false) {
                        $validErrors['Inbound']['box'] = '対象データに不備がありました。';
                        break;
                    } else {
                        $box_use_flag[$tmp_box_list[$key_index]['product_cd']] = true;
                    }
                }
                CakeSession::write('box_use_flag', $box_use_flag);
            }

            /** データ整形 */
            // Amazonより取得した個人情報よりデータ整形
            $this->_setAmazonCustomerData($data);
            $data['address_id'] = 'add';
            // 配送関連情報
            $this->_setDeliveryData($data, $validErrors);

            CakeSession::write(self::MODEL_NAME_INBOUND_BASE, $data);
            CakeSession::write('box_list_data', $box_list_data);

            if (!empty($validErrors)) {
                $this->InboundBase->set($data);
                $this->InboundBase->validationErrors = $validErrors;
                $this->set('box_list_data', CakeSession::read('box_list_data'));
                return $this->render('input_amazon_pay');
            }

            // ボックスタイプ別遷移先変更
            $attention_prefix_list = [
                'MC',
                'MG',
                'CL',
            ];
            foreach ($attention_prefix_list as $prefix) {
                if(strpos($data['box'], $prefix) !== false){
                    return $this->redirect(['controller' => 'InboundBox', 'action' => 'attention_amazon_pay']);
                }
            }
            return $this->redirect(['controller' => 'InboundBox', 'action' => 'confirm_amazon_pay']);
        }
    }

    /*
     * ボックス超過確認
     * ※クローゼット、クリーニング、ギフトの場合のみ
     */
    public function attention_amazon_pay()
    {
        // check access source actions
        $allow_action_list = [
            'InboundBox/input_amazon_pay',
            'InboundBox/attention_amazon_pay',
            'InboundBox/confirm_amazon_pay',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'inbound_box', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // 確認画面から戻る際のフラグ
        CakeSession::write('attention_flag', true);

        if ($this->request->is('get')) {
            // 選択ボックス
            $target_box_list = [];
            $other_box_list  = [];
            $data = CakeSession::read(self::MODEL_NAME_INBOUND_BASE);
            $box_list_data = CakeSession::read('box_list_data');
            if ($data['box_type'] == 'new') {
                $tmp_box_list = $this->InfoBox->getListForInbound();
            } else {
                $tmp_box_list = $this->InfoBox->getListForInboundOldBox();
            }
            foreach ($box_list_data[$data['box_type']] as $box_id => $item) {
                if (isset($item['checkbox'])) {
                    // 存在チェック
                    $key_index = array_search($box_id, array_column($tmp_box_list, 'box_id'));
                    if ($key_index === false) {
                        $validErrors['Inbound']['box'] = '対象データに不備がありました。';
                        break;
                    } else {
                        $tmp_box_list[$key_index]['title'] = $item['title'];
                        if (in_array($tmp_box_list[$key_index]['product_cd'], array_keys(EXCESS_ATTENTION_PRODUCT_CD))) {
                            $target_box_list[] = $tmp_box_list[$key_index];
                        } else {
                            $other_box_list[] = $tmp_box_list[$key_index];
                        }
                    }
                }
            }

            // set data
            $this->set('target_box_list', $target_box_list);
            $this->set('other_box_list', $other_box_list);
            $this->set('box_use_flag', CakeSession::read('box_use_flag'));

        } elseif ($this->request->is('post')) {
            return $this->redirect(['controller' => 'InboundBox', 'action' => 'confirm_amazon_pay']);
        }

    }

    /**
     * Amazon Pay 確認フォーム
     */
    public function confirm_amazon_pay()
    {

        // check access source actions
        $allow_action_list = [
            'InboundBox/input_amazon_pay',
            'InboundBox/attention_amazon_pay',
            'InboundBox/confirm_amazon_pay',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'inbound_box', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $validErrors = [];
        $box_list = [];

        $data = CakeSession::read(self::MODEL_NAME_INBOUND_BASE);
        $box_list_data = CakeSession::read('box_list_data');

        // 選択ボックスタイプ別情報取得
        if ($data['box_type'] == 'new') {
            $tmp_box_list = $this->InfoBox->getListForInbound();
        } else {
            $tmp_box_list = $this->InfoBox->getListForInboundOldBox();
        }

        // 選択ボックス
        foreach ($box_list_data[$data['box_type']] as $box_id => $item) {
            if (isset($item['checkbox'])) {
                // 存在チェック
                $key_index = array_search($box_id, array_column($tmp_box_list, 'box_id'));
                if ($key_index === false) {
                    $validErrors['Inbound']['box'] = '対象データに不備がありました。';
                    break;
                } else {
                    $tmp_box_list[$key_index]['title'] = $item['title'];
                    $tmp_box_list[$key_index]['wrapping_type'] = (isset($item['wrapping_type']) && $item['wrapping_type'] == 1) ? 1 : 0;
                    $box_list[] = $tmp_box_list[$key_index];
                }
            }
        }

        if (!empty($validErrors)) {
            $this->set('validErrors', $validErrors);
            $this->redirect('/inbound_box/add');
        }

        // set data
        $this->Inbound->init($data);
        $this->set('dateList', $this->Inbound->date());
        $this->set('timeList', $this->Inbound->time());
        $this->set('data', $data);
        $this->set('box_list', $box_list);
        $this->set('box_use_flag', CakeSession::read('box_use_flag'));
        $this->set('attention_flag', CakeSession::read('attention_flag'));
    }

    /**
     * amazon pay 完了フォーム
     */
    public function complete_amazon_pay()
    {
        // check access source actions
        $allow_action_list = [
            'InboundBox/confirm_amazon_pay',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'inbound_box', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $data = CakeSession::read(self::MODEL_NAME_INBOUND_BASE);

        // cleaning_pack
        $post_data = $this->request->data;
        if (isset($post_data['InboundBase'])) {
            $this->_setCleaningPack($data, $post_data['InboundBase']);
        }

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
     * 預け入れ日時情報取得
     */
    public function as_get_inbound_datetime()
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
     * AmazonPayment情報取得設定
     */
    public function as_get_amazon_user_info_detail()
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

    /**
     * 選択済みリストを設定
     *
     * @param array $_data          入力データ
     * @param array $_box_list_data ボックスデータ
     * @param array $_validErrors   エラーリスト
     *
     * @return array 選択済みリスト
     */
    private function _setSelectedBoxList(&$_data, &$_box_list_data, &$_validErrors)
    {
        $selectedList = [];

        // 選択ボックスタイプ別情報取得
        if ($_data['box_type'] == 'new') {
            $box_list = $this->InfoBox->getListForInbound();
        } else {
            $box_list = $this->InfoBox->getListForInboundOldBox();
        }

        // 選択ボックスの設定
        foreach ($_box_list_data[$_data['box_type']] as $box_id => $item) {
            if (isset($item['checkbox'])) {
                // 存在チェック
                $key_index = array_search($box_id, array_column($box_list, 'box_id'));
                if ($key_index !== false) {
                    $item['box_id']       = $box_list[$key_index]['box_id'];
                    $item['product_cd']   = $box_list[$key_index]['product_cd'];
                    $item['product_name'] = $box_list[$key_index]['product_name'];
                    $item['kit_cd']       = $box_list[$key_index]['kit_cd'];
                } else {
                    $_validErrors['box'][0] = '対象データに不備がありました。';
                    break;
                }

                $boxModel = new InboundSelectedBox();
                $boxModel->set([$boxModel->getModelName() => $item]);
                if (!$boxModel->validates()) {
                    $_validErrors['box_list'][$_data['box_type']][$item['box_id']] = $boxModel->validationErrors;
                }

                $selectedList[] = InboundComponent::createBoxParam($item);
            } else {
                unset($_box_list_data[$_data['box_type']][$box_id]);
            }
        }
        $_data['box'] = implode(',', $selectedList);
    }

    /**
     * 配送情報を設定
     *
     * @param array  $_data        入力データ
     * @param array  $_validErrors エラーリスト
     *
     * @return array 選択済みリスト
     */
    private function _setDeliveryData(&$_data, &$_validErrors)
    {
        if (empty($_data['delivery_carrier'])) {
            $_validErrors['delivery_carrier'][0] = __d('validation', 'notBlank', __d('validation', 'inbound_delivery_carrier'));
        } else {
            $this->Inbound->init($_data);

            if ($_data['address_id'] !== 'add') {
                $_data = $this->Address->merge($_data['address_id'], $_data);
            } else {
                $_data['firstname_kana'] = '　';
                $_data['lastname_kana']  = '　';
            }

            $model = $this->Inbound->model($_data);
            if (empty($model)) {
                $this->Flash->set(__('empty_session_data'));
                return $this->redirect(['action' => 'add']);
            }
            if (!$model->validates()) {
                if (explode("_", $_data['delivery_carrier'])[0] == DELIVERY_ID_PICKUP && $_data['address_id'] != 'add') {
                    if (isset($model->validationErrors['tel1'])) {
                        unset($model->validationErrors['tel1']);
                        $model->validationErrors['address_id'] = '不正な形式の電話番号が設定されています。上の住所選択から「お届先を追加する」を選択し、再度集荷先の追加を入力ください。';
                    }
                }
                $_validErrors = array_merge($_validErrors, $model->validationErrors);
            }

            // 集荷依頼を頼んでいる場合
            if (explode("_", $_data['delivery_carrier'])[0] == DELIVERY_ID_PICKUP && $_data['address_id'] != 'add') {
                // 郵便番号チェック
                $this->loadModel('MtYmstpost');
                $res = $this->MtYmstpost->getPostal(['postal' => $_data["postal"]]);
                if ($res->status == 0 || count($res->results) == 0) {
                    CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));
                    CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' postal ' . print_r($_data['postal'], true));
                    $_validErrors['address_id'] = ['ヤマト運輸社で集荷申し込みできない郵便番号が設定されています。上の住所選択から「お届先を追加する」を選択し、再度集荷先の追加を入力ください。'];
                }
            }
        }
    }

    /**
     * 選択済みリストを設定
     *
     * @param array  $_data      データ
     * @param array  $_post_data POSTデータ
     *
     * @return void
     */
    private function _setCleaningPack(&$_data, $_post_data)
    {
        $replace_box_text = '';
        $arr_box_text = explode(',', $_data["box"]);
        foreach ($arr_box_text as $var) {
            $arr_seperate_colon = explode(':', $var);
            if ($arr_seperate_colon[0] == PRODUCT_CD_CLEANING_PACK) {
                $replace_box_text .= implode(':', $arr_seperate_colon) . ':' . $_post_data['keeping_type'] . ',';
            } else {
                $replace_box_text .= implode(':', $arr_seperate_colon) . ':,';
            }
        }
        $_data["box"] = rtrim($replace_box_text, ',');
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
     * 無料期限設定
     */
    private function _setFreeLimitDate(&$_box_info)
    {
        $_box_info['free_limit_date'] = '';
        $current_time = time();
        $limit_time   = strtotime($this->Common->getServiceFreeLimit($_box_info['order_date'], 'Y-m-d h:m:s'));
        $start_time   = strtotime(START_BOX_FREE);
        $order_time   = strtotime($_box_info['order_date']);

        // 有料購入プロダクト除去
        $paid_product_cd_list = [
            PRODUCT_CD_CLEANING_PACK,
            PRODUCT_CD_GIFT_CLEANING_PACK
        ];
        if (in_array($_box_info['product_cd'], $paid_product_cd_list)) {
            return;
        }

        // 購入日とサービス開始日時
        if ($start_time > $order_time) {
            return;
        }

        // 現在日時と無料期限
        if ($current_time > $limit_time) {
            return;
        }

        $_box_info['free_limit_date'] = $this->Common->getServiceFreeLimit($_box_info['order_date'], 'Y/m/d');
    }

    /**
     * 預け入れセッション情報を全削除
     */
    private function _cleanInboundBoxSession()
    {
        CakeSession::delete(self::MODEL_NAME_INBOUND_BASE);
        CakeSession::delete('box_list_data');
        CakeSession::delete('address_list');
        CakeSession::delete('item_excess_list');
    }
}
