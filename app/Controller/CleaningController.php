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
    const PER_PAGE = 20;

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
        $list = [];
        $pager = null;

        // 引数を取得
        $selected_id = filter_input(INPUT_GET, "id");
        $page = filter_input(INPUT_GET, "page");
        
        // 検索情報を読み込む
        $search_options = CakeSession::read('app.data.session_cleaning_search');
        if (filter_input(INPUT_POST, "order")) {
            $search_options = [
                "keyword"    => filter_input(INPUT_POST, "keyword"),
                "order"       => filter_input(INPUT_POST, "order"),
                "direction" => filter_input(INPUT_POST, "direction"),
            ];
            //CakeSession::write('app.data.session_cleaning_search', $search_options);
        }
      
        // resetが設定されている場合はすべてリセットする
        /*
        if (isset($_COOKIE['mn_cleaning_reset'])) {
            // 選択リストCookieを削除する
            setcookie("mn_cleaning_list", "", time() - 3600);
            setcookie("mn_cleaning_reset", "", time() - 3600);
        } 
        */

        // 引数でidがリターンされた場合はすでにチェックを入れる
        if (!is_null($selected_id)) {
            // リストの優先アイテムとして追加する （トップに持ってくるため）
            array_push($priorities, ["item_id" => $selected_id]);
        }

        // confirmからのバックの場合は選択を保持する
        if (isset($_COOKIE['mn_cleaning_list']) && $_COOKIE['mn_cleaning_list'] !== "") {
            //$selected_list = json_decode($_COOKIE['mn_cleaning_list'],TRUE);
            $selected_list = explode(",",$_COOKIE['mn_cleaning_list']);

            // 選択されたアイテムを優先アイテムとして追加する
            foreach ($selected_list as $tmp) {
                array_push($priorities, ["item_id" => $tmp]);
            }
        }
        // 現在のページ指定
        if (!isset($page)) {
            $page = 1;
        }

        // 商品指定
        $where = [];
        $where['product'] = null;
        // ItemStatusは70のみを表示
        $where['item_status'] = array(BOXITEM_STATUS_INBOUND_DONE);
        // itemgroup_cdはConfig/EnvConfig/[Development]/AppConfig.phpを参照
        $where['item_group_cd'] = array_keys(Configure::read('app.kit.cleaning.item_group_cd'));

        // 並び替えキー指定
        if (!empty($search_options['order'])) {
            $sort_key = [$search_options['order'] => ($search_options['direction'] === 'asc')];
        } else {
            $sort_key = [];
        }
        
        // 保管品リストを取得する
        // sort_key:ソートキー、 where:リスティング条件、prioritiesは優先アイテム指定
        $results = $this->InfoItem->getListWhere($sort_key, $where, $priorities);
        $results = $this->InfoItem->editBySearchTerm($results, $search_options);

        // 全体のアイテム数を取得
        $item_all_count = count($results);
        
        // ページングライブラリを使う（CWS)
        App::uses('AppHelperHelper', 'View/Helper');
        $appHelper = new AppHelperHelper(new View());

        // 選択したアイテム(Cookie)が一ページの上限を超えた場合
        if (count($priorities) > self::PER_PAGE) {
            // 読み込むページ数を計算する
            $num_loadpage = ceil(count($priorities) / self::PER_PAGE);
            
            // ロードするページより現在のページより大きい場合
            //   選択したリスト以後の対応（通常ページと同様読み込む
            if ($page > $num_loadpage) {
                // ページ情報を取得する
                $pager = $appHelper->getPagerInfo($page, self::PER_PAGE, $item_all_count);
                
                // 現在のページがtotal_pageの数より同様か小さい場合のみリストを出力する
                if ($pager['total_page'] >= $page) {
                    // 表示データ取得
                    $list = array_slice($results, $pager["start_row"], $pager["view_count"]);
                }
            } else {
                // 選択した画像は絶対トップに来るので0番レコードから、読み込みページ数分取得する
                $list = array_merge($list, array_slice($results, 0, self::PER_PAGE * $num_loadpage));

                // 現在のページを読み込むページ数に設定する
                $page = $num_loadpage;
                
                // ページ情報を取得
                $pager = $appHelper->getPagerInfo($page, self::PER_PAGE, $item_all_count);
            }
        } else {
            // 選択したアイテムがない場合の対応
            // 全レコード数がページあたりのアイテム数より小さい場合
            // それ以外はそのまま表示
            if ($item_all_count > self::PER_PAGE) {
                $pager = $appHelper->getPagerInfo($page, self::PER_PAGE, $item_all_count);
                
                if ($pager['total_page'] >= $page) {
                    // 表示データ取得
                    $list = array_slice($results, $pager["start_row"], $pager["view_count"]);
                }
            } else {
                $list = $results;
            }
        }
        
        // 取得したリストをセット
        $this->set('itemList', $list);
        // 取得したリストをセット
        $this->set('item_all_count', $item_all_count);
    
        // 次のページのリンクを生成（infinitescroolのため)
        $nexturl = Router::url(['action' => 'input', '?' => "page=" . $pager['next_page']]);

        // View用の変数をセット
        $this->set('keyword', $search_options['keyword']);
        $this->set('order', $search_options['order']);
        $this->set('direction', $search_options['direction']);
        $this->set('selected_id', $selected_id);
        $this->set('pager', $pager);
        $this->set('nexturl', $nexturl);
        
        // 金額情報を変数に入れる
        // 金額情報はConfig/EnvConfig/[Development]/AppConfig.phpを参照
        $this->set('price', Configure::read('app.kit.cleaning.item_group_cd'));
        
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
           "order"        => filter_input(INPUT_POST, "order"),
           //"selected" => filter_input(INPUT_POST, "selected", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY),
        ];
        
        if (isset($params['order'])) {
            $selected_list = json_decode($params['order'], true);
        } else {
            $selected_list = null;
        }
        
        // 選択リストがない場合はエラー
        if (is_null($selected_list)) {
            $flg_error = true;
        } else {
            // 選択リストから数を確認する
            if (count($selected_list) < 1) {
                $flg_error = true;
            }
        }
        
        // エラーの場合はinputにリダイレクト
        if ($flg_error) {
          $this->Flash->set("アイテムを選択してください。");
          return $this->redirect(['controller' => 'Cleaning', 'action' => 'input']);
        }

        $session_data = array();
        $totalcount = 0;
        $totalprice = 0;

        // 選択データを整理してセッション用データを作成する
        foreach ($selected_list as $item) {
            $data = array();
            
            // アイテムデータ文字列を分解する
            list($data['item_id'], $data['item_group_cd'], $data['box_id'], $data['product_cd'], $data['image_url']) = explode(",", $item['data'], 5);

            // アイテムの価格を設定
            // 金額情報はConfig/EnvConfig/[Development]/AppConfig.phpを参照
            $data['price']= Configure::read('app.kit.cleaning.item_group_cd')[$data['item_group_cd']];
            
            // item_group_cd配列がない場合は追加して初期化
            if (!isset($session_data[$data['item_group_cd']])) {
                $session_data[$data['item_group_cd']] = array();
            }
            
            // itemgroup->itemの配列に処理したデータを収納
            array_push($session_data[$data['item_group_cd']], $data);
            
            // 金額合計とアイテム数を計算する
            $totalprice += $data['price'];
            $totalcount++;
        }
        
        // 処理したデータをセッションに保管する
        CakeSession::write('app.data.session_cleaning', $session_data);

        // View用の変数をセット
        $this->set('selected_count', $totalcount);
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
        // ※最後に設定すると途中エラー発生時、リロード時再処理することを防ぐため
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        // セッションデータがない場合はリダイレクト
        if (!CakeSession::read('app.data.session_cleaning')) {
            return $this->redirect(['controller' => 'Cleaning', 'action' => 'input']);
        }

        // Item_Group_Idごとにデータを処理する
        $flg_complete = true;
        $complete_data = [];
        $request_data = [];

        // セッションデータかAPIにリクエストする
        foreach (CakeSession::read('app.data.session_cleaning') as $itemgroup_cd => $items) {
            // APIリクエストのためのパラメータをセット
            //  Model/API/Cleaning
            $request_param = array(
                "work_type" => $this->Cleaning->getWorkType($itemgroup_cd),
                "product"    => $this->Cleaning->buildParamProduct($items),
            );
            
            // パラメータセット
            $this->Cleaning->set($request_param);
            // Validate
            $valid_cleaning = $this->Cleaning->validates();

            if (!$valid_cleaning) {
                // Validateに失敗した場合
                $this->Flash->set("データに誤りがあります");
                $flg_complete = false;
                break;
            } else {
                // API:クリーニング申し込み
                $res = $this->Cleaning->apiPost($this->Cleaning->toArray());

                // 登録に失敗した場合
                if (!empty($res->error_message)) {
                    // 未処理のアイテムリストを収集する
                    $selected_items = [];
                    foreach (CakeSession::read('app.data.session_cleaning') as $itemgroup2) {
                        foreach ($itemgroup2 as $items2) {
                            array_push($selected_items, $items2['item_id']);
                        }
                    }
                    // Cookieを更新する(
                    setcookie("mn_cleaning_list", implode(",", $selected_items));

                    //$this->Flash->set("");
                    $flg_complete = false;
                    break;
                } else {
                    $complete_data[$itemgroup_cd] = $items;
                    // 処理完了した分に関してはセッションから削除する
                    CakeSession::delete("app.data.session_cleaning." . $itemgroup_cd);
                }
            }
        }

        // View用の変数をセット
        $this->set('itemList', $complete_data);
        $this->set('flgComplete', $flg_complete);
        
        // 処理が完了したら、セッションとクッキーを削除する
        CakeSession::delete("app.data.session_cleaning"); 
        
        if ($flg_complete) {
            CakeSession::delete('app.data.session_cleaning_search');
            setcookie("mn_cleaning_list", "", time() - 3600);
        }
    }

    private function _makeSelectSortUrl()
    {
        // 並び替え選択
        $selected_sortkey = [
            'item_id' => __('item_id'),
            'item_name' => __('item_name'),
        ];

        $page = $this->request->query('page');
        $data = [];
        foreach ($selected_sortkey as $key => $value) {
            $desc = Router::url(['action' => 'index', '?' => ['order' => $key, 'direction' => 'desc', 'page' => $page]]);
            $data[$desc] = $value . __('select_sort_desc');
            $asc = Router::url(['action' => 'index', '?' => ['order' => $key, 'direction' => 'asc', 'page' => $page]]);
            $data[$asc] = $value . __('select_sort_asc');
        }

        return $data;
    }
}
