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
        $this->set('sortSelectList', $this->makeSelectSortUrl());
        $this->set('select_sort_value', Router::reverse($this->request));
    }

    private function makeSelectSortUrl()
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

    private function getRequestSortKey()
    {
        $order = $this->request->query('order');
        $direction = $this->request->query('direction');
        if (!empty($order)) {
            return [$order => ($direction === 'asc')];
        }
        return [];
    }

    /**
     * input
     */
    public function input()
    {
        $priorities = [];
        $storedList = null;
        $selected_id = null;

        // resetが設定されている場合はすべてリセットする
        if ( isset($this->request->query['reset']) ) {
            // 廃止予定
            #CakeSession::write('app.data.session_cleaning_loaded',1);
            setcookie("mn_cleaning_list", "", time()-3600);
        } else {
            // 引数でidがリターンされた場合はすでにチェックを入れる
            if ( isset($this->request->query['id']) ) {
                $selected_id = $this->request->query['id'];
                array_push($priorities,["item_id"=>$selected_id]);
            }
            
            $query = $this->request->query;
            // confirmからのバックの場合は選択を保持する
            if ( isset($_COOKIE['mn_cleaning_list'])  ) {
                // 廃止予定
                #$toloadPage = CakeSession::read('app.data.session_cleaning_loaded');
                
                foreach ( explode(",", $_COOKIE['mn_cleaning_list']) as $tmp ) {
                array_push($priorities,["item_id"=>$tmp]);
                }
            }
        }

        // ページが設定されていない場合は１を設定
        if ( !isset($query['page']) ) {
            $query['page'] = 1;
        }
        
        // 商品指定
        $where = [];
        $where['product'] = null;
        $where['item_status'] = array(70);
        $where['item_group_cd'] = array_keys(Configure::read('app.kit.cleaning.item_group_cd'));

        // 並び替えキー指定
        $sortKey = $this->getRequestSortKey();

        // 保管品リストを取得する
        $results = $this->InfoItem->getListWhere($sortKey, $where, $priorities);
        $results = $this->InfoItem->editBySearchTerm($results, $this->request->query);
        $item_all_count = count($results);
        
        // paginate(仕様変更により廃止予定）
/*
        if ( $storedList ) {
            $list = array();
            for ( $_i=1; $_i<=$toloadPage; $_i++ ) {
               $this->request->query['page'] = $_i;
               $query['page'] = $_i;
               $tmplist = $this->paginate(self::MODEL_NAME, $results);
               $list = array_merge($list,$tmplist);
            }
        } else {
        }
*/
        $list = $this->paginate(self::MODEL_NAME, $results);
        
        $this->set('itemList', $list);
        $this->set('item_all_count', $item_all_count);

        $url = Router::url(['action'=>'input', '?' => http_build_query($query)]);
        $query_params = $this->setQueryParameter();

        $this->set('keyword', $query_params['keyword']);
        $this->set('order', $query_params['order']);
        $this->set('direction', $query_params['direction']);
        $this->set('page',$query['page']);
        $this->set('selected_id', $selected_id);

        // 廃止予定
        #CakeSession::write('app.data.session_cleaning_loaded',$query['page']);

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

        if ( !isset($this->request->data['selected']) ) {
            $flg_error = true;
        } else {
            $selected_ids = $this->request->data['selected'];
            if ( count($selected_ids) < 1 ) {
                $flg_error = true;
            }
        }
        
        if ( $flg_error ) {
          return $this->redirect(['controller' => 'Cleaning', 'action' => 'input']);
        }

        $session_data = array();
        $totalCount = 0;
        $totalprice = 0;

        foreach ( $selected_ids as $item ) {
            $data = array();
            list($data['item_id'], $data['item_group_cd'], $data['box_id'], $data['product_cd'],$data['image_url']) = explode(",",$item,5);
            $data['price']= Configure::read('app.kit.cleaning.item_group_cd')[$data['item_group_cd']];
            
            if ( !isset($session_data[$data['item_group_cd']]) ) $session_data[$data['item_group_cd']] = array();
            array_push($session_data[$data['item_group_cd']],$data);

            $totalprice += $data['price'];
            $totalCount++;
        }
        
        CakeSession::write('app.data.session_cleaning',$session_data);

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
        CakeSession::write('app.data.session_referer', $this->name . '/' . $this->action);

        // データがない場合はリダイレクト
        if ( !CakeSession::read('app.data.session_cleaning') ) {
          return $this->redirect(['controller' => 'Cleaning', 'action' => 'input']);
        }

        // Item_Group_Idごとにデータを処理する
        $flgComplete = true;
        $request_data = array();

        $ct = 0;
        foreach ( CakeSession::read('app.data.session_cleaning') as $itemGroupCD=>$items ) {
            $requestParam = array(
                "work_type"  => $this->Cleaning->getWorkType($itemGroupCD),
                "product"    => $this->Cleaning->buildParamProduct($items),
            );
            
            $this->Cleaning->set($requestParam);
            $validCleaning = $this->Cleaning->validates();

            if ( !$validCleaning ) {
                $this->Flash->set("データに誤りがあります");
                $flgComplete = false;
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
                    $flgComplete = false;
                    break;
                } else {
                    // 処理完了した分に関してはセッションから削除する
                    CakeSession::delete("app.data.session_cleaning.".$itemGroupCD);
                }
            }
            
            $ct++;
        }

        // 登録に成功した場合
        $this->set('itemList', CakeSession::read('app.data.session_cleaning'));
        $this->set('flgComplete', $flgComplete);
        
        // 処理が完了したら、セッションとクッキーを削除する
        CakeSession::delete("app.data.session_cleaning"); 
        if ( $flgComplete ) {
            //setcookie("mn_cleaning_list", "", time()-3600);
        }
    }
}
