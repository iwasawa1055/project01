<?php

App::uses('MinikuraController', 'Controller');

/**
* クリーニングメニュー　各ページ  
*
* 
*/
class CleaningController extends MinikuraController
{
    const MODEL_NAME = 'Cleaning';
    const MODEL_NAME_ITEM = 'InfoItem';
    
    protected $paginate = array(
        'limit' => 20,
        'paramType' => 'querystring'
    );

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
        $this->loadModel(self::MODEL_NAME_ITEM);

        (new InfoItem())->deleteCache();
        $this->set('sortSelectList', $this->_makeSelectSortUrl());
        $this->set('select_sort_value', Router::reverse($this->request));
    }

    protected function setQueryParameter()
    {
        $query = $this->request->query;
        $results = [];
        // keyword
        if (empty($query['keyword'])) {
            $results['keyword'] = null;
        } else {
            $results['keyword'] = $query['keyword'];
        }

        // order
        if (empty($query['order'])) {
            $results['order'] = null;
        } else {
            $results['order'] = $query['order'];
        }

        // direction
        if (empty($query['direction'])) {
            $results['direction'] = null;
        } else {
            $results['direction'] = $query['direction'];
        }

        return $results;
    }

    /**
     * input
     */
    public function input()
    {
        $priorities = [];
        $storedList = null;

        // 引数を取得
        $selected_id = filter_input(INPUT_GET,"id");

        $params = [
            "page"          => filter_input(INPUT_GET,"page"),
            "keyword"     => filter_input(INPUT_GET,"keyword"),
            "order"        => filter_input(INPUT_GET,"order"),
            "direction"  => filter_input(INPUT_GET,"direction"),
        ];
        
        // resetが設定されている場合はすべてリセットする
        if ( null !== filter_input(INPUT_GET,"reset") ) {
            // 選択リストCookieを削除する
            setcookie("mn_cleaning_list", "", time()-3600);
        } 

        // 引数でidがリターンされた場合はすでにチェックを入れる
        if ( !is_null($selected_id) ) {
            // リストの優先アイテムとして追加する （トップに持ってくるため）
            array_push($priorities, ["item_id"=>$selected_id]);
        }

        // confirmからのバックの場合は選択を保持する
        if ( isset($_COOKIE['mn_cleaning_list']) ) {
            // 選択されたアイテムを優先アイテムとして追加する
            foreach ( explode(",", $_COOKIE['mn_cleaning_list']) as $tmp ) {
                array_push($priorities,["item_id"=>$tmp]);
            }
        }

        // ページが設定されていない場合は１を設定
        if ( !isset($params['page']) ) {
            $params['page'] = 1;
        }
        
        // 商品指定
        $where = [];
        $where['product'] = null;
        // ItemStatusは70のみを表示
        $where['item_status'] = array(70);
        // ItemgroupCDはConfig/EnvConfig/[Development]/AppConfig.phpを参照
        $where['item_group_cd'] = array_keys(Configure::read('app.kit.cleaning.item_group_cd'));

        // 並び替えキー指定
        $sortKey = $this->_getRequestSortKey();

        // 保管品リストを取得する
        // sortkey:ソートキー、 where:リスティング条件、prioritiesは優先アイテム指定
        $results = $this->InfoItem->getListWhere($sortKey, $where, $priorities);
        $results = $this->InfoItem->editBySearchTerm($results, $params);

        // 全体のアイテム数を取得
        $item_all_count = count($results);
        
        // ページング
        $list = $this->paginate(self::MODEL_NAME, $results);
        
        // 取得したリストをセット
        $this->set('itemList', $list);

        // 取得したリストをセット
        $this->set('item_all_count', $item_all_count);

        $url = Router::url(['action'=>'input', '?' => http_build_query($params)]);
        $query_params = $this->setQueryParameter();

        // View用の変数をセット
        $this->set('keyword', $query_params['keyword']);
        $this->set('order', $query_params['order']);
        $this->set('direction', $query_params['direction']);
        $this->set('page',$params['page']);
        $this->set('selected_id', $selected_id);
        
        // 金額情報を変数に入れる
        // 金額情報はConfig/EnvConfig/[Development]/AppConfig.phpを参照
        $this->set('price',Configure::read('app.kit.cleaning.item_group_cd'));
        
        // Session Referer
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
    }

    /**
     *  confirm
     */
    public function confirm()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['Cleaning/input', 'Cleaning/confirm', 'Cleaning/complete'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'Cleaning', 'action' => 'input']);
        }
        
        $flg_error = false;
        $params = [
            "selected"          => filter_input(INPUT_POST,"selected",FILTER_DEFAULT,FILTER_REQUIRE_ARRAY),
        ];

        // 選択リストがない場合はエラー
        if ( is_null($params['selected']) ) {
            $flg_error = true;
        } else {
            // 選択リストから数を確認する
            if ( count($params['selected']) < 1 ) {
                $flg_error = true;
            }
        }
        
        // エラーの場合はinputにリダイレクト
        if ( $flg_error ) {
          return $this->redirect(['controller' => 'Cleaning', 'action' => 'input']);
        }

        $session_data = array();
        $totalCount = 0;
        $totalprice = 0;

        // 選択データを整理してセッション用データを作成する
        foreach ( $params['selected'] as $item ) {
            $data = array();
            
            // アイテムデータ文字列を分解する
            list($data['item_id'], $data['item_group_cd'], $data['box_id'], $data['product_cd'],$data['image_url']) = explode(",",$item,5);

            // アイテムの価格を設定
            // 金額情報はConfig/EnvConfig/[Development]/AppConfig.phpを参照
            $data['price']= Configure::read('app.kit.cleaning.item_group_cd')[$data['item_group_cd']];
            
            // item_group_cd配列がない場合は追加して初期化
            if ( !isset($session_data[$data['item_group_cd']]) ) $session_data[$data['item_group_cd']] = array();
            
            // itemgroup->itemの配列に処理したデータを収納
            array_push($session_data[$data['item_group_cd']],$data);
            
            // 金額合計とアイテム数を計算する
            $totalprice += $data['price'];
            $totalCount++;
        }
        
        // 処理したデータをセッションに保管する
        CakeSession::write('app.data.session_cleaning',$session_data);

        // View用の変数をセット
        $this->set('selected_count',$totalCount);
        $this->set('selected_total', $totalprice);
        $this->set('itemList', $session_data);

        // Session Referer
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);
    }

    /**
     *  complete 
     */
    public function complete()
    {
        //* session referer check
        if (in_array(CakeSession::read('app.data.session_referer'), ['Cleaning/confirm'], true) === false) {
            //* NG redirect
            $this->redirect(['controller' => 'Cleaning', 'action' => 'input']);
        }

        // Session Referer
        // ※最後に設定すると途中エラー発生時、リファラー判断の問題があるので先に設定する
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        // セッションデータがない場合はリダイレクト
        if ( !CakeSession::read('app.data.session_cleaning') ) {
          return $this->redirect(['controller' => 'Cleaning', 'action' => 'input']);
        }

        // Item_Group_Idごとにデータを処理する
        $flg_complete = true;
        $request_data = array();

        $ct = 0;
        // セッションデータかAPIにリクエストする
        foreach ( CakeSession::read('app.data.session_cleaning') as $itemGroupCD=>$items ) {
            // APIリクエストのためのパラメータをセット
            //  Model/API/Cleaning
            $request_param = array(
                "work_type"  => $this->Cleaning->getWorkType($itemGroupCD),
                "product"    => $this->Cleaning->buildParamProduct($items),
            );
            
            // パラメータセット
            $this->Cleaning->set($request_param);
            // Validate
            $validCleaning = $this->Cleaning->validates();

            if ( !$validCleaning ) {
                // Validateに失敗した場合
                $this->Flash->set("データに誤りがあります");
                $flg_complete = false;
                break;
            } else {
                // クリーニング申し込み
                if ( $ct === 0 ) {
                    $res = $this->Cleaning->apiPost($this->Cleaning->toArray());
                } else {
                    $res = (object) [];
                    $res->error_message = "ERROR";
                }

                // 登録に失敗した場合
                if (!empty($res->error_message)) {
                    // Cookieを更新する

                    #$this->Flash->set($res->error_message);
                    $flg_complete = false;
                    break;
                } else {
                    // 処理完了した分に関してはセッションから削除する
                    CakeSession::delete("app.data.session_cleaning.".$itemGroupCD);
                }
            }
            
            $ct++;
        }

        // View用の変数をセット
        $this->set('itemList', CakeSession::read('app.data.session_cleaning'));
        $this->set('flgComplete', $flg_complete);
        
        // 処理が完了したら、セッションとクッキーを削除する
        CakeSession::delete("app.data.session_cleaning"); 
        if ( $flg_complete ) {
            //setcookie("mn_cleaning_list", "", time()-3600);
        }
    }


    private function _makeSelectSortUrl()
    {
        // 並び替え選択
        $selectSortKeys = [
            'item_id' => __('item_id'),
            'item_name' => __('item_name'),
        ];

        $page = $this->request->query('page');
        $data = [];
        foreach ($selectSortKeys as $key => $value) {
            $desc = Router::url(['action'=>'index', '?' => ['order' => $key, 'direction' => 'desc', 'page' => $page]]);
            $data[$desc] = $value . __('select_sort_desc');
            $asc = Router::url(['action'=>'index', '?' => ['order' => $key, 'direction' => 'asc', 'page' => $page]]);
            $data[$asc] = $value . __('select_sort_asc');
        }

        return $data;
    }

    private function _getRequestSortKey()
    {
        $order = $this->request->query('order');
        $direction = $this->request->query('direction');
        if (!empty($order)) {
            return [$order => ($direction === 'asc')];
        }
        return [];
    }
}
