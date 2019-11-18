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

        $this->set('address', $this->Address->get());
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
            return $this->redirect(['controller' => 'InboundBox', 'action' => 'input_amazon_pay']);
        }

        // card or bank user
        return $this->redirect(['controller' => 'InboundBox', 'action' => 'input']);
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

        /* 保持ボックスリスト */
        $holding_box_list['new'] = $this->InfoBox->getListForInbound();
        $holding_box_list['old'] = $this->InfoBox->getListForInboundOldBox();
        // 無料期限
        foreach ($holding_box_list as &$box_list) {
            foreach ($box_list as &$box_info) {
                $this->_setFreeLimitDate($box_info);
            }
        }
        $this->set('holding_box_list', $holding_box_list);
        CakeSession::write('holding_box_list', $holding_box_list);

        if ($this->request->is('get')) {
            $inbound_base = CakeSession::read('inbound_base_data');
            if (empty($inbound_base)) {
                // 初回表示時は「新しく取り寄せしたボックス」を選択
                $inbound_base['box_type'] = 'new';
            }

            $select_box_list_data = CakeSession::read('select_box_list_data');

            $this->request->data['InboundBase'] = $inbound_base;
            $this->request->data['BoxList'][$inbound_base['box_type']] = $select_box_list_data;

        } elseif ($this->request->is('post')) {
            $validErrors = [];

            /* 預け入れデータ */
            $inbound_base_data = $this->request->data['InboundBase'];
            CakeSession::write('inbound_base_data', $inbound_base_data);

            $box_type = $inbound_base_data['box_type'];

            /* 選択ボックス */
            $select_box_list_data = [];
            if (isset($this->request->data['BoxList']) && !empty($this->request->data['BoxList'][$box_type])) {
                $tmp_select_box_list_data = CakeSession::read('select_box_list_data');
                $post_select_box_list_data = $this->request->data['BoxList'][$box_type];

                $target_box_list = [];
                $box_use_flag = [
                    PRODUCT_CD_MONO          => false,
                    PRODUCT_CD_HAKO          => false,
                    PRODUCT_CD_CLEANING_PACK => false,
                    PRODUCT_CD_LIBRARY       => false,
                    PRODUCT_CD_CLOSET        => false,
                ];
                $select_box_id_list = array_keys($post_select_box_list_data);
                foreach ($select_box_id_list as $box_id) {
                    $key_index = array_search($box_id, array_column($holding_box_list[$box_type], 'box_id'));
                    if ($key_index === false) {
                        $this->Flash->validation('対象データに不備がありました。', ['key' => 'data_error']);
                        $this->redirect(['controller' => 'InboundBox', 'action' => 'add']);
                    } else {
                        $box_use_flag[$holding_box_list[$box_type][$key_index]['product_cd']] = true;
                        $target_box_list[] = $holding_box_list[$box_type][$key_index];
                        if (isset($tmp_select_box_list_data[$box_id])) {
                            $select_box_list_data[$box_id] = $tmp_select_box_list_data[$box_id];
                        } else {
                            $select_box_list_data[$box_id] = $post_select_box_list_data[$box_id];
                        }
                    }
                }
                CakeSession::write('box_use_flag', $box_use_flag);
                CakeSession::write('target_box_list', $target_box_list);

            } else {
                $validErrors['BoxList'][$box_type]['box'][0] = 'ボックスが選択されていません';
            }

            CakeSession::write('select_box_list_data', $select_box_list_data);

            // validation
            if (!empty($validErrors)) {
                $this->set('validErrors', $validErrors);
                return $this->render('input');
            }

            return $this->redirect(['controller' => 'InboundBox', 'action' => 'attention']);
        }
    }

    /*
     * ボックス情報入力
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

        // 預け入れ情報
        $inbound_base_data = CakeSession::read('inbound_base_data');
        // 選択ボックス設定情報
        $select_box_list_data = CakeSession::read('select_box_list_data');
        // 保持ボックスリスト
        $holding_box_list = CakeSession::read('holding_box_list');

        if ($this->request->is('get')) {

            // 住所一覧
            $address_list = $this->Address->get();
            $set_address_list = [];
            if (is_array($address_list)) {
                foreach ($address_list as $address) {
                    $name  = h("〒{$address['postal']}");
                    $name .= h(" {$address['pref']}{$address['address1']}{$address['address2']}{$address['address3']}");
                    $name .= h("　{$address['lastname']}　{$address['firstname']}");
                    $set_address_list[] = [
                        'name' => $name,
                        'value' => $address['address_id'],
                        'data-address-name' => $address['lastname'] . $address['firstname']
                    ];
                }
                // 新規住所追加用
                $set_address_list[] = [
                    'name' => 'お届先を追加する',
                    'value' => 'add',
                    'data-address-name' => ''
                ];
            }
            CakeSession::write('address_list', $set_address_list);

            // set data
            $this->set('inbound_base_data', $inbound_base_data);
            $this->set('box_use_flag', CakeSession::read('box_use_flag'));
            $this->set('address_list', CakeSession::read('address_list'));
            $this->set('target_box_list', CakeSession::read('target_box_list'));

            $this->request->data['InboundBase'] = $inbound_base_data;
            $this->request->data['BoxList'][$inbound_base_data['box_type']] = $select_box_list_data;

        } elseif ($this->request->is('post')) {
            $validErrors = [];

            // 預け入れ情報
            $inbound_base_data = array_merge($inbound_base_data, $this->request->data['InboundBase']);

            // ボックス情報
            $select_box_list_data = $this->request->data['BoxList'][$inbound_base_data['box_type']];
            $this->_setSelectedBoxList($inbound_base_data, $select_box_list_data, $holding_box_list[$inbound_base_data['box_type']]);
            foreach ($select_box_list_data as $box_id => $box_data) {
                $boxModel = new InboundSelectedBox();
                $boxModel->set([$boxModel->getModelName() => $box_data]);
                if (!$boxModel->validates()) {
                    $validErrors['BoxList'][$inbound_base_data['box_type']][$box_id] = $boxModel->validationErrors;
                }
            }

            // 配送関連情報
            $this->_setDeliveryData($inbound_base_data, $validErrors);

            CakeSession::write('inbound_base_data', $inbound_base_data);
            CakeSession::write('select_box_list_data', $select_box_list_data);

            // validation
            if (!empty($validErrors)) {
                $this->set('validErrors', $validErrors);
                $this->set('address_list', CakeSession::read('address_list'));
                $this->set('inbound_base_data', $inbound_base_data);
                $this->set('target_box_list', CakeSession::read('target_box_list'));
                $this->set('box_use_flag', CakeSession::read('box_use_flag'));

                return $this->render('attention');
            }
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
            'InboundBox/attention',
            'InboundBox/confirm',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'inbound_box', 'action' => 'add']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $inbound_base_data = CakeSession::read('inbound_base_data');

        // set data
        $this->Inbound->init($inbound_base_data);
        $this->set('dateList', $this->Inbound->date());
        $this->set('timeList', $this->Inbound->time());
        $this->set('inbound_base_data', $inbound_base_data);
        $this->set('target_box_list', CakeSession::read('target_box_list'));
        $this->set('select_box_list_data', CakeSession::read('select_box_list_data'));
        $this->set('box_use_flag', CakeSession::read('box_use_flag'));
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

        $inbound_base_data = CakeSession::read('inbound_base_data');
        $select_box_list_data = CakeSession::read('select_box_list_data');

        /* 存在チェック */
        $select_box_id_list = array_keys($select_box_list_data);
        $box_type = $inbound_base_data['box_type'];
        $holding_box_list['new'] = $this->InfoBox->getListForInbound();
        $holding_box_list['old'] = $this->InfoBox->getListForInboundOldBox();
        foreach ($select_box_id_list as $box_id) {
            $key_index = array_search($box_id, array_column($holding_box_list[$box_type], 'box_id'));
            if ($key_index === false) {
                $this->Flash->validation('ボックス情報に不備があったために失敗しました。お手数ですが再度実施のほどよろしくお願いいたします。', ['key' => 'data_error']);
                $this->redirect(['action' => 'add']);
            }
        }

        // insert address
        if ($inbound_base_data['address_id'] == 'add' && isset($inbound_base_data['resister'])) {
            $this->_postCustomerAddress($inbound_base_data, 'input_card');
        }

        // cleaning_pack
        $post_data = $this->request->data;
        if (isset($post_data['InboundBase'])) {
            $this->_setCleaningPack($inbound_base_data, $post_data['InboundBase']);
        }

        $this->Inbound->init($inbound_base_data);
        $model = $this->Inbound->model($inbound_base_data);
        if (!empty($model) && $model->validates()) {
            // api
            $res = $model->apiPost($model->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->validation('ボックスの預け入れ処理に失敗しました。お手数ですが再度実施のほどよろしくお願いいたします。', ['key' => 'data_error']);
                return $this->redirect(['action' => 'add']);
            }
        } else {
            $this->Flash->validation('ボックスの預け入れ処理に失敗しました。お手数ですが再度実施のほどよろしくお願いいたします。', ['key' => 'data_error']);
            return $this->redirect(['action' => 'add']);
        }
        $this->_cleanInboundBoxSession();
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

        /* 保持ボックスリスト */
        $holding_box_list['new'] = $this->InfoBox->getListForInbound();
        $holding_box_list['old'] = $this->InfoBox->getListForInboundOldBox();
        // 無料期限
        foreach ($holding_box_list as &$box_list) {
            foreach ($box_list as &$box_info) {
                $this->_setFreeLimitDate($box_info);
            }
        }
        $this->set('holding_box_list', $holding_box_list);
        CakeSession::write('holding_box_list', $holding_box_list);

        if ($this->request->is('get')) {

            $inbound_base = CakeSession::read('inbound_base_data');
            if (empty($inbound_base)) {
                // 初回表示時は「新しく取り寄せしたボックス」を選択
                $inbound_base['box_type'] = 'new';
            }

            $select_box_list_data = CakeSession::read('select_box_list_data');

            $this->request->data['InboundBase'] = $inbound_base;
            $this->request->data['BoxList'][$inbound_base['box_type']] = $select_box_list_data;

        } elseif ($this->request->is('post')) {
            $validErrors = [];

            /* 預け入れデータ */
            $inbound_base_data = $this->request->data['InboundBase'];
            CakeSession::write('inbound_base_data', $inbound_base_data);

            $box_type = $inbound_base_data['box_type'];

            /* 選択ボックス */
            $select_box_list_data = [];
            if (isset($this->request->data['BoxList']) && !empty($this->request->data['BoxList'][$box_type])) {
                $tmp_select_box_list_data = CakeSession::read('select_box_list_data');
                $post_select_box_list_data = $this->request->data['BoxList'][$box_type];

                $target_box_list = [];
                $box_use_flag = [
                    PRODUCT_CD_MONO          => false,
                    PRODUCT_CD_HAKO          => false,
                    PRODUCT_CD_CLEANING_PACK => false,
                    PRODUCT_CD_LIBRARY       => false,
                    PRODUCT_CD_CLOSET        => false,
                ];
                $select_box_id_list = array_keys($post_select_box_list_data);
                foreach ($select_box_id_list as $box_id) {
                    $key_index = array_search($box_id, array_column($holding_box_list[$box_type], 'box_id'));
                    if ($key_index === false) {
                        $this->Flash->validation('対象データに不備がありました。', ['key' => 'data_error']);
                        $this->redirect(['controller' => 'InboundBox', 'action' => 'add']);
                    } else {
                        $box_use_flag[$holding_box_list[$box_type][$key_index]['product_cd']] = true;
                        $target_box_list[] = $holding_box_list[$box_type][$key_index];
                        if (isset($tmp_select_box_list_data[$box_id])) {
                            $select_box_list_data[$box_id] = $tmp_select_box_list_data[$box_id];
                        } else {
                            $select_box_list_data[$box_id] = $post_select_box_list_data[$box_id];
                        }
                    }
                }
                CakeSession::write('box_use_flag', $box_use_flag);
                CakeSession::write('target_box_list', $target_box_list);

            } else {
                $validErrors['BoxList'][$box_type]['box'][0] = 'ボックスが選択されていません';
            }

            CakeSession::write('select_box_list_data', $select_box_list_data);

            // validation
            if (!empty($validErrors)) {
                $this->set('validErrors', $validErrors);
                return $this->render('input');
            }

            return $this->redirect(['controller' => 'InboundBox', 'action' => 'attention_amazon_pay']);
        }
    }

    /*
     * ボックス超過確認
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

        // 預け入れ情報
        $inbound_base_data = CakeSession::read('inbound_base_data');
        // 選択ボックス設定情報
        $select_box_list_data = CakeSession::read('select_box_list_data');
        // 保持ボックスリスト
        $holding_box_list = CakeSession::read('holding_box_list');

        if ($this->request->is('get')) {

            // set data
            $this->set('inbound_base_data', $inbound_base_data);
            $this->set('box_use_flag', CakeSession::read('box_use_flag'));
            $this->set('address_list', CakeSession::read('address_list'));
            $this->set('target_box_list', CakeSession::read('target_box_list'));

            $this->request->data['InboundBase'] = $inbound_base_data;
            $this->request->data['BoxList'][$inbound_base_data['box_type']] = $select_box_list_data;

        } elseif ($this->request->is('post')) {
            $validErrors = [];

            // 預け入れ情報
            $inbound_base_data = array_merge($inbound_base_data, $this->request->data['InboundBase']);

            // ボックス情報
            $select_box_list_data = $this->request->data['BoxList'][$inbound_base_data['box_type']];
            $this->_setSelectedBoxList($inbound_base_data, $select_box_list_data, $holding_box_list[$inbound_base_data['box_type']]);
            foreach ($select_box_list_data as $box_id => $box_data) {
                $boxModel = new InboundSelectedBox();
                $boxModel->set([$boxModel->getModelName() => $box_data]);
                if (!$boxModel->validates()) {
                    $validErrors['BoxList'][$inbound_base_data['box_type']][$box_id] = $boxModel->validationErrors;
                }
            }

            /** データ整形 */
            // Amazonより取得した個人情報よりデータ整形
            $this->_setAmazonCustomerData($inbound_base_data);
            $inbound_base_data['address_id'] = 'add';
            // 配送関連情報
            $this->_setDeliveryData($inbound_base_data, $validErrors);

            CakeSession::write('inbound_base_data', $inbound_base_data);
            CakeSession::write('select_box_list_data', $select_box_list_data);

            // validation
            if (!empty($validErrors)) {
                $this->set('validErrors', $validErrors);
                $this->set('inbound_base_data', $inbound_base_data);
                $this->set('target_box_list', CakeSession::read('target_box_list'));
                $this->set('box_use_flag', CakeSession::read('box_use_flag'));

                return $this->render('attention_amazon_pay');
            }

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

        $inbound_base_data = CakeSession::read('inbound_base_data');

        // set data
        $this->Inbound->init($inbound_base_data);
        $this->set('dateList', $this->Inbound->date());
        $this->set('timeList', $this->Inbound->time());
        $this->set('inbound_base_data', $inbound_base_data);
        $this->set('target_box_list', CakeSession::read('target_box_list'));
        $this->set('select_box_list_data', CakeSession::read('select_box_list_data'));
        $this->set('box_use_flag', CakeSession::read('box_use_flag'));
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

        $inbound_base_data = CakeSession::read('inbound_base_data');
        $select_box_list_data = CakeSession::read('select_box_list_data');

        /* 存在チェック */
        $select_box_id_list = array_keys($select_box_list_data);
        $box_type = $inbound_base_data['box_type'];
        $holding_box_list['new'] = $this->InfoBox->getListForInbound();
        $holding_box_list['old'] = $this->InfoBox->getListForInboundOldBox();
        foreach ($select_box_id_list as $box_id) {
            $key_index = array_search($box_id, array_column($holding_box_list[$box_type], 'box_id'));
            if ($key_index === false) {
                $this->Flash->validation('ボックス情報に不備があったために失敗しました。お手数ですが再度実施のほどよろしくお願いいたします。', ['key' => 'data_error']);
                $this->redirect(['action' => 'add']);
            }
        }

        // cleaning_pack
        $post_data = $this->request->data;
        if (isset($post_data['InboundBase'])) {
            $this->_setCleaningPack($inbound_base_data, $post_data['InboundBase']);
        }

        $this->Inbound->init($inbound_base_data);
        $model = $this->Inbound->model($inbound_base_data);
        if (!empty($model) && $model->validates()) {
            // api
            $res = $model->apiPost($model->toArray());
            if (!empty($res->error_message)) {
                $this->Flash->validation('ボックスの預け入れ処理に失敗しました。お手数ですが再度実施のほどよろしくお願いいたします。', ['key' => 'data_error']);
                return $this->redirect(['action' => 'add']);
            }
        } else {
            $this->Flash->validation('ボックスの預け入れ処理に失敗しました。お手数ですが再度実施のほどよろしくお願いいたします。', ['key' => 'data_error']);
            return $this->redirect(['action' => 'add']);
        }
        $this->_cleanInboundBoxSession();
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
     * @param array $_data             入力データ
     * @param array $_box_list_data    選択ボックスデータ
     * @param array $_holding_box_list 保持ボックスデータ
     *
     * @return array 選択済みリスト
     */
    private function _setSelectedBoxList(&$_data, &$_box_list_data, $_holding_box_list)
    {
        $selectedList = [];

        // 選択ボックスの設定
        foreach ($_box_list_data as $box_id => $item) {
            $key_index = array_search($box_id, array_column($_holding_box_list, 'box_id'));
            if ($key_index !== false) {
                if (($_holding_box_list[$key_index]['kit_cd'] == null || $_holding_box_list[$key_index]['kit_cd'] == "") && $_holding_box_list[$key_index]['product_cd'] == PRODUCT_CD_HAKO) {
                    $_holding_box_list[$key_index]['kit_cd'] = KIT_CD_HAKO;
                    $_holding_box_list[$key_index]['product_cd'] = PRODUCT_CD_HAKO;
                }
                $item['box_id']       = $_holding_box_list[$key_index]['box_id'];
                $item['product_cd']   = $_holding_box_list[$key_index]['product_cd'];
                $item['product_name'] = $_holding_box_list[$key_index]['product_name'];
                $item['kit_cd']       = $_holding_box_list[$key_index]['kit_cd'];
                $selectedList[] = InboundComponent::createBoxParam($item);
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
            $_validErrors['InboundBase']['delivery_carrier'][0] = __d('validation', 'notBlank', __d('validation', 'inbound_delivery_carrier'));
        } else {
            $this->Inbound->init($_data);

            if ($_data['address_id'] !== 'add') {
                $_data = $this->Address->merge($_data['address_id'], $_data);
            } else {
                $_data['firstname_kana'] = '　';
                $_data['lastname_kana']  = '　';
            }

            $datetime_list = explode('-', $_data['datetime_cd']);
            $_data['day_cd']  = $datetime_list[0] . '-' . $datetime_list[1] . '-' . $datetime_list[2];
            $_data['time_cd'] = $datetime_list[3];

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
                if (!isset($_validErrors['InboundBase'])) {
                    $_validErrors['InboundBase'] = [];
                }
                $_validErrors['InboundBase'] = array_merge($_validErrors['InboundBase'], $model->validationErrors);
            }

            // 集荷依頼を頼んでいる場合
            if (explode("_", $_data['delivery_carrier'])[0] == DELIVERY_ID_PICKUP && $_data['address_id'] != 'add') {
                // 郵便番号チェック
                $this->loadModel('MtYmstpost');
                $res = $this->MtYmstpost->getPostal(['postal' => $_data["postal"]]);
                if ($res->status == 0 || count($res->results) == 0) {
                    CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' res ' . print_r($res, true));
                    CakeLog::write(ERROR_LOG, $this->name . '::' . $this->action . ' postal ' . print_r($_data['postal'], true));
                    $_validErrors['InboundBase']['address_id'] = ['ヤマト運輸社で集荷申し込みできない郵便番号が設定されています。上の住所選択から「お届先を追加する」を選択し、再度集荷先の追加を入力ください。'];
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
        CakeSession::delete('box_use_flag');
        CakeSession::delete('address_list');
        CakeSession::delete('target_box_list');
        CakeSession::delete('holding_box_list');
        CakeSession::delete('inbound_base_data');
        CakeSession::delete('select_box_list_data');
    }
}
