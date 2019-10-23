<?php

App::uses('MinikuraController', 'Controller');
App::uses('AppValid', 'Lib');
App::uses('InfoBox', 'Model');
App::uses('InfoItem', 'Model');
App::uses('PickupYamato', 'Model');
App::uses('InboundAndOutboundHistory', 'Model');

class OutboundHistoryController extends MinikuraController
{
    const MODEL_NAME_OUTBOUND_HISTORY = 'InboundAndOutboundHistory';

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

        $outbound_history_list = [];

        // 取り出し履歴取得
        $api_param['works_type'] = '003';

        // 検索時
        if ($this->request->is('post')) {
            $data = $this->request->data[self::MODEL_NAME_OUTBOUND_HISTORY];
            $api_param['keyword'] = $data['keyword'];
        }

        $result = $this->InboundAndOutboundHistory->apiGet($api_param);
        if ($result->isSuccess()) {
            $outbound_history_list = $result->results;
        }

        // paginate
        $list = $this->paginate(self::MODEL_NAME_OUTBOUND_HISTORY, $outbound_history_list);
        $this->set('outbound_history_list', $list);

        CakeSession::write(self::MODEL_NAME_OUTBOUND_HISTORY, $outbound_history_list);
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
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'inbound_history', 'action' => 'index']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

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
        $outbound_history_list = CakeSession::read(self::MODEL_NAME_OUTBOUND_HISTORY);

        // 入庫履歴から対象のbox_idを抽出
        $keyIndex = array_search($selected_announcement_id, array_column($outbound_history_list, 'announcement_id'));
        $target_outbound_data = $outbound_history_list[$keyIndex];
        $box_id_list = explode(',', $target_outbound_data['box_ids']);

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
     * セッションを削除
     */
    private function _cleanOutboundSession()
    {
        CakeSession::delete(self::MODEL_NAME_OUTBOUND_HISTORY);
        CakeSession::delete('selected_announcement_id');
        CakeSession::delete('box_list');
    }
}
