<?php

App::uses('AppController', 'Controller');
App::uses('InfoItem', 'Model');
App::uses('InfoBox', 'Model');
App::uses('DevOrderId', 'Model');
App::uses('DevWorkId', 'Model');
App::uses('DevDeliVeryDone', 'Model');
App::uses('DevDeliVeryCancel', 'Model');
App::uses('DevInboundDone', 'Model');
App::uses('DevOutboundDone', 'Model');
App::uses('DevBilling', 'Model');
App::uses('DevUserApplying', 'Model');
App::uses('DevUserDebt', 'Model');
App::uses('OutboundList', 'Model');

class DevController extends AppController
{
    public function index()
    {
        $this->layout = "";
        $data = [
            'oem_key' => ['oem_key' => Configure::read(ApiModel::CONFIG_API_OEMKEY)],
            'token' => $this->customer->token,
            'info' => $this->customer->info,
        ];
        $this->set('data', $data);

        // order, work
        $order = (new DevOrderId())->apiGet();
        $this->set('order_ids', $order->results);
        $work = (new DevWorkId())->apiGet(['work_type' => '001']);
        $this->set('work_ids_001', $work->results);
        $work = (new DevWorkId())->apiGet(['work_type' => '003']);
        $this->set('work_ids_003', $work->results);
        $work = (new DevWorkId())->apiGet(['work_type' => '006']);
        $this->set('work_ids_006', $work->results);

        InfoBox::deleteAllCache();
        $ib = new InfoBox();
        $r = $ib->apiGet();
        $boxList = $r->results;
        $data = [];
        foreach ($boxList as $b) {
            $a = [];
            $a['product_cd'] = $b['product_cd'];
            $a['box_id'] = $b['box_id'];
            $a['box_name'] = $b['box_name'];
            $data[$b['box_status']][] = $a;
        }
        ksort($data);
        $this->set('boxData', $data);
        unset($data);

        InfoItem::deleteAllCache();
        $ii = new InfoItem();
        $r = $ii->apiGet();
        $itemList = $r->results;
        $data = [];
        foreach ($itemList as $b) {
            $a = [];
            $a['item_status'] = $b['item_status'];
            $a['item_id'] = $b['item_id'];
            $a['item_name'] = $b['item_name'];
            $data[$b['box_id']][] = $a;
        }
        $this->set('timeData', $data);
    }

    private function after($res)
    {
        if ($res->isSuccess()) {
            return $this->redirect(['action' => 'index']);
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
        $id = Hash::get($this->request->query, 'work_id');
        $dev = new DevOutboundDone();
        $res = $dev->apiPatch(['work_id' => $id]);
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
        // CustomerData::delete();
        // OutboundList::delete();
        return $this->redirect(['action' => 'index']);
    }
}
