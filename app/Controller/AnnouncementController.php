<?php

App::uses('MinikuraController', 'Controller');
App::uses('Receipt', 'Model');
App::uses('Billing', 'Model');
App::uses('ReceiptDetail', 'Model');

class AnnouncementController extends MinikuraController
{
    const MODEL_NAME = 'Announcement';

    /**
     * 制御前段処理.
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->loadModel(self::MODEL_NAME);
    }

    /**
     * 一覧.
     */
    public function index()
    {
        $all = $this->Announcement->apiGetResults();
        CakeLog::write(DEBUG_LOG, $this->name . '::' . $this->action . ' all ' . print_r($all, true));
        // 特定文字の含まれるメッセージは非表示
        foreach($all as $key => $value) {
            if($this->_isNoDispAnnouncement($value['text'])) {
                unset($all[$key]);
            }
        }
        $list = $this->paginate($all);
        $this->set('announcements', $list);
    }

    /**
     *
     */
    public function detail()
    {
        // アマゾンペイメント対応
        $this->set('isAmazonPayLogin', false);
        if ($this->Customer->isAmazonPay()) {
            $this->set('isAmazonPayLogin', true);
        }

        $id = $this->params['id'];
        $data = $this->Announcement->apiGetResultsFind([], ['announcement_id' => $id]);
        if (!empty($data)) {
            $this->set('announcement', $data);
            $this->Announcement->apiPatch(['announcement_id' => $id]);

            if ($data['category_id'] === ANNOUNCEMENT_CATEGORY_ID_BILLING) {
                $billing = new Billing();
                $res = $billing->apiGet([
                    'announcement_id' => $id,
                    'category_id' => $data['category_id']
                ]);
                if ($res->isSuccess()) {
                    $this->set('billing', $res->results);
                }
            }
        }
    }

    /**
     * 領収証ダウンロード
     * @return [type] [description]
     */
    public function receipt()
    {
        $id = $this->params['id'];
        if ($this->request->is('post')) {
            $receipt = new Receipt();
            $data = $this->Announcement->apiGetResultsFind([], ['announcement_id' => $id]);
            if (!empty($data)) {
                if ($data['category_id'] === ANNOUNCEMENT_CATEGORY_ID_RECEIPT) {
                    $res = $receipt->apiGet([
                        'announcement_id' => $id,
                        'category_id' => $data['category_id']
                    ]);
                    if ($res->isSuccess() || count($res->results) === 1) {
                        $name = $res->results[0]['file_name'];
                        $binary = base64_decode($res->results[0]['receipt']);
                        $this->autoRender = false;
                        $this->response->type('pdf');
                        $this->response->download($name);
                        $this->response->body($binary);
                        return;
                    } else {
                        $this->Flash->set($res->error_message);
                    }
                }
                if ($data['category_id'] === ANNOUNCEMENT_CATEGORY_ID_KIT_RECEIPT) {
                    $receiptDetail = new ReceiptDetail();
                    $res = $receiptDetail->apiGet([
                        'announcement_id' => $id
                    ]);
                    if ($res->isSuccess() && count($res->results) === 1) {
                        $timelyReceiptId = $res->results[0]['timely_receipt_id'];
                        $name = "receipt{$timelyReceiptId}.pdf";
                        $binary = base64_decode($res->results[0]['receipt_data']);
                        $this->autoRender = false;
                        $this->response->type('pdf');
                        $this->response->download($name);
                        $this->response->body($binary);
                        return;
                    } else {
                        // $this->Flash->set($res->error_message);
                        $this->Flash->set('領収証を発行できません。お問い合わせフォームにて領収証発行を依頼ください。');
                    }
                }
            }
        }
        return $this->redirect(['action' => 'detail', 'id' => $id]);
    }
}
