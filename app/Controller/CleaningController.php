<?php

App::uses('MinikuraController', 'Controller');

App::uses('InfoItem', 'Model');

/**
 * minikuraCLEANING+
 */
class CleaningController extends MinikuraController
{
    const MODEL_NAME_CLEANING      = 'Cleaning';
    const MODEL_NAME_INFO_ITEM     = 'InfoItem';
    const MODEL_NAME_POINT_BALANCE = 'PointBalance';
    const MODEL_NAME_POINT_USE     = 'PointUse';

    const DEFAULTS_SORT_KEY = [
        'box.product_cd' => true,
        'box.kit_cd' => true,
        'box.box_id' => true,
        'box.box_name' => true,
        'item_id' => true,
        'item_name' => true,
        'item_status' => true,
        'item_group_cd' => true,
    ];

    /** layout */
    public $layout = 'style';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();

        (new InfoItem())->deleteCache();

        $this->loadModel(self::MODEL_NAME_CLEANING);
        $this->loadModel(self::MODEL_NAME_INFO_ITEM);
        $this->loadModel(self::MODEL_NAME_POINT_USE);
        $this->loadModel(self::MODEL_NAME_POINT_BALANCE);
    }

    /**
     * input
     */
    public function input()
    {
        // session delete
        $allow_action_list = [
            'Cleaning/add',
            'Cleaning/input',
            'Cleaning/confirm',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->_cleanSession();
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        if ($this->request->is('post')) {
            $cleaning_data = $this->request->data[self::MODEL_NAME_CLEANING];
            CakeSession::write(self::MODEL_NAME_CLEANING, $cleaning_data);
        } else {
            // アイテム詳細からの遷移
            if (isset($_GET['item_id'])) {
                $cleaning_data['selected_item_id_list'][$_GET['item_id']] = 1;
                CakeSession::write(self::MODEL_NAME_CLEANING, $cleaning_data);
            }
            $cleaning_data = CakeSession::read(self::MODEL_NAME_CLEANING);

            $this->request->data[self::MODEL_NAME_CLEANING] = $cleaning_data;
            $this->request->data[self::MODEL_NAME_POINT_USE] = CakeSession::read(self::MODEL_NAME_POINT_USE);
        }

        /** 検索情報 **/
        $search_options = $this->_getSearchOptions($cleaning_data);

        /** 商品取得 **/
        $item_list = $this->_getItem($search_options);

        /** 選択済み情報 **/
        $selected_item_list = $this->_getSelectedItem($cleaning_data, $item_list);

        // 選択済みアイテムを優先項目として先頭へ配置
        $item_id_list = array_column($item_list, 'item_id');
        $selected_item_id_list = array_column($selected_item_list, 'item_id');
        foreach ($selected_item_id_list as $selected_item_id) {
            $target_index = array_search($selected_item_id, $item_id_list);
            unset($item_list[$target_index]);
        }
        $item_list = array_merge($selected_item_list, $item_list);

        // ポイント取得
        $res = $this->PointBalance->apiGet();
        if (!empty($res->error_message)) {
            $this->Flash->validation($res->error_message, ['key' => 'point_get']);
            return $this->redirect(['controller' => 'cleaning', 'action' => 'input']);
        } else {
            $point_balance = $res->results[0]['point_balance'];
        }

        $this->set('point_blance', $point_balance);
        $this->set('item_list', $item_list);
        $this->set('price', Configure::read('app.kit.cleaning.item_group_cd'));

        // 確認画面遷移時
        if ($this->request->is('post')) {
            if (!isset($cleaning_data['search'])) {

                $error_flag = false;

                // 小計
                $subtotal = 0;
                $price = Configure::read('app.kit.cleaning.item_group_cd');
                foreach ($selected_item_list as $item) {
                    $subtotal += $price[$item['item_group_cd']];
                }

                $point_data = $this->request->data[self::MODEL_NAME_POINT_USE];
                $this->PointUse->set($point_data);
                $this->PointUse->data[self::MODEL_NAME_POINT_USE]['point_balance'] = $point_balance;
                $this->PointUse->data[self::MODEL_NAME_POINT_USE]['subtotal'] = $subtotal;
                // validation
                if (!$this->PointUse->validates()) {
                    $error_flag = true;
                }
                // 選択アイテム
                if (empty($selected_item_list)) {
                    $this->Cleaning->validationErrors['item'][0] = 'アイテムを選択してください';
                    $error_flag = true;
                }

                if ($error_flag) {
                    return $this->render('input');
                }

                // add post data
                $cleaning_data['selected_item_list'] = $selected_item_list;
                $cleaning_data['subtotal'] = $subtotal;

                CakeSession::write(self::MODEL_NAME_CLEANING, $cleaning_data);
                CakeSession::write(self::MODEL_NAME_POINT_USE, $point_data);

                return $this->redirect(['controller' => 'cleaning', 'action' => 'confirm']);
            }
        }
    }

    /**
     *  confirm
     */
    public function confirm()
    {
        // check access source actions
        $allow_action_list = [
            'Cleaning/input',
            'Cleaning/confirm',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'cleaning', 'action' => 'input']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $cleaning_data = CakeSession::read(self::MODEL_NAME_CLEANING);
        $point_data = CakeSession::read(self::MODEL_NAME_POINT_USE);

        $price = Configure::read('app.kit.cleaning.item_group_cd');


        // クリーニング情報
        $this->set('cleaning_data', $cleaning_data);
        // ポイント情報
        $this->set('point_data', $point_data);
        // 金額情報
        $this->set('price', $price);
    }

    /**
     *  complete
     */
    public function complete()
    {
        // check access source actions
        $allow_action_list = [
            'Cleaning/confirm',
        ];
        if (in_array(CakeSession::read('app.data.session_referer'), $allow_action_list, true) === false) {
            $this->redirect(['controller' => 'cleaning', 'action' => 'input']);
        }
        CakeSession::Write('app.data.session_referer', $this->name . '/' . $this->action);

        $cleaning_data = CakeSession::read(self::MODEL_NAME_CLEANING);
        $point_data = CakeSession::read(self::MODEL_NAME_POINT_USE);

        // Item_Group_Id単位で分ける
        $item_by_group_cd = [];
        foreach ($cleaning_data['selected_item_list'] as $item) {
            $item_by_group_cd[$item['item_group_cd']][] = $item;
        }

        if (empty($item_by_group_cd)) {
            $this->Flash->validation('データに不備がありました。', ['key' => 'invalid_data']);
            return $this->redirect(['controller' => 'cleaning', 'action' => 'input']);
        }

        /** クリーニング対応 */
        foreach ($item_by_group_cd as $group_cd => $items) {

            $request_param = array(
                "work_type" => $this->Cleaning->getWorkType($group_cd),
                "product"   => $this->Cleaning->buildParamProduct($items),
            );

            // パラメータセット
            $this->Cleaning->set($request_param);

            // Validate
            $valid_cleaning = $this->Cleaning->validates();

            if (!$valid_cleaning) {
                // Validateに失敗した場合
                $this->Flash->validation('データに不備がありました。', ['key' => 'invalid_data']);
                return $this->redirect(['controller' => 'cleaning', 'action' => 'input']);
            } else {
                // API:クリーニング申し込み
                $res = $this->Cleaning->apiPost($this->Cleaning->toArray());
                // 登録に失敗した場合
                if (!empty($res->error_message)) {
                    $this->Flash->validation($res->error_message, ['key' => 'cleaning_post']);
                    return $this->redirect(['controller' => 'cleaning', 'action' => 'input']);
                }
            }
        }

        /** ポイント利用 */
        if (!empty($point_data['use_point'])) {
            // ポイント取得
            $res = $this->PointBalance->apiGet();
            if (!empty($res->error_message)) {
                $this->Flash->validation($res->error_message, ['key' => 'point_get']);
                return $this->redirect(['controller' => 'cleaning', 'action' => 'input']);
            } else {
                $point_balance = $res->results[0]['point_balance'];
            }
            $point_data = CakeSession::read(self::MODEL_NAME_POINT_USE);
            $this->PointUse->set($point_data);
            $this->PointUse->data[self::MODEL_NAME_POINT_USE]['point_balance'] = $point_balance;
            $this->PointUse->data[self::MODEL_NAME_POINT_USE]['subtotal'] = $cleaning_data['subtotal'];
            $this->PointUse->data[self::MODEL_NAME_POINT_USE]['contents_type'] = USE_POINT_CONTENTS_TYPE_CLEANING_PLUS;
            // validation
            if (!$this->PointUse->validates()) {
                $this->Flash->validation('データに不備がありました。', ['key' => 'invalid_data']);
                return $this->redirect(['controller' => 'cleaning', 'action' => 'input']);
            } else {
                // API:ポイント使用
                $res = $this->PointUse->apiPost($this->PointUse->toArray());
                // APIに失敗した場合
                if (!empty($res->error_message)) {
                    $this->Flash->validation($res->error_message, ['key' => 'point_post']);
                    return $this->redirect(['controller' => 'cleaning', 'action' => 'input']);
                }
            }
        }

        // クリーニング情報
        $this->set('cleaning_data', $cleaning_data);
        // ポイント情報
        $this->set('point_data', $point_data);
        // 金額情報
        $this->set('price', Configure::read('app.kit.cleaning.item_group_cd'));

        $this->_cleanSession();
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
        $search_options = CakeSession::read('app.data.session_cleaning_search');

        if (!empty($_data) && isset($_data['search'])) {
            $search_options = [
                "keyword"    => $_data['keyword'],
                "order"      => $_data['order'],
                "direction"  => $_data['direction'],
            ];
            CakeSession::write('app.data.session_cleaning_search', $search_options);
        }

        return $search_options;
    }

    /*
     * アイテム情報を取得
     *
     * @param array $_search_param 検索条件
     *
     * @return array 検索条件
     */
    private function _getItem($_search_options)
    {
        $item_list = [];

        // 商品指定
        $where = [];
        $where['product'] = null;
        // ItemStatusは70のみを表示
        $where['item_status'] = array(BOXITEM_STATUS_INBOUND_DONE);
        // itemgroup_cdはConfig/EnvConfig/[Development]/AppConfig.phpを参照
        $where['item_group_cd'] = array_keys(Configure::read('app.kit.cleaning.item_group_cd'));
        // 保管品リストを取得する
        //* アイテム取得、 中でアイテム画像とボックス情報取得
        $tmp_list = $this->InfoItem->apiGetResultsWhere([], $where);
        // リストからクリーニングパックを除外する
        foreach ($tmp_list as $item) {
            $notTrade = true;
            $notMatch = false;
            $value = [PRODUCT_CD_CLEANING_PACK];
            if (!in_array($item['box']['product_cd'], $value, true)) {
                $notMatch = true;
            }
            if (!empty($item['sales'])) {
                foreach($item['sales'] as $sales) {
                    if($sales['sales_status'] >= SALES_STATUS_ON_SALE && $sales['sales_status'] <= SALES_STATUS_REMITTANCE_COMPLETED ) {
                        $notTrade = false;
                        break;
                    }
                }
            }
            if ($notMatch && $notTrade) {
                array_push($item_list, $item);
            }
        }

        // 並び替えキー指定
        if (isset($_search_options['order'])) {
            $sort_key = [$_search_options['order'] => ($_search_options['direction'] === 'asc')];
        } else {
            $sort_key = [];
        }

        // sort
        HashSorter::sort($item_list, ($sort_key + self::DEFAULTS_SORT_KEY));

        $item_list = $this->InfoItem->editBySearchTerm($item_list, $_search_options, false);

        return $item_list;
    }

    /*
     * 選択済みアイテム情報を取得
     *
     * @param array $_data POST情報
     * @param array $_item_list アイテム情報
     *
     * @return array 検索条件
     */
    private function _getSelectedItem($_data, $_item_list)
    {
        $selected_item_list = [];

        if (!empty($_data) && isset($_data['selected_item_id_list'])) {

            // 選択アイテム存在チェック
            $item_id_list = array_column($_item_list, 'item_id');
            $selected_item_id_list = array_keys($_data['selected_item_id_list']);
            foreach ($selected_item_id_list as $selected_item_id) {
                // アイテム情報
                if (in_array($selected_item_id, $item_id_list, true)) {
                    $target_index = array_search($selected_item_id, $item_id_list);
                    $selected_item_list[] = $_item_list[$target_index];
                } else {
                    $this->Flash->validation('選択されたアイテムが存在しませんでした。再度お試し下さい。', ['key' => 'selected_item']);
                    return $this->redirect(['controller' => 'cleaning', 'action' => 'input']);
                }
            }
        }

        return $selected_item_list;
    }

    /**
     * 使用しているセッションを削除
     */
    private function _cleanSession()
    {
        CakeSession::delete(self::MODEL_NAME_CLEANING);
        CakeSession::delete(self::MODEL_NAME_POINT_USE);
        CakeSession::delete('app.data.session_cleaning_search');
    }
}
