<?php
App::uses('Controller', 'Controller');
class AppController extends Controller
{
    // ジャンクション関連
    // ジャンクション終了時の戻りURL用セッションキー
    const JUNCTION_URL_KEY = 'app.data.junction';

    public function beforeFilter()
    {
        parent::beforeFilter();
    }

    public function afterFilter()
    {
        parent::afterFilter();
    }

    public function beforeRender()
    {
        parent::beforeRender();
    }

    /**
     * ジャンクション開始ポイントでコールする
     *
     * 内部的には、
     * ジャンクション戻り処理に必要なパラメータの保存を行う
     * @return type
     */
    protected function _startJunction()
    {
        // ジャンクション情報が来てれば、セッションに保存
        // ジャンクションフロー完了後のリダイレクト情報に使用する
        $junction_controller = filter_input(INPUT_GET, 'c');
        $junction_action = filter_input(INPUT_GET, 'a');
        $junction_params_str = filter_input(INPUT_GET, 'p');

        if ($junction_controller && $junction_action) {
            $junction_params = array();
            parse_str($junction_params_str, $junction_params);
            $junction = [
                'controller' => $junction_controller,
                'action' => $junction_action,
                'params' => $junction_params,
            ];
            CakeLog::write(DEBUG_LOG, '_startJunction ' . print_r($junction, true));
            CakeSession::write(self::JUNCTION_URL_KEY, $junction);
        } else {
            CakeSession::delete(self::JUNCTION_URL_KEY);
        }

        return;
    }

    /**
     * ジャンクション終了ポイントでコールする
     *
     * 内部的にはパラメータがセッションにあれば、リダイレクトかける
     * @return type
     */
    protected function _endJunction()
    {
        // ジャンクション情報設定されてるならリダイレクト
        $junction = CakeSession::read(self::JUNCTION_URL_KEY);
        CakeSession::delete(self::JUNCTION_URL_KEY);
        CakeLog::write(DEBUG_LOG, '_endJunction ' . print_r($junction, true));
        if (!empty($junction)) {
            $this->redirect([
                'controller' => $junction['controller'],
                'action' => $junction['action'],
                '?' => $junction['params']
            ]);
        }

        return;
    }

    /**
     * 全角半角変換　Mac全角ハイフン対応版
     *
     * @access    public
     * @param     全角文字列
     * @return    半角文字列
     */
    /// 全角変換
    public static function _wrapConvertKana($str)
    {
        $phyhen = array(
            '-',
            '﹣',
            '－',
            '−',
            '⁻',
            '₋',
            '‐',
            '‑',
            '‒',
            '–',
            '—',
            '―',
            '﹘',
            'ー'
        );

        return mb_convert_kana(str_replace($phyhen, '', $str), 'nhk', "utf-8");
    }

    /**
     * 非表示にする文字を含んでいるかチェック
     *
     * @access    public
     * @param     全角文字列
     * @return    boolean
     */
    /// 全角変換
    public static function _isNoDispAnnouncement($str)
    {
        // クラウド部室に関するお知らせ
        if (strpos($str,NEWS_NO_DISP_CHECK_WORD_CLUB) !== false) {
            return true;
        }
        return false;
    }

    /*
     * Asteriaに郵便番号形式を合わせる
     * @param string $_postal 郵便番号
     * @return string $_postal 郵便番号 d{3}-d{4}
     */
    public function _editPostalFormat($_postal)
    {
        if (preg_match('/^\d{7}$/', $_postal)) {
            $_postal = substr($_postal, 0, 3) . '-' . substr($_postal, 3);
        }
        return $_postal;
    }

