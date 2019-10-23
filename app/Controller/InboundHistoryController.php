<?php

App::uses('AppValid', 'Lib');
App::uses('MinikuraController', 'Controller');
App::uses('InfoBox', 'Model');
App::uses('PickupYamato', 'Model');
App::uses('V5Box', 'Model');
App::uses('InboundAndOutboundHistory', 'Model');
App::uses('Announcement', 'Model');


class InboundHistoryController extends MinikuraController
{
    const MODEL_NAME_INBOUND_HISTORY = 'InboundAndOutboundHistory';
    const MODEL_NAME_V5_BOX = 'V5Box';

    public $layout = 'style';

    protected $paginate = array(
        'limit' => 10,
        'paramType' => 'querystring'
    );

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
        return !$this->Customer->canInbound();
    }

    /**
     * 預け入れ履歴一覧
     */
    public function index()
    {
        // session delete
        $allow_action_list = [
            'InboundHistory/index',
            'InboundHistory/detail',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->_cleanInboundSession();
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_INBOUND_HISTORY);

        $inbound_history_list = [];

        // 預け入れ履歴取得
        $api_param = [
            'works_type' => '001,002'
        ];
        $result = $this->InboundAndOutboundHistory->apiGet($api_param);
        if ($result->isSuccess()) {
            $inbound_history_list = $result->results;
        }

        // paginate
        $list = $this->paginate(self::MODEL_NAME_INBOUND_HISTORY, $inbound_history_list);
        $this->set('inbound_history_list', $list);

        CakeSession::write(self::MODEL_NAME_INBOUND_HISTORY, $inbound_history_list);
    }

    /**
     * 預け入れ履歴詳細
     */
    public function detail()
    {
        // session delete
        $allow_action_list = [
            'InboundHistory/index',
            'InboundHistory/detail',
            'InboundHistory/change',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'inbound_history', 'action' => 'index']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        CakeSession::delete('selected_box_data');

        // box情報を全件取得
        $InfoBox = new InfoBox();
        $limit = 1000;
        $offset = 0;
        $box_results = [];
        do {
            $param = [
                'limit'  => $limit,
                'offset' => $offset,
            ];
            $tmp_box_results = $InfoBox->apiGetResults($param);
            $box_results = array_merge($box_results, $tmp_box_results);

            $offset += $limit;
        } while (count($tmp_box_results) >= $limit);

        // 選択したお知らせIDを取得
        $selected_announcement_id = $this->request->query('announcement_id');
        CakeSession::write('selected_announcement_id', $selected_announcement_id);

        // 入庫履歴リスト
        $inbound_history_list = CakeSession::read(self::MODEL_NAME_INBOUND_HISTORY);

        // 入庫履歴から対象のbox_idを抽出
        $keyIndex = array_search($selected_announcement_id, array_column($inbound_history_list, 'announcement_id'));
        $target_inbound_data = $inbound_history_list[$keyIndex];

        // 該当するbox情報を抽出
        $box_id_list = explode(',', $target_inbound_data['box_ids']);
        // 該当するbox情報を抽出
        $box_list = [];
        foreach ($box_id_list as $box_id) {
            $keyIndex = array_search($box_id, array_column($box_results, 'box_id'));
            $box_list[] = $box_results[$keyIndex];
        }
        CakeSession::write('box_list', $box_list);

        // お知らせカテゴリー取得
        $announcement_data = [];
        $announcement = new Announcement();
        $res = $announcement->apiGetResultsFind([], ['announcement_id' => $target_inbound_data['announcement_id']]);

        if (!empty($res)) {
            $announcement_data =$res;
        }

        // 集荷情報
        $pickup_data = [];
        $pickup_yamato = new PickupYamato();
        $res = $pickup_yamato->apiGet([
            'announcement_id' => $target_inbound_data['announcement_id'],
        ]);
        if (!empty($res->results)) {
            $pickup_data =$res->results[0];
            $pickup_yamato_change = $this->Common->pickupYamatoChangeFlag($res);
            $this->set('pickup_yamato_change', $pickup_yamato_change);
        }

        $this->set('inbound_data', $target_inbound_data);
        $this->set('box_list', $box_list);
        $this->set('pickup_data', $pickup_data);
        $this->set('announcement_data', $announcement_data);
    }

    /**
     * 預け入れ項目変更
     */
    public function edit()
    {
        // session delete
        $allow_action_list = [
            'InboundHistory/detail',
            'InboundHistory/edit',
            'InboundHistory/confirm',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'inbound_history', 'action' => 'index']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_V5_BOX);

        // 編集画面
        if ($this->request->is('get')) {

            $this->request->data[self::MODEL_NAME_V5_BOX] = CakeSession::read(self::MODEL_NAME_V5_BOX);

            // 選択したbox_idを取得
            $selected_box_id = $this->request->query('box_id');
            CakeSession::write('selected_box_id', $selected_box_id);

            // 該当するボックス情報を取得
            $box = CakeSession::read('selected_box_data');
            if (empty($box)) {
                $box_list = CakeSession::read('box_list');
                $keyIndex = array_search($selected_box_id, array_column($box_list, 'box_id'));
                $box = $box_list[$keyIndex];
                CakeSession::write('selected_box_data', $box);

                $this->request->data[self::MODEL_NAME_V5_BOX]['box_name'] = $box['box_name'];
                $this->request->data[self::MODEL_NAME_V5_BOX]['wrapping_type'] = $box['wrapping_type'];
                $this->request->data[self::MODEL_NAME_V5_BOX]['keeping_type'] = $box['keeping_type'];
            }

            $this->set('box', $box);
            $this->V5Box->set($this->request->data);
        }

        // 確認画面
        if ($this->request->is('post')) {

            $data = $this->request->data[self::MODEL_NAME_V5_BOX];

            $this->V5Box->set($data);
            if (!$this->V5Box->validates()) {
                $this->set('box', CakeSession::read('selected_box_data'));
                return $this->render('edit');
            }

            $box = CakeSession::read('selected_box_data');
            $box['box_name']      = $data['box_name'];
            $box['wrapping_type'] = $data['wrapping_type'];
            $box['keeping_type']  = $data['keeping_type'];

            CakeSession::write('selected_box_data', $box);
            CakeSession::write(self::MODEL_NAME_V5_BOX, $data);

            return $this->redirect(['controller' => 'inbound_history', 'action' => 'confirm']);
        }
    }

    /**
     * 預け入れ項目変更内容確認
     */
    public function confirm()
    {
        // session delete
        $allow_action_list = [
            'InboundHistory/edit',
            'InboundHistory/confirm',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'inbound_history', 'action' => 'index']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // 該当するボックス情報
        $this->set('box', CakeSession::read('selected_box_data'));
    }

    /**
     * 預け入れ項目変更完了
     */
    public function complete()
    {
        // session delete
        $allow_action_list = [
            'InboundHistory/confirm',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'inbound_history', 'action' => 'index']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_V5_BOX);

        $this->request->data[self::MODEL_NAME_V5_BOX] = CakeSession::read(self::MODEL_NAME_V5_BOX);

        // 更新処理
        $this->V5Box->set($this->request->data);
        $res = $this->V5Box->apiPatch($this->V5Box->toArray());
        if (!empty($res->error_message)) {
            $this->Flash->set($res->error_message);
            return $this->redirect(['controller' => 'inbound_history', 'action' => 'edit']);
        }
    }

    /**
     * セッションを削除
     */
    private function _cleanInboundSession()
    {
        CakeSession::delete(self::MODEL_NAME_INBOUND_HISTORY);
        CakeSession::delete('selected_announcement_id');
        CakeSession::delete('box_list');
        CakeSession::delete('selected_box_id');
    }
}
