<?php

App::uses('MinikuraController', 'Controller');
App::uses('AppFile', 'Lib');
App::uses('AppImageSns', 'Lib');

/**
* 販売機能 アイテム　各ページ  
*
* 
*/
class SaleItemController extends MinikuraController
{
    const MODEL_NAME_SALES = 'Sales';
    const MODEL_NAME_INFO_ITEM = 'InfoItem';

    public function beforeFilter () {
        parent::beforeFilter(); 
        $this->loadModel(self::MODEL_NAME_SALES);
        $this->loadModel(self::MODEL_NAME_INFO_ITEM);
    }


    /**
     * 暫定 edit
     * アイテムページのitem/detail/から遷移 
     */
    public function edit()
    {

        //* get
        if ($this->request->is('get')) {
            $id = $this->params['id'];
        }
        //* post
        if ($this->request->is('post')) {
            $data = $this->request->data[self::MODEL_NAME_SALES];
            $id = $data['item_id'];
            $this->Sales->set($data);
            if ( $this->Sales->validates()) {
                //* session write
                CakeSession::write(self::MODEL_NAME_SALES, $data);
            } else {
                $this->set('validErrors', $this->Sales->validationErrors);
                //CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->Sales->validationErrors, true));
                // renderはSaleItem/edit
            }
        }
        $item = $this->InfoItem->apiGetResultsFind([], ['item_id' => $id]);
        if (empty($item)) {
            //* no data
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['controller' => 'item', 'action' => 'index' ]);
        }
        $this->set('item', $item);

        $box = $item['box'];
        $this->set('box', $box);

    }

    /**
     * 暫定 complete
     */
    public function complete()
    {
        //* reload
        $data = CakeSession::read([self::MODEL_NAME_SALES]);
        CakeSession::delete(self::MODEL_NAME_SALES);
        if (empty($data)) {
            $this->Flash->set(__('empty_session_data'));
            return $this->redirect(['controller' => 'item', 'action' => 'index' ]);
        }
        $this->Sales->set($data);

        //* post
        if ($this->request->is('post')) {
            if ( $this->Sales->validates()) {

                $this->set('sale_item', $data);
                //* to API
                $sales_result = $this->Sales->apiPost($data);
                //*  error
                if (!empty($sales_result->error_message)) {
                    $this->Flash->set($sales_result->error_message);
                    return $this->redirect('/item/detail/'.$data['item_id']);
                }

                //* item, box
                $id = $data['item_id'];
                $item = $this->InfoItem->apiGetResultsFind([], ['item_id' => $id]);
                $this->set('item', $item);

                $box = $item['box'];
                $this->set('box', $box);

                //* sales 
                $sales_result = $this->Sales->apiGet(['item_id' => $id, 'sales_status' => SALES_STATUS_ON_SALE ]);
                if (!empty($sales_result->results[0])) {
                    $sales = $sales_result->results[0];
                    $sales_id = $sales['sales_id'];
                    //* trade page url
                    $trade_url = Configure::read('site.trade.url').$sales_id;
                    //* widget page url
                    $widget_url = Configure::read('site.trade.url') . 'widget/' . $sales_id;

                    //* test facebook share用のurlをFB側にキャッシュさせる
                    /*
                    $ch = curl_init();
                    $post_data = ['q' => $trade_url];
                    $facebook_developper_url = 'https://developers.facebook.com/tools/debug/sharing/?';
                    //$facebook_developper_url = 'https://developers.facebook.com/tools/debug/sharing/?q='.urlencode($trade_url);
                    //$facebook_developper_url = 'https://graph.facebook.com/?scrape=true&id='.urlencode($trade_url);
                    curl_setopt($ch, CURLOPT_URL, $facebook_developper_url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
                    $curl_result = curl_exec($ch);
                    $curl_info = curl_getinfo($ch);
                    //CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($curl_info, true));
                    curl_close($ch);
                    */
                }
                /*
                *  [test]image processing & file upload 
                *  [test]FB shareボタンを押した時に走るajaxの処理を、アイテム販売完了時に行ってみる
                */
                //AppImageSns::image_facebook($sales['item_image'][0]['image_url']);

                $this->set('sales', $sales);
                $this->set('trade_url', $trade_url);
                $this->set('widget_url', $widget_url);

            } else {
                $this->set('validErrors', $this->Sales->validationErrors);
                //todo render
                return $this->redirect('/item/detail/'.$data['item_id']);
            }
        }
    }

    /**
     * 暫定 cancel
     */
    public function cancel()
    {
        if (!$this->request->is('post')) {
            //* redirect
            return $this->redirect($this->referer());
        }
        //* post
        if ($this->request->is('post')) {
            $data = $this->request->data[self::MODEL_NAME_SALES];
            $id = $data['item_id'];
            $this->Sales->set($data);
            if ( $this->Sales->validates(['fieldList' => ['sales_id']])) {
                //* to API
                $sales_put_result = $this->Sales->apiPut($data);
                //CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($sales_put_result, true));
                // error
                if (!empty($sales_put_result->error_message)) {
                    $this->Flash->set($sales_put_result->error_message);
                    return $this->redirect('/item/detail/'.$id);
                }

            } else {
                $this->set('validErrors', $this->Sales->validationErrors);
                //CakeLog::write(BENCH_LOG, __METHOD__.'('.__LINE__.')'.var_export($this->Sales->validationErrors, true));
                //todo render
            }
            //* 表示用
            $item = $this->InfoItem->apiGetResultsFind([], ['item_id' => $id]);
            if (empty($item)) {
                //* no data
                $this->Flash->set(__('empty_session_data'));
                return $this->redirect(['controller' => 'item', 'action' => 'index' ]);
            }
            $this->set('item', $item);

            $box = $item['box'];
            $this->set('box', $box);
        }
    }
}
