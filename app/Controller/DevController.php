<?php

App::uses('MinikuraController', 'Controller');
App::uses('ApiModel', 'Model');
App::uses('ApiTest', 'Model');
App::uses('InfoItem', 'Model');
App::uses('InfoBox', 'Model');
App::uses('DevOrderId', 'Model');
App::uses('DevWorkId', 'Model');
App::uses('DevWorkLinkageId', 'Model');
App::uses('DevDeliVeryDone', 'Model');
App::uses('DevDeliVeryCancel', 'Model');
App::uses('DevInboundDone', 'Model');
App::uses('DevOutboundDone', 'Model');
App::uses('DevOutboundLingkageDone', 'Model');
App::uses('DevBilling', 'Model');
App::uses('DevUserApplying', 'Model');
App::uses('DevUserDebt', 'Model');
App::uses('OutboundList', 'Model');
App::uses('AppMail', 'Lib');
App::uses('InboundAndOutboundHistory', 'Model');

class DevController extends MinikuraController
{
    // ログイン不要なページ
    protected $checkLogined = false;

    /**
     * アクセス拒否
     */
    protected function isAccessDeny()
    {
        if (Configure::read('debug') > 0) {
            if (!$this->Customer->isLogined() && $this->action === 'customer') {
                return true;
            }
            return false;
        }
        return true;
    }

    public function index()
    {
        $this->layout = "";
    }

    public function server()
    {
        $this->layout = "";

        pr('-display-------------');
        pr('configure.debug: ' . Configure::read('debug'));
        pr('display_errors: ' . ini_get('display_errors'));
        pr('error_reporting: ' . ini_get('error_reporting'));
        pr('-Configure[site]-------------');
        pr(Configure::read('site'));
        pr('-Configure[api]-------------');
        pr(Configure::read('api'));
        pr('-ApiTest-------------');
        $test = new ApiTest();
        $res = $test->apiGet();
        pr('get: ' . ($res->isSuccess() ? 'success!' : $res->message));
        $res = $test->apiPost([]);
        pr('post: ' . ($res->isSuccess() ? 'success!' : $res->message));
        pr('-CakeLog-------------');
        pr(CakeLog::configured());
        pr('-Configure[app.e.mail.receiver]-------------');
        // pr(Configure::read('app'));
        pr(Configure::read('app.e.mail.receiver'));
        // pr(CakeLog::levels());
        pr('-AppMail-------------');
        $mail = new AppMail();
        pr($mail->config());
    }

    public function customer()
    {
        $this->layout = "";
        $data = [
            'oem_key' => ['oem_key' => Configure::read(ApiModel::CONFIG_API_OEMKEY)],
            'token' => $this->Customer->getToken(),
            'info' => $this->Customer->getInfo(),
            'password' => ['password' => $this->Customer->getPassword()],
        ];
        $this->set('data', $data);


        $this->set('order_ids', []);
        $this->set('work_ids_001', []);
        $this->set('work_ids_003', []);
        $this->set('work_ids_006', []);
        $this->set('boxData', []);
        $this->set('timeData', []);

        if (!$this->Customer->isEntry()) {
            // order, work
            $order = (new DevOrderId())->apiGet();
            $this->set('order_ids', $order->results);
            $work = (new DevWorkId())->apiGet(['work_type' => '001']);
            $this->set('work_ids_001', $work->results);
            $work = (new DevWorkLinkageId())->apiGet(['work_type' => '003']);
            $this->set('work_ids_003', $work->results);
            $work = (new DevWorkId())->apiGet(['work_type' => '006']);
            $this->set('work_ids_006', $work->results);

            $ib = new InfoBox();
            $ib->deleteCache();
            $boxList = $ib->apiGetResults();
            $data = [];
            foreach ($boxList as $b) {
                $data[$b['box_status']][] = $b;
            }
            ksort($data);
            $this->set('boxData', $data);
            unset($data);

            $ii = new InfoItem();
            $ii->deleteCache();
            $itemList = $ii->apiGetResults();
            $data = [];
            foreach ($itemList as $b) {
                $data[$b['box_id']][] = $b;
            }
            $this->set('timeData', $data);

            // 出庫処理
            $api_param['works_type'] = '003';
            $history_linkage_list = $this->_getOutboundHistoryLinkageList($api_param);
            $this->set('history_linkage_list', $history_linkage_list);
        }
    }

    private function after($res)
    {
        if ($res->isSuccess()) {
            return $this->redirect(['action' => 'customer']);
        } else {
            pr($res);
            $this->layout = false;
            $this->render(false);
        }
    }

    public function delivery_done()
    {
        $id = Hash::get($this->request->query, 'order_id');
        $dev = new DevDeliVeryDone();
        $res = $dev->apiPatch(['order_id' => $id]);
        return $this->after($res);
    }
    public function delivery_cancel()
    {
        $this->layout = "";
        $id = Hash::get($this->request->query, 'order_id');
        $dev = new DevDeliVeryCancel();
        $res = $dev->apiPatch(['order_id' => $id]);
        return $this->after($res);
    }
    public function inbound_done()
    {
        $this->layout = "";
        $boxId = Hash::get($this->request->query, 'box_id');
        $number = Hash::get($this->request->query, 'number');
        $dev = new DevInboundDone();
        $res = $dev->apiPatch(['box_id' => $boxId, 'number' => $number]);
        return $this->after($res);
    }
    public function outbound_done()
    {
        $this->layout = "";
        $id = Hash::get($this->request->query, 'work_linkage_id');
        $dev = new DevOutboundLingkageDone();
        $res = $dev->apiPatch(['work_linkage_id' => $id]);
        return $this->after($res);
    }
    public function billing()
    {
        $this->layout = "";
        $dev = new DevBilling();
        $res = $dev->apiPost([]);
        pr($res);
        $this->layout = false;
        $this->render(false);
    }
    public function user_applying()
    {
        $this->layout = "";
        $approval = Hash::get($this->request->query, 'approval');
        $dev = new DevUserApplying();
        $res = $dev->apiPatch(['approval' => $approval]);
        pr($res);
        $this->layout = false;
        $this->render(false);
    }
    public function user_debt()
    {
        $this->layout = "";
        $payment = Hash::get($this->request->query, 'payment');
        $dev = new DevUserDebt();
        $res = $dev->apiPatch(['payment' => $payment]);
        pr($res);
        $this->layout = false;
        $this->render(false);
    }
    public function cache_clear()
    {
        $this->layout = "";
        ApiCachedModel::deleteAllCache();
        return $this->redirect(['action' => 'index']);
    }

    /*
     * 取り出し履歴情報を取得
     *
     * @param array  $_search_param 絞り込み条件
     * @param string $_api_param    APIパラメータ
     *
     * @return array 絞り込み後の取出し履歴情報
     */
    private function _getOutboundHistoryLinkageList($_api_param = [])
    {
        $history_list = [];
        $history_linkage_list = [];

        $this->loadModel('InboundAndOutboundHistory');

        // 取り出し履歴取得
        $result = $this->InboundAndOutboundHistory->apiGet($_api_param);
        if ($result->isSuccess()) {
            $history_list = $result->results;
        }
        foreach ($history_list as $history_info) {
            if (!isset($history_info['work_linkage_id'])) {
                continue;
            }
            $history_linkage_list[] = $history_info;
        }

        return $history_linkage_list;
    }
}
