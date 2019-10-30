<?php

App::uses('MinikuraController', 'Controller');
App::uses('AppValid', 'Lib');
App::uses('InfoBox', 'Model');
App::uses('InfoItem', 'Model');
App::uses('PickupYamato', 'Model');
App::uses('InboundAndOutboundHistory', 'Model');
App::uses('OutboundCancel', 'Model');

class OutboundHistoryController extends MinikuraController
{
    const MODEL_NAME_OUTBOUND_HISTORY = 'InboundAndOutboundHistory';
    const MODEL_NAME_OUTBOUND_CANCEL  = 'OutboundCancel';

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
        return !$this->Customer->canOutbound();
    }

    /**
     * 取り出し履歴一覧
     */
    public function index()
    {
        // session delete
        $allow_action_list = [
            'OutboundHistory/index',
            'OutboundHistory/detail',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->_cleanOutboundSession();
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_OUTBOUND_HISTORY);

        $data = [];

        // 検索時
        if ($this->request->is('post')) {
            $data = $this->request->data[self::MODEL_NAME_OUTBOUND_HISTORY];
            CakeSession::write(self::MODEL_NAME_OUTBOUND_HISTORY, $data);
        }

        /** 検索情報 **/
        $search_options = $this->_getSearchOptions($data);

        /** 取り出し履歴取得 **/
        $outbound_history_list = $this->_getOutboundHistory($search_options);

        // paginate
        $list = $this->paginate(self::MODEL_NAME_OUTBOUND_HISTORY, $outbound_history_list);
        $this->set('list', $list);

        $this->request->data[self::MODEL_NAME_OUTBOUND_HISTORY] = CakeSession::read(self::MODEL_NAME_OUTBOUND_HISTORY);
        $this->InboundAndOutboundHistory->set($this->request->data);
    }

    /**
     * 取り出し履歴詳細
     */
    public function detail()
    {
        // session delete
        $allow_action_list = [
            'OutboundHistory/index',
            'OutboundHistory/detail',
            'OutboundHistory/cancel_confirm',
            'OutboundHistory/cancel_complete',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'outbound_history', 'action' => 'index']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_OUTBOUND_HISTORY);

        // 選択したお知らせIDを取得
        $selected_announcement_id = $this->request->query('announcement_id');

        // 取り出しキャンセル対象情報取得
        $search_options = [];
        $outbound_history_list = $this->_getOutboundHistory($search_options, $selected_announcement_id);
        if (count($outbound_history_list) !== 1) {
            $this->Flash->validation('該当するデータの取得に失敗しました。', ['key' => 'data_error']);
            return $this->redirect('/outbound_history');
        }
        $target_outbound_data = $outbound_history_list[0];

        // 出庫履歴から対象のbox_idを抽出
        $box_id_list = explode(',', $target_outbound_data['box_ids']);

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

        // 該当するbox情報を抽出
        $box_list = [];
        foreach ($box_id_list as $box_id) {
            $keyIndex = array_search($box_id, array_column($box_results, 'box_id'));
            $box_list[] = $box_results[$keyIndex];
        }
        CakeSession::write('box_list', $box_list);

        // 該当するアイテム情報を抽出
        $item_id_list = [];
        if (isset($target_outbound_data['item_ids'])) {
            $tmp_item_id_list = explode(',' , $target_outbound_data['item_ids']);
            foreach ($box_id_list as $box_id) {
                foreach ($tmp_item_id_list as $item_id) {
                    if (substr($item_id, 0, 7) == $box_id) {
                        $item_id_list[$box_id][] = $item_id;
                    }
                }
            }
            $InfoItem = new InfoItem();
            foreach ($box_list as &$box) {
                if ($box['product_cd'] !== PRODUCT_CD_HAKO) {
                    $param = [
                        'box_id' => $box['box_id'],
                    ];
                    $item_results = $InfoItem->apiGetResults($param);
                    foreach ($item_id_list[$box['box_id']] as $item_id) {
                        $keyIndex = array_search($item_id, array_column($item_results, 'item_id'));
                        $target_item_data = $item_results[$keyIndex];
                        $box['item_data'][] = [
                            'item_id'     =>$target_item_data['item_id'],
                            'item_name'   =>$target_item_data['item_name'],
                            'image_first' =>$target_item_data['image_first'],
                        ];
                    }
                }
            }
        }

        $this->set('outbound_data', $target_outbound_data);
        $this->set('box_list', $box_list);
    }

    /**
     * 取り出しキャンセル確認
     */
    public function cancel_confirm()
    {
        // session delete
        $allow_action_list = [
            'OutboundHistory/detail',
            'OutboundHistory/cancel_confirm',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'outbound_history', 'action' => 'index']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        // 削除対象のお知らせID
        $cancel_announcement_id = $this->request->query('announcement_id');
        CakeSession::write('cancel_announcement_id', $cancel_announcement_id);
        $this->set('cancel_announcement_id', $cancel_announcement_id);
    }

    /**
     * 取り出しキャンセル完了
     */
    public function cancel_complete()
    {
        // session delete
        $allow_action_list = [
            'OutboundHistory/cancel_confirm',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'outbound_history', 'action' => 'index']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $this->loadModel(self::MODEL_NAME_OUTBOUND_HISTORY);
        $this->loadModel(self::MODEL_NAME_OUTBOUND_CANCEL);

        // 削除対象のお知らせID
        $cancel_announcement_id = CakeSession::read('cancel_announcement_id');

        // 取り出しキャンセル対象情報取得
        $search_options = [];
        $outbound_history_list = $this->_getOutboundHistory($search_options, $cancel_announcement_id);
        if (count($outbound_history_list) !== 1) {
            $this->Flash->validation('キャンセル対象のデータに不備があったために失敗しました。', ['key' => 'data_error']);
            return $this->redirect(['controller' => 'outbound_history', 'action' => "detail?announcement_id='{$cancel_announcement_id}'"]);
        }

        // キャンセル処理
        $this->request->data[self::MODEL_NAME_OUTBOUND_CANCEL] = [
            'work_linkage_id' => $outbound_history_list[0]['works_linkage_id'],
        ];
        $this->OutboundCancel->set($this->request->data);
        $res = $this->OutboundCancel->apiPatch($this->OutboundCancel->toArray());
        if (!empty($res->error_message)) {
            $this->Flash->set($res->error_message);
            return $this->redirect(['controller' => 'outbound_history', 'action' => "detail?announcement_id='{$cancel_announcement_id}'"]);
        }

        $this->set('cancel_announcement_id', $cancel_announcement_id);
    }

    /*
     * 検索条件を取得
     *
     * @param array $_data POST情報
     *
     * @return array 検索条件
     */
    private function _getSearchOptions($_data)
    {
        $search_options = CakeSession::read('app.data.session_order_history_search');

        if (!empty($_data) && isset($_data['keyword'])) {
            $search_options = [
                "keyword"    => $_data['keyword'],
            ];
            CakeSession::write('app.data.session_order_history_search', $search_options);
        }

        return $search_options;
    }

    /*
     * 取り出し履歴情報を取得
     *
     * @param array  $_search_param    検索条件
     * @param string $_announcement_id お知らせID（複数の場合はカンマ区切り）
     *
     * @return array 絞り込み後の取出し履歴情報
     */
    private function _getOutboundHistory($_search_options = [], $_announcement_id = '')
    {
        // 取り出し履歴取得
        $api_param['works_type'] = '003';
        if (!empty($_announcement_id)) {
            $api_param['announcement_id'] = $_announcement_id;
        }
        $result = $this->InboundAndOutboundHistory->apiGet($api_param);
        if ($result->isSuccess()) {
            $outbound_history_list = $result->results;
        }

        $outbound_history_list = $this->InboundAndOutboundHistory->searchTerm($outbound_history_list, $_search_options, false);

        return $outbound_history_list;
    }

    /**
     * セッションを削除
     */
    private function _cleanOutboundSession()
    {
        CakeSession::delete(self::MODEL_NAME_OUTBOUND_HISTORY);
        CakeSession::delete('app.data.session_order_history_search');
        CakeSession::delete('cancel_announcement_id');
        CakeSession::delete('box_list');
    }
}
