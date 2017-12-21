<?php

App::uses('AppController', 'Controller');
App::uses('AppSecurity', 'Lib');

class MinikuraController extends AppController
{
    public $helpers = ['Html', 'Title'];
    public $uses = ['CustomerLogin', 'Announcement', 'InfoBox'];
    public $components = ['Customer', 'Address'];

    // アクセス許可
    protected $checkLogined = true;

    protected $paginate = array(
        'limit' => 10,
        'paramType' => 'querystring'
    );

    public function beforeFilter()
    {
        parent::beforeFilter();

        // * Attack Request Block
        AppSecurity::blockAttackRequest();

        CakeSession::start();

        // use customer for view
        $this->set('customer', $this->Customer);

        // ログインチェック
        if ($this->checkLogined) {
            if (!$this->Customer->isLogined()) {
                $query_string_url = Router::reverse($this->request);

                $start_pos = strpos($query_string_url, '/', 1);
                if ($start_pos  === false) {
                    // コントローラ名＋パラメータ名 item?product=
                    $set_controller = $this->name;
                    $start_pos = strlen($set_controller);
                } else {
                    // コントローラ名＋アクション名
                    $set_controller = substr($query_string_url, 0, $start_pos + 1);
                }

                $end_pos = strpos($query_string_url, '?');

                $set_action = substr($query_string_url, $start_pos + 1);
                if ($set_action === false) {
                    // コントローラ名のみ /outbound
                    $set_action = $this->request->action;
                }

                // actionにパラメータが入り混んでいる場合
                if (strpos($set_action, '?') !== false) {
                    // コントローラ名＋パラメータ名 item?product=
                    $set_action = $this->request->action;
                }

                if ($end_pos !== false) {
                    if ($start_pos + 1 !== $end_pos) {
                        // コントローラ名＋アクション名 + パラメータ
                        $set_action = substr($query_string_url, $start_pos + 1, $end_pos);
                    }
                }

                $set_param = [
                    'c' => $set_controller,
                    'a' => $set_action,
                    'p' => http_build_query($this->request->query),
                ];

                return $this->redirect(['controller' => 'login', 'action' => 'index', 'customer' => false, '?' => $set_param]);
            }

            // リファラ確認スイッチフラグが立っていないこと
            if(is_null(CakeSession::read('referer_switch_redirct_flg'))) {

                // リファラ確認
                if (isset($_SERVER['HTTP_REFERER'])) {
                    $referer = $_SERVER['HTTP_REFERER'];
                    $static_content_url = Configure::read('site.static_content_url');
                    // CakeLog::write(DEBUG_LOG, 'MinikuraController'  . ' referer [' . $referer . '] static_content_url [' . $static_content_url . ']');

                    if (strpos($referer, $static_content_url) !== false) {
                        // CakeLog::write(DEBUG_LOG, 'MinikuraController referer _switchRedirct ');

                        // リファラ確認スイッチフラグを立てて、リファラー遷移後の再リファラ処理を防ぐ
                        CakeSession::write('referer_switch_redirct_flg', true);

                        // ユーザ状態によって遷移先を変更
                        $this->_switchRedirct();
                    }
                }
            } else {
                // リファラ確認スイッチフラグを削除
                CakeSession::delete('referer_switch_redirct_flg');
            }

            // 負債ユーザ遷移制限
            if ($this->Customer->isPaymentNG() && $this->request->prefix !== 'paymentng') {
                if ($this->Customer->hasCreditCard()) {
                    if ($this->Customer->isAmazonPay()) {
                        return $this->redirect(['controller' => 'credit_card', 'action' => 'edit_amazon_pay', 'paymentng' => true]);
                    }else {
                        return $this->redirect(['controller' => 'credit_card', 'action' => 'edit', 'paymentng' => true]);
                    }
                } else {
                    if ($this->Customer->isAmazonPay()) {
                        return $this->redirect(['controller' => 'credit_card', 'action' => 'edit_amazon_pay', 'paymentng' => true]);
                    }else {
                        $this->Flash->paymentng_no_credit_card('');
                        return $this->redirect(['controller' => 'login', 'action' => 'logout']);
                    }
                }
            }

            // ご利用中サービスの集計
            $this->set('product_summary', []);
            if (!$this->Customer->isEntry()) {
                $summary = $this->InfoBox->getProductSummary(false);
                $this->set('product_summary', $summary);
                // 出庫済み含めた利用
                $summary_all = $this->InfoBox->getProductSummary(true, 'summary_all');
                $this->set('summary_all', $summary_all);
            }
        }

        // アクセス拒否
        if ($this->isAccessDeny()) {
            return $this->redirect(['controller' => 'MyPage', 'action' => 'index', 'customer' => false]);
        }

        // item, boxのactiveステータス
        $this->set('active_status', $this->getActiveStatus());

    }