    /**
     * ログイン時、ログイン済 ユーザ状態によって遷移先を変更
     *
     * @access    public
     * @param
     * @return    遷移先へ遷移
     */
    public function _switchRedirct()
    {
        // 1 Sneaker
        if ($this->Customer->isSneaker()) {

            // エントリユーザかどうか
            if ($this->Customer->isEntry()) {
                // CakeLog::write(DEBUG_LOG, '_switchRedirct isSneaker isEntry');
                return $this->redirect(['controller' => 'order', 'action' => 'add']);
            }

            // Sneakerで預けているboxが存在するか
            $summary = $this->InfoBox->getProductSummary(false);

            // スニーカが収納されている場合
            if (!empty($summary)) {
                // CakeLog::write(DEBUG_LOG, '_switchRedirctUrl isSneaker on item ');
                return $this->redirect(['controller' => 'item', 'action' => 'index']);
            }

            // ボックスを持っている場合
            $no_inbound_box = $this->InfoBox->getListForInbound();
            if (!empty($no_inbound_box)) {
                // CakeLog::write(DEBUG_LOG, '_switchRedirctUrl isSneaker no_inbound_box');
                return $this->redirect(['controller' => 'inbound', 'action' => 'box/add']);
            }

            // アイテムなし、ボックス未購入
            // CakeLog::write(DEBUG_LOG, '_switchRedirctUrl isSneaker order ');
            return $this->redirect(['controller' => 'order', 'action' => 'add']);

        }

        // 直前のリファラーが初回購入動線の場合、購入ページに遷移
        if (in_array(CakeSession::read('app.data.session_referer'), ['FirstOrder/index'], true)) {

            $referer = $_SERVER['HTTP_REFERER'];
            // リファラ確認スイッチフラグを立てて、リファラー遷移後の再リファラ処理を防ぐ
            CakeSession::write('referer_switch_redirct_flg', true);

            CakeSession::delete('app.data.session_referer');

            // CakeLog::write(DEBUG_LOG, '_switchRedirctUrl'  . 'before FirstOrder referer [' . $referer . ']');
            return $this->redirect(['controller' => 'order', 'action' => 'add']);
        }

        // 遷移元がLPのラインナップ(購入動線の場合)で、購入へリダイレクト
        if (isset($_SERVER['HTTP_REFERER'])) {
            $referer = $_SERVER['HTTP_REFERER'];

            // リファラ確認スイッチフラグを立てて、リファラー遷移後の再リファラ処理を防ぐ
            CakeSession::write('referer_switch_redirct_flg', true);

            // order判定 フラグ
            $is_redirct = false;

            // order判定 mono詳細
            $static_content_url_mono = Configure::read('site.static_content_url') . '/lineup/mono.html';
            if (strpos($referer, $static_content_url_mono) !== false) {
                $is_redirct = true;
            }

            // order判定 mono詳細
            $static_content_url_hako = Configure::read('site.static_content_url') . '/lineup/hako.html';
            if (strpos($referer, $static_content_url_hako) !== false) {
                $is_redirct = true;
            }

            // order判定 mono詳細
            $static_content_url_cleaning = Configure::read('site.static_content_url') . '/lineup/cleaning.html';
            if (strpos($referer, $static_content_url_cleaning) !== false) {
                $is_redirct = true;
            }

            // order判定 フラグ有効の場合、画面遷移
            if ($is_redirct) {
                // CakeLog::write(DEBUG_LOG, '_switchRedirctUrl on mono referer order ');
                return $this->redirect(['controller' => 'order', 'action' => 'add']);
            }
        }

        // 以下、ユーザ状態により遷移先を変更
        // エントリーユーザではない
        if (!$this->Customer->isEntry()) {

            // ボックスの状態を取得
            $summary = $this->InfoBox->getProductSummary(false);

            // 3 MONOを預けている 遷移元確認
            // 4 入庫中アイテムあり
            if (array_key_exists(PRODUCT_CD_MONO, $summary)) {
                if (isset($_SERVER['HTTP_REFERER'])) {

                    $referer = $_SERVER['HTTP_REFERER'];

                    // #20214 Travel クローズ対応
                    // // travel判定 静的ページtravelから遷移した場合
                    // $static_content_url_travel = Configure::read('site.static_content_url') . '/lineup/travel.html';
                    // if (strpos($referer, $static_content_url_travel) !== false) {
                    //     // CakeLog::write(DEBUG_LOG, '_switchRedirctUrl on mono  referer travel ');
                    //     return $this->redirect(['controller' => 'travel', 'action' => 'mono']);
                    // }
                }

                // 遷移元がオプション静的ページでない場合 アイテム一覧に遷移
                return $this->redirect(['controller' => 'item', 'action' => 'index']);
            }

            // monoがない場合 静的オプションから遷移してきた場合購入ページへ遷移
            if (isset($_SERVER['HTTP_REFERER'])) {

                // order判定 フラグ
                $is_order_redirct = false;

                $referer = $_SERVER['HTTP_REFERER'];

                // #20214 Travel クローズ対応
                // // travel判定 静的ページtravelから遷移した場合
                // $static_content_url_travel = Configure::read('site.static_content_url') . '/lineup/travel.html';
                // if (strpos($referer, $static_content_url_travel) !== false) {
                //     $is_order_redirct = true;
                // }

                // monoがない場合でオプションページから遷移してきた場合、ボックス購入に遷移
                if ($is_order_redirct) {
                    // CakeLog::write(DEBUG_LOG, '_switchRedirctUrl not mono  referer option ');
                    return $this->redirect(['controller' => 'order', 'action' => 'add']);
                }
            }

            // 5 入庫中ボックスあり
            if (!empty($summary)) {
                // CakeLog::write(DEBUG_LOG, '_switchRedirctUrl on box');
                return $this->redirect(['controller' => 'box', 'action' => 'index']);
            }

            // 6 未入庫ボックスあり
            $no_inbound_box = $this->InfoBox->getListForInbound();
            if (!empty($no_inbound_box)) {
                // CakeLog::write(DEBUG_LOG, '_switchRedirctUrl no_inbound_box');
                return $this->redirect(['controller' => 'inbound', 'action' => 'box/add']);
            }
        }

        $referer = $_SERVER['HTTP_REFERER'];
        $static_content_url = Configure::read('site.static_content_url');
        if (strpos($referer, $static_content_url) !== false) {
            return $this->redirect('/');
        }

        // CakeLog::write(DEBUG_LOG, '_switchRedirctUrl None-aggressive user');
        return $this->redirect(['controller' => 'order', 'action' => 'add']);
    }
}