    protected function isAccessDeny()
    {
        return false;
    }

    public function beforeRender()
    {
        parent::beforeRender();
        $this->response->disableCache();
    }

    public function afterFilter()
    {
        parent::afterFilter();

        // 転送処理の妨げになる一時除外
        //* Click Jacking Block
        // AppSecurity::blockClickJacking();
    }

    /**
     * 商品コード チェック
     *
     * @access      private
     * @param       array $product 商品情報
     * @return      boolean
     */
    protected function checkProduct($product = null)
    {
        if(empty($product)) return true;

        // sneakers のユーザに minikura の商品を見せない（逆も然り）
        $oem_cd = $this->Customer->getInfo()['oem_cd'];

        // 各OEMのproductリスト生成
        foreach (IN_USE_SERVICE['minikura'] as $service_data) {
            $minikura_services[] = $service_data['product'];
        }
        foreach (IN_USE_SERVICE['sneakers'] as $service_data) {
            $sneakers_services[] = $service_data['product'];
        }

        // oem_cdに属するかどうかをチェック
        if ($oem_cd === OEM_CD_LIST['sneakers']) {
            if (!in_array($product, $sneakers_services)) {
                return false;
            }
        } else {
            if (!in_array($product, $minikura_services)) {
                return false;
            }
        }
        return true;
    }

    /**
     * item, boxのリンクアクティブを取得
     * todo: 暫定処置 View/Elements/sidebar.ctp 内でPHPでactiveを表示している。もっと汎用的な方法を考える必要がある
     */
    protected function getActiveStatus() 
    {
        $url = Router::url();

        $active_status = [
            'item' => [
                'toggle' => false,
                'all' => false,
                'mono' => false,
                'mono_direct' => false,
                'cargo01' => false,
                'cargo02' => false,
                'cleaning' => false,
                'shoes' => false,
                'sneakers' => false,
            ],
            'box' => [
                'toggle' => false,
                'all' => false,
                'mono' => false,
                'hako' => false,
                'mono_direct' => false,
                'cargo01' => false,
                'cargo02' => false,
                'cleaning' => false,
                'shoes' => false,
                'sneakers' => false,
            ],
            'cleaning' => false,
            'travel' => false,
        ];

        $active_status_tmp = [];


        if (isset($this->request->query['product'])) {
            if (preg_match('/\/item/', $url)) {
                $active_status['item']['toggle'] = true;
                $active_status_tmp = $active_status['item'];
            } elseif (preg_match('/\/box/', $url)) {
                $active_status['box']['toggle'] = true;
                $active_status_tmp = $active_status['box'];
            }
            switch (true) {
                case $this->request->query['product'] === 'mono':
                    $active_status_tmp['mono'] = true;
                    break;
                case $this->request->query['product'] === 'hako':
                    $active_status_tmp['hako'] = true;
                    break;
                case $this->request->query['product'] === 'mono_direct':
                    $active_status_tmp['mono_direct'] = true;
                    break;
                case $this->request->query['product'] === 'cargo01':
                    $active_status_tmp['cargo01'] = true;
                    break;
                case $this->request->query['product'] === 'cargo02':
                    $active_status_tmp['cargo02'] = true;
                    break;
                case $this->request->query['product'] === 'cleaning':
                    $active_status_tmp['cleaning'] = true;
                    break;
                case $this->request->query['product'] === 'shoes':
                    $active_status_tmp['shoes'] = true;
                    break;
                case $this->request->query['product'] === 'sneakers':
                    $active_status_tmp['sneakers'] = true;
                    break;
                default:
                    $active_status_tmp['all'] = true;
                    break;
            }
            if (preg_match('/\/item/', $url)) {
                $active_status['item'] = $active_status_tmp;
            } elseif (preg_match('/\/box/', $url)) {
                $active_status['box'] = $active_status_tmp;
            }
        }  elseif (preg_match('/\/travel/', $url)) {
            $active_status['travel'] = true;
        }  elseif (preg_match('/\/cleaning/', $url)) {
            $active_status['cleaning'] = true;
        }
        return $active_status;
    }
}
